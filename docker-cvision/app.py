import os
import io
import torch
import torch.nn as nn
from flask import Flask, request, jsonify
from sentence_transformers import SentenceTransformer, util
from PIL import Image

app = Flask(__name__)

# --- 1. INISIALISASI MODEL SECARA GLOBAL ---
# Model di-load HANYA SATU KALI saat container/aplikasi pertama kali menyala.
# Jika diletakkan di dalam route, setiap request akan memakan waktu lama untuk load model.
device = "cuda" if torch.cuda.is_available() else "cpu"
print(f"[*] Menginisialisasi model CLIP di {device}...")

# Menggunakan ViT-B-32 untuk hasil standar. ViT-L-14 jika butuh presisi tinggi.
model = SentenceTransformer('clip-ViT-B-32', device=device)
print("[*] Model siap menerima request!")

@app.route('/vision-comparison', methods=['POST'])
def vision_comparison():
    try:
        # Memastikan Laravel/Client mengirimkan file dengan key 'image1' dan 'image2'
        if 'image1' not in request.files or 'image2' not in request.files:
            return jsonify({
                "status": "error",
                "message": "Parameter image1 dan image2 wajib diisi dalam format form-data"
            }), 400

        file1 = request.files['image1']
        file2 = request.files['image2']

        # --- 2. BACA GAMBAR DARI MEMORI ---
        # Membaca gambar langsung dari stream request (RAM) tanpa menyimpannya ke harddisk.
        # Ini mencegah penumpukan file temporary di dalam container Docker.
        image1 = Image.open(io.BytesIO(file1.read())).convert('RGB')
        image2 = Image.open(io.BytesIO(file2.read())).convert('RGB')

        # --- 3. PROSES EMBEDDING ---
        embeddings = model.encode([image1, image2])

        # --- 4. HITUNG KEMIRIPAN ---
        cos_sim = util.cos_sim(embeddings[0], embeddings[1]).item()

        # --- 5. LOGIKA THRESHOLDING ---
        if cos_sim > 0.8:
            keterangan = "Konteks gambar sangat mirip (Kemungkinan objek yang sama)"
        elif cos_sim > 0.5:
            keterangan = "Gambar memiliki keterkaitan, konteksnya serupa"
        else:
            keterangan = "Gambar tidak memiliki kemiripan konteks"

        # --- 6. KEMBALIKAN RESPONSE JSON ---
        return jsonify({
            "score": round(cos_sim, 4), # Dibulatkan 4 angka di belakang koma
            "text": keterangan
        }), 200

    except Exception as e:
        # Menangkap error (misal file korup atau bukan gambar) agar Flask tidak crash
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500

if __name__ == '__main__':
    # Parameter host='0.0.0.0' wajib agar port terekspos keluar dari container Docker
    # Port 5000 sesuai dengan yang kita mapping di docker-compose.yml
    app.run(host='0.0.0.0', port=5000, debug=False)