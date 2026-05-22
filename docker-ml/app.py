from flask import Flask, request, jsonify
from sklearn.ensemble import RandomForestClassifier, StackingClassifier
from sklearn.linear_model import LogisticRegression
from xgboost import XGBClassifier
import numpy as np
import logging
import json
import os

app = Flask(__name__)

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

# =====================================================================
# FASE 1: INISIALISASI & TRAINING MODEL STACKING ENSEMBLE
# =====================================================================
def load_and_train_model():
    logging.info("Memuat dataset dari dataset_gizi.json...")
    file_path = os.path.join(os.path.dirname(__file__), 'dataset_gizi.json')
    
    try:
        with open(file_path, 'r') as file:
            dataset = json.load(file)
            
        X_train_list = []
        y_train_list = []
        
        for item in dataset:
            X_train_list.append([
                item['kalori'], 
                item['protein'], 
                item['lemak'], 
                item['karbohidrat']
            ])
            y_train_list.append(int(item['label']))
            
        X_train = np.array(X_train_list, dtype=np.float32)
        y_train = np.array(y_train_list, dtype=int)
        
        logging.info(f"Berhasil memuat {len(dataset)} baris data training.")
        
    except Exception as e:
        logging.error(f"Gagal memuat dataset: {str(e)}. Menggunakan fallback darurat.")
        X_train = np.array([[600, 25, 20, 80], [200, 5, 5, 30]], dtype=np.float32)
        y_train = np.array([1, 0])

    logging.info("Melatih Model Stacking Ensemble (RF + XGB -> LogReg)...")
    
    # 1. Base Estimators: Model yang mendeteksi pola kompleks dan non-linear
    estimators = [
        ('rf', RandomForestClassifier(
            n_estimators=100, 
            max_depth=10, 
            min_samples_leaf=4, 
            random_state=42
        )),
        ('xgb', XGBClassifier(
            n_estimators=100,
            max_depth=6,
            learning_rate=0.05,
            eval_metric='logloss', 
            random_state=42
        ))
    ]

    # 2. Meta-Learner: Logistic Regression (Mencegah halu/overfitting dari Base Model)
    ensemble_model = StackingClassifier(
        estimators=estimators, 
        final_estimator=LogisticRegression(class_weight='balanced'),
        cv=5,
        n_jobs=-1 # Gunakan semua core CPU untuk training
    )

    ensemble_model.fit(X_train, y_train)
    logging.info("Model berhasil dilatih dengan akurasi terkalibrasi!")
    
    return ensemble_model

# Muat model ke memori saat server Flask dijalankan
ensemble_model = load_and_train_model()

# =====================================================================
# FASE 2: API ENDPOINT UNTUK LARAVEL
# =====================================================================
@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.json
        mbg_code = data.get('mbg_code', 'UNKNOWN')
        features = data.get('features', {})
        
        # Ekstraksi fitur secara aman
        kalori = float(features.get('kalori', 0))
        protein = float(features.get('protein', 0))
        lemak = float(features.get('lemak', 0))
        karbohidrat = float(features.get('karbohidrat', 0))
        
        logging.info(f"[{mbg_code}] Analisis - Kal:{kalori}, Pro:{protein}, Lem:{lemak}, Kar:{karbohidrat}")
        
        # Proteksi input (Jika AI Gemini gagal mendeteksi makronutrien sama sekali)
        if kalori == 0 and protein == 0 and lemak == 0 and karbohidrat == 0:
            return jsonify({
                'mbg_code': mbg_code,
                'prediction': "Gagal Dianalisis (Data Kosong)",
                'confidence_percentage': 0,
                'model_used': 'Rule_Based_Fallback'
            }), 200

        # Prediksi
        X_test = np.array([[kalori, protein, lemak, karbohidrat]], dtype=np.float32)
        prediction_class = ensemble_model.predict(X_test)[0]
        prediction_proba = ensemble_model.predict_proba(X_test)[0]
        
        # Penentuan Status
        if prediction_class == 1:
            status = "Layak Konsumsi"
            confidence = float(prediction_proba[1]) * 100
        else:
            status = "Gizi Tidak Seimbang (Tidak Layak)"
            confidence = float(prediction_proba[0]) * 100
            
        logging.info(f"[{mbg_code}] Hasil: {status} ({confidence:.2f}%)")
        
        return jsonify({
            'mbg_code': mbg_code,
            'prediction': status,
            'confidence_percentage': round(confidence, 2),
            'model_used': 'Stacking_Ensemble_RF_XGB_LogReg'
        }), 200

    except Exception as e:
        logging.error(f"Error prediksi: {str(e)}")
        return jsonify({
            'error': str(e),
            'prediction': 'Error Processing Data'
        }), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)