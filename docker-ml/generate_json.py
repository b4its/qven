import json
import random

def generate_realistic_dataset(num_samples):
    data = []
    
    for _ in range(num_samples):
        # Distribusi seimbang 50:50 untuk klasifikasi biner
        label = random.choice([0, 1])
        
        if label == 1:
            # LABEL 1: GIZI SEIMBANG (Makanan Utama ATAU Camilan Sehat)
            tipe_sehat = random.choice(["makanan_utama", "camilan_sehat"])
            
            if tipe_sehat == "makanan_utama":
                target_kalori = random.randint(350, 900)
                prop_protein = random.uniform(0.15, 0.25)
                prop_lemak = random.uniform(0.25, 0.30)
            else:
                # Skenario Camilan Sehat (seperti Salad Buah, Susu Kacang)
                target_kalori = random.randint(150, 349)
                prop_protein = random.uniform(0.10, 0.20) # Protein snack bisa lebih rendah
                prop_lemak = random.uniform(0.15, 0.35)
                
            protein = int((target_kalori * prop_protein) / 4)
            lemak = int((target_kalori * prop_lemak) / 9)
            karbohidrat = int((target_kalori - (protein * 4) - (lemak * 9)) / 4)
            
        else:
            # ==========================================
            # LABEL 0: TIDAK SEIMBANG / BURUK
            # ==========================================
            tipe_buruk = random.choice([
                "rendah_kalori", 
                "tinggi_lemak", 
                "karbo_kosong", 
                "kalori_berlebih",
                "kurang_protein" # Skenario spesifik wilayah 3T
            ])
            
            if tipe_buruk == "rendah_kalori":
                # Stunting risk: Porsi sangat kecil
                target_kalori = random.randint(150, 300)
                protein = random.randint(2, 8)
                lemak = random.randint(2, 8)
                karbohidrat = int((target_kalori - (protein * 4) - (lemak * 9)) / 4)
                
            elif tipe_buruk == "tinggi_lemak":
                # Junk food / Gorengan berlebih
                target_kalori = random.randint(500, 1000)
                prop_lemak = random.uniform(0.45, 0.65) # Lemak mendominasi
                lemak = int((target_kalori * prop_lemak) / 9)
                protein = random.randint(5, 12)
                karbohidrat = int((target_kalori - (protein * 4) - (lemak * 9)) / 4)
                
            elif tipe_buruk == "karbo_kosong":
                # Makanan instan manis/tepung tanpa gizi pembangun
                target_kalori = random.randint(400, 800)
                protein = random.randint(0, 5) # Sangat minim protein
                lemak = random.randint(5, 15)
                karbohidrat = int((target_kalori - (protein * 4) - (lemak * 9)) / 4)
                
            elif tipe_buruk == "kalori_berlebih":
                # Porsi raksasa yang menyebabkan obesitas (mengecoh model kalori mentah)
                target_kalori = random.randint(1200, 1800)
                protein = random.randint(20, 50) 
                lemak = random.randint(50, 90)   
                karbohidrat = int((target_kalori - (protein * 4) - (lemak * 9)) / 4)
                
            elif tipe_buruk == "kurang_protein":
                # Nasi setumpuk, lauk kuah/sayur tanpa protein hewani/nabati yang cukup
                target_kalori = random.randint(500, 900)
                prop_karbo = random.uniform(0.75, 0.85)
                karbohidrat = int((target_kalori * prop_karbo) / 4)
                protein = random.randint(2, 9)
                lemak = int((target_kalori - (karbohidrat * 4) - (protein * 4)) / 9)

        # Sanitisasi data untuk mencegah nilai negatif akibat pembulatan
        protein = max(0, protein)
        lemak = max(0, lemak)
        karbohidrat = max(0, karbohidrat)

        # Hitung ulang kalori aktual berdasarkan gram makronutrien
        kalori_aktual = (protein * 4) + (lemak * 9) + (karbohidrat * 4)
        
        # Injeksi Noise (+/- 3%) agar model belajar pola, bukan menghafal rumus eksak
        noise = random.uniform(0.97, 1.03)
        kalori_final = int(kalori_aktual * noise)

        data.append({
            "kalori": kalori_final,
            "protein": protein,
            "lemak": lemak,
            "karbohidrat": karbohidrat,
            "label": label
        })
        
    return data

# Menggunakan 10.000 data memastikan model ansambel konvergen dengan baik
jumlah_data = 10000
dataset = generate_realistic_dataset(jumlah_data)

with open('dataset_gizi.json', 'w') as f:
    json.dump(dataset, f, indent=4)

print(f"Berhasil membuat {jumlah_data} baris dataset gizi dengan penyesuaian demografis 3T.")