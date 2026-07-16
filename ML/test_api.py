import requests
import json

url = 'http://127.0.0.1:5000/predict'

# Test payloads: list of different student scenarios matching the new blueprint
test_cases = [
    {
        "description": "Kasus 1: Nilai tinggi, kehadiran baik (Harapan: Mahir)",
        "payload": {
            "nilai_tugas": 90,
            "nilai_kuis": 86,
            "kehadiran": "Baik",
            "study_duration": 4,
            "tingkat_kesulitan": "Sedang"
        }
    },
    {
        "description": "Kasus 2: Nilai sedang, kehadiran cukup (Harapan: Menengah)",
        "payload": {
            "nilai_tugas": 75,
            "nilai_kuis": 71,
            "kehadiran": "Cukup",
            "study_duration": 3,
            "tingkat_kesulitan": "Sedang"
        }
    },
    {
        "description": "Kasus 3: Nilai rendah, kehadiran kurang (Harapan: Dasar)",
        "payload": {
            "nilai_tugas": 45,
            "nilai_kuis": 41,
            "kehadiran": "Kurang",
            "study_duration": 1,
            "tingkat_kesulitan": "Sedang"
        }
    },
    {
        "description": "Kasus 4: Borderline - Nilai tinggi, kehadiran kurang (Harapan: Menengah)",
        "payload": {
            "nilai_tugas": 95,
            "nilai_kuis": 91,
            "kehadiran": "Kurang",
            "study_duration": 2,
            "tingkat_kesulitan": "Sedang"
        }
    }
]

headers = {'Content-Type': 'application/json'}

print("MEMULAI PENGUJIAN FLASK API BARU:")
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
        break
        
print("\n" + "="*60)
print("Pengujian selesai.")
