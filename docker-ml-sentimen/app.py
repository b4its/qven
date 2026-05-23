from __future__ import annotations

import logging
import os
import re  # Ditambahkan untuk pencocokan kata yang lebih akurat
from functools import wraps
from typing import Any

from flask import Flask, jsonify, request
from flask_cors import CORS

# --- KONFIGURASI DATA SENTIMEN ---------------------------------------------

mbg_negatif = {
    "Kelompok 1: Kualitas & Rasa Menu (Negatif)": "Kualitas rasa atau kematangan bahan baku tidak memenuhi standar konsumsi.",
    "Kelompok 2: Porsi & Pemenuhan Gizi (Negatif)": "Kuantitas porsi atau variasi lauk tidak mencukupi standar kecukupan gizi.",
    "Kelompok 3: Higienitas & Penyajian (Negatif)": "Ditemukan indikasi kontaminasi atau penyajian yang tidak higienis.",
    "Kelompok 4: Respon & Psikologis Penerima (Negatif)": "Adanya penolakan atau ketidakpuasan psikologis dari penerima manfaat.",
    "Kelompok 5: Kondisi Fisik & Tampilan Menu (Negatif)": "Estetika dan kondisi fisik makanan buruk (layu/hancur).",
    "Kelompok 6: Dampak & Keluhan Kesehatan (Negatif)": "Makanan menyebabkan gangguan kesehatan pasca-konsumsi.",
    "Kelompok 7: Istilah Umum Sentimen Negatif": "Ketidaklayakan umum berdasarkan standar operasional prosedur (SOP).",
}

mbg_positif_reasons = {
    "Kelompok 1: Kualitas & Rasa Menu (Positif)": "Kualitas rasa, aroma, dan tekstur makanan sangat baik dan menggugah selera.",
    "Kelompok 2: Porsi & Pemenuhan Gizi (Positif)": "Porsi melimpah dan komposisi gizi seimbang sesuai standar MBG.",
    "Kelompok 3: Higienitas & Penyajian (Positif)": "Standar kebersihan dan higienitas penyajian terjaga dengan sangat baik.",
    "Kelompok 4: Respon & Psikologis Penerima (Positif)": "Penerima manfaat menunjukkan antusiasme dan apresiasi tinggi terhadap menu.",
    "Kelompok 5: Kondisi Fisik & Kualitas Bahan (Positif)": "Bahan pangan yang digunakan terlihat segar, premium, dan berkualitas tinggi.",
    "Kelompok 6: Dampak & Manfaat Kesehatan (Positif)": "Memberikan dampak kebugaran dan energi positif bagi penerima manfaat.",
    "Kelompok 7: Istilah Umum Sentimen Positif": "Program berhasil mencapai target kepuasan nutrisi dan kelayakan konsumsi.",
}

keywords_db = {
    "negatif": {
        "Kelompok 1: Kualitas & Rasa Menu (Negatif)": ["hambar", "asin", "amis", "hapak", "alot", "anyep", "enek", "pahit", "asam", "lembek", "keras", "mentah", "sengak", "berminyak", "lendir", "gosong", "kecut", "anyir", "sepat", "hambal"],
        "Kelompok 2: Porsi & Pemenuhan Gizi (Negatif)": ["sedikit", "irit", "pelit", "kurang", "mini", "tipis", "kosong", "serpihan", "jomplang", "defisit", "minim", "tandus", "porsi-kucing", "terbatas"],
        "Kelompok 3: Higienitas & Penyajian (Negatif)": ["kotor", "basi", "lalat", "rambut", "ulat", "berdebu", "kumal", "bau", "berjamur", "lumer", "tercemar", "masam", "becek", "pengap", "menjijikkan"],
        "Kelompok 6: Dampak & Keluhan Kesehatan (Negatif)": ["mulas", "diare", "pusing", "alergi", "sembelit", "sakit", "keracunan", "kembung"],
    },
    "positif": {
        "Kelompok 1: Kualitas & Rasa Menu (Positif)": ["lezat", "gurih", "segar", "empuk", "pulen", "harum", "nikmat", "pas", "matang", "renyah", "sedap", "mantap"],
        "Kelompok 2: Porsi & Pemenuhan Gizi (Positif)": ["kenyang", "padat", "melimpah", "puas", "cukup", "lengkap", "tebal", "berlebih", "berbobot", "ideal", "proporsional"],
        "Kelompok 3: Higienitas & Penyajian (Positif)": ["bersih", "higienis", "rapi", "steril", "estetik", "tertutup", "teratur"],
        "Kelompok 4: Respon & Psikologis Penerima (Positif)": ["gembira", "antusias", "lahap", "bersyukur", "senang", "ketagihan", "bangga", "ceria", "bahagia", "terbantu", "memuji", "menghabiskan"],
        "Kelompok 6: Dampak & Manfaat Kesehatan (Positif)": ["sehat", "kuat", "bugar", "cerdas", "fokus", "berstamina", "nyaman", "bergizi"],
    },
}

