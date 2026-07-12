import requests
import json

url = 'http://127.0.0.1:5000/predict'

# Test payloads: list of different student scenarios
test_cases = [
    {
        "description": "Kasus 1: Nilai tinggi, kehadiran baik, kesulitan sedang (Harapan: Program Reguler)",
        "payload": {
            "nilai": 85,
            "tingkat_kesulitan": "Sedang",
            "jam_belajar": 4,
            "kehadiran": "Baik",
            "gaya_belajar": "Visual"
        }
    },
    {
        "description": "Kasus 2: Nilai sedang, kehadiran cukup, kesulitan sedang (Harapan: Program Intensif)",
        "payload": {
            "nilai": 70,
            "tingkat_kesulitan": "Sedang",
            "jam_belajar": 3,
            "kehadiran": "Cukup",
            "gaya_belajar": "Auditori"
        }
    },
    {
        "description": "Kasus 3: Nilai rendah, kehadiran kurang (Harapan: Pendampingan Intensif)",
        "payload": {
            "nilai": 45,
            "tingkat_kesulitan": "Sulit",
            "jam_belajar": 1,
            "kehadiran": "Kurang",
            "gaya_belajar": "Kinestetik"
        }
    }
]

headers = {'Content-Type': 'application/json'}

print("MEMULAI PENGUJIAN FLASK API:")
print("="*60)

for case in test_cases:
    desc = case["description"]
    payload = case["payload"]
    
    print(f"\nRunning: {desc}")
    print(f"Mengirim payload: {json.dumps(payload, ensure_ascii=False)}")
    
    try:
        response = requests.post(url, json=payload, headers=headers)
        print(f"Status Code: {response.status_code}")
        if response.status_code == 200:
            print("Hasil Prediksi:")
            print(json.dumps(response.json(), indent=4, ensure_ascii=False))
        else:
            print(f"Error Respon: {response.text}")
    except Exception as e:
        print(f"Gagal menghubungi server Flask: {str(e)}")
        print("Silakan jalankan Flask API terlebih dahulu dengan perintah: python ML/app.py")
        break
        
print("\n" + "="*60)
print("Pengujian selesai.")
