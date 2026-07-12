from flask import Flask, request, jsonify
import joblib
import pandas as pd
import os
from datetime import datetime

app = Flask(__name__)

# Load model and label encoders
model_path = os.path.join(os.path.dirname(__file__), 'model.pkl')
encoder_path = os.path.join(os.path.dirname(__file__), 'label_encoder.pkl')

if os.path.exists(model_path) and os.path.exists(encoder_path):
    model = joblib.load(model_path)
    label_encoders = joblib.load(encoder_path)
    print("Model dan Label Encoder berhasil dimuat.")
else:
    model = None
    label_encoders = None
    print("WARNING: model.pkl atau label_encoder.pkl belum dibuat. Jalankan train.py terlebih dahulu.")

# API request monitoring globals
total_requests = 0
last_prediction_time = "Belum ada request"


@app.route('/predict', methods=['POST'])
def predict():
    global model, label_encoders, total_requests, last_prediction_time

    # Check if model is loaded
    if model is None or label_encoders is None:
        if os.path.exists(model_path) and os.path.exists(encoder_path):
            model = joblib.load(model_path)
            label_encoders = joblib.load(encoder_path)
        else:
            return jsonify({'error': 'Model atau Label Encoder belum di-train.'}), 500

    # Get request JSON data
    data = request.get_json()
    if not data:
        return jsonify({'error': 'Invalid request: No JSON payload provided.'}), 400

    # Validate required parameters
    required_params = ['nilai', 'tingkat_kesulitan', 'jam_belajar', 'kehadiran', 'gaya_belajar']
    for param in required_params:
        if param not in data:
            return jsonify({'error': f'Missing parameter: {param}'}), 400

    try:
        # Extract features
        nilai = float(data['nilai'])
        tingkat_kesulitan = str(data['tingkat_kesulitan']).strip()
        jam_belajar = float(data['jam_belajar'])
        kehadiran = str(data['kehadiran']).strip()
        gaya_belajar = str(data['gaya_belajar']).strip()

        # Validation ranges
        if not (0 <= nilai <= 100):
            return jsonify({'error': 'Parameter nilai harus berada di rentang 0 s.d 100.'}), 400
        if not (1 <= jam_belajar <= 5):
            return jsonify({'error': 'Parameter jam_belajar harus berada di rentang 1 s.d 5.'}), 400

        # Check if categorical values are valid
        valid_kesulitan = label_encoders['tingkat_kesulitan'].categories_[0]
        valid_kehadiran = label_encoders['kehadiran'].categories_[0]
        valid_gaya = label_encoders['gaya_belajar'].categories_[0]

        if tingkat_kesulitan not in valid_kesulitan:
            return jsonify({'error': f'Nilai tingkat_kesulitan tidak valid. Pilihan: {list(valid_kesulitan)}'}), 400
        if kehadiran not in valid_kehadiran:
            return jsonify({'error': f'Nilai kehadiran tidak valid. Pilihan: {list(valid_kehadiran)}'}), 400
        if gaya_belajar not in valid_gaya:
            return jsonify({'error': f'Nilai gaya_belajar tidak valid. Pilihan: {list(valid_gaya)}'}), 400

        # Encode categorical features
        encoded_kesulitan = label_encoders['tingkat_kesulitan'].transform(
            pd.DataFrame([[tingkat_kesulitan]], columns=['tingkat_kesulitan']))[0][0]
        encoded_kehadiran = label_encoders['kehadiran'].transform(
            pd.DataFrame([[kehadiran]], columns=['kehadiran']))[0][0]
        encoded_gaya = label_encoders['gaya_belajar'].transform(
            pd.DataFrame([[gaya_belajar]], columns=['gaya_belajar']))[0][0]

        # Prepare feature vector
        feature_df = pd.DataFrame(
            [[nilai, encoded_kesulitan, jam_belajar, encoded_kehadiran, encoded_gaya]],
            columns=['nilai', 'tingkat_kesulitan', 'jam_belajar', 'kehadiran', 'gaya_belajar']
        )

        # Perform prediction
        pred_encoded = model.predict(feature_df)[0]
        probabilities = model.predict_proba(feature_df)[0]
        confidence = round(float(max(probabilities)) * 100, 2)

        # Decode predicted label
        pred_label = label_encoders['rekomendasi'].inverse_transform([pred_encoded])[0]

        # Update monitoring
        prediction_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        total_requests += 1
        last_prediction_time = prediction_time

        # Return ONLY prediction and confidence — all recommendation details built by Laravel
        return jsonify({
            'prediction': pred_label,
            'confidence': confidence
        })

    except ValueError as ve:
        return jsonify({'error': f'Format data numerik tidak sesuai: {str(ve)}'}), 400
    except Exception as e:
        return jsonify({'error': f'Terjadi kesalahan internal: {str(e)}'}), 500


@app.route('/health', methods=['GET'])
def health():
    global total_requests, last_prediction_time
    return jsonify({
        'status': 'Online',
        'model': 'Random Forest',
        'version': '2.0',
        'categories': [
            'Program Remedial Intensif',
            'Pendampingan Akademik',
            'Program Reguler',
            'Program Pengayaan'
        ],
        'total_requests': total_requests,
        'last_prediction_time': last_prediction_time
    })


if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000, debug=True)