# --- KONFIGURASI APP --------------------------------------------------------

API_KEY = os.getenv("MBG_API_KEY", "").strip() or None
ALLOWED_ORIGINS = [
    o.strip() for o in os.getenv("MBG_ALLOWED_ORIGINS", "*").split(",") if o.strip()
] or ["*"]
MAX_TEXT_LEN = int(os.getenv("MBG_MAX_TEXT_LEN", "2000"))
MAX_BATCH = int(os.getenv("MBG_MAX_BATCH", "100"))
PORT = int(os.getenv("MBG_PORT", "5000"))

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(name)s: %(message)s",
)
logger = logging.getLogger("mbg_sentimen")


# --- FUNGSI ANALISIS --------------------------------------------------------

def _find_match(text_lower: str, groups: dict[str, list[str]]) -> tuple[str, str] | None:
    """Cari grup pertama yang punya keyword cocok menggunakan batas kata (word boundary)."""
    for group, kws in groups.items():
        for kw in kws:
            # Menggunakan \b untuk memastikan kecocokan kata utuh (bukan substring)
            # Contoh: \basin\b tidak akan mengonfirmasi kata 'masing-masing'
            pattern = rf"\b{re.escape(kw)}\b"
            if re.search(pattern, text_lower):
                return group, kw
    return None


def api_sentiment_analysis(text: str) -> dict[str, Any]:
    """Analisis sentimen sebuah teks ulasan MBG."""
    text_lower = text.lower()

    hit = _find_match(text_lower, keywords_db["negatif"])
    if hit:
        group, kw = hit
        return {
            "text": text,
            "status": "TIDAK LAYAK",
            "kategori": group,
            "alasan": mbg_negatif.get(group, "Sentimen negatif terdeteksi."),
            "matched_keyword": kw,
        }

    hit = _find_match(text_lower, keywords_db["positif"])
    if hit:
        group, kw = hit
        return {
            "text": text,
            "status": "LAYAK",
            "kategori": group,
            "alasan": mbg_positif_reasons.get(group, "Sentimen positif terdeteksi."),
            "matched_keyword": kw,
        }

    return {
        "text": text,
        "status": "LAYAK",
        "kategori": "Umum",
        "alasan": "Makanan memenuhi standar kualitas dasar MBG.",
        "matched_keyword": None,
    }


# --- FLASK APP --------------------------------------------------------------

def create_app() -> Flask:
    app = Flask(__name__)
    app.config["JSON_SORT_KEYS"] = False
    app.config["MAX_CONTENT_LENGTH"] = 1 * 1024 * 1024  # 1 MB

    CORS(
        app,
        resources={r"/*": {"origins": ALLOWED_ORIGINS}},
        allow_headers=["Content-Type", "X-API-Key"],
        methods=["GET", "POST", "OPTIONS"],
    )

    def require_api_key(fn):
        @wraps(fn)
        def wrapper(*args, **kwargs):
            if API_KEY is not None:
                provided = request.headers.get("X-API-Key", "")
                if provided != API_KEY:
                    return jsonify({"error": "API key tidak valid atau tidak diberikan."}), 401
            return fn(*args, **kwargs)
        return wrapper

    def validate_text(value: Any) -> tuple[str | None, str | None]:
        if not isinstance(value, str):
            return None, "Field 'text' harus berupa string."
        text = value.strip()
        if not text:
            return None, "Field 'text' tidak boleh kosong."
        if len(text) > MAX_TEXT_LEN:
            return None, f"Teks melebihi panjang maksimum ({MAX_TEXT_LEN} karakter)."
        return text, None

    @app.route("/health", methods=["GET"])
    def health():
        return jsonify({
            "status": "ok",
            "service": "ML_Sentimen_MBG",
            "version": "1.0.0",
        })

    @app.route("/categories", methods=["GET"])
    @require_api_key
    def categories():
        return jsonify({
            "negatif": {
                "alasan": mbg_negatif,
                "keywords": keywords_db["negatif"],
            },
            "positif": {
                "alasan": mbg_positif_reasons,
                "keywords": keywords_db["positif"],
            },
        })

    @app.route("/analyze", methods=["POST"])
    @require_api_key
    def analyze_endpoint():
        data = request.get_json(silent=True)
        if not isinstance(data, dict) or "text" not in data:
            return jsonify({"error": "Payload JSON harus berisi field 'text'."}), 400

        text, err = validate_text(data["text"])
        if err:
            return jsonify({"error": err}), 400

        result = api_sentiment_analysis(text)
        logger.info("analyze status=%s kategori=%s", result["status"], result["kategori"])
        return jsonify(result)

    @app.route("/analyze/batch", methods=["POST"])
    @require_api_key
    def analyze_batch_endpoint():
        data = request.get_json(silent=True)
        if not isinstance(data, dict) or "texts" not in data:
            return jsonify({"error": "Payload JSON harus berisi field 'texts' (list)."}), 400

        texts = data["texts"]
        if not isinstance(texts, list):
            return jsonify({"error": "Field 'texts' harus berupa list string."}), 400
        if len(texts) == 0:
            return jsonify({"error": "Field 'texts' tidak boleh kosong."}), 400
        if len(texts) > MAX_BATCH:
            return jsonify({"error": f"Jumlah teks melebihi batas maksimum ({MAX_BATCH})."}), 400

        results = []
        for idx, item in enumerate(texts):
            text, err = validate_text(item)
            if err:
                results.append({"index": idx, "error": err})
                continue
            res = api_sentiment_analysis(text)
            res["index"] = idx
            results.append(res)

        summary = {
            "total": len(results),
            "layak": sum(1 for r in results if r.get("status") == "LAYAK"),
            "tidak_layak": sum(1 for r in results if r.get("status") == "TIDAK LAYAK"),
            "error": sum(1 for r in results if "error" in r),
        }
        logger.info("analyze_batch %s", summary)
        return jsonify({"summary": summary, "results": results})

    @app.errorhandler(404)
    def not_found(_e):
        return jsonify({"error": "Endpoint tidak ditemukan."}), 404

    @app.errorhandler(405)
    def method_not_allowed(_e):
        return jsonify({"error": "Method tidak diizinkan untuk endpoint ini."}), 405

    @app.errorhandler(413)
    def payload_too_large(_e):
        return jsonify({"error": "Payload terlalu besar."}), 413

    @app.errorhandler(500)
    def internal_error(e):
        logger.exception("Internal server error: %s", e)
        return jsonify({"error": "Terjadi kesalahan internal pada server."}), 500

    return app


app = create_app()


if __name__ == "__main__":
    try:
        from waitress import serve
        logger.info("Menjalankan production server Waitress pada port %d", PORT)
        serve(app, host="0.0.0.0", port=PORT)
    except ImportError:
        logger.warning("Waitress tidak terpasang, fallback ke Flask dev server.")
        app.run(host="0.0.0.0", port=PORT, debug=False)