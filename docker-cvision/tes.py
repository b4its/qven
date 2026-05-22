# import torch
# from sentence_transformers import SentenceTransformer, util
# from PIL import Image

# # 1. Load Model CLIP berbasis ViT
# # Model ini memahami hubungan antar objek dengan sangat baik (Semantic Understanding)
# model = SentenceTransformer('clip-ViT-B-32')

# # 2. Siapkan Gambar
# # Gambar 1: Burger di meja restoran
# # Gambar 2: Burger di tengah hutan dengan angle berbeda
# img_path1 = "images/burger.jpg"
# img_path2 = "images/burger2.jpg"

# image1 = Image.open(img_path1)
# image2 = Image.open(img_path2)

# # 3. Proses Gambar menjadi Embedding (Vektor)
# # ViT akan memecah gambar menjadi patches dan merangkum konteksnya
# embeddings = model.encode([image1, image2])

# # 4. Hitung Kemiripan (Cosine Similarity)
# # Ini mengukur seberapa dekat arah vektor kedua gambar di ruang laten
# cos_sim = util.cos_sim(embeddings[0], embeddings[1])

# print(f"Tingkat Kemiripan Semantik: {cos_sim.item():.4f}")

# # Thresholding sederhana
# if cos_sim.item() > 0.8:
#     print("Konteks gambar sangat mirip (Kemungkinan objek yang sama)")
# elif cos_sim.item() > 0.5:
#     print("Gambar memiliki keterkaitan tema (Konteks serupa)")
# else:
#     print("Gambar tidak memiliki kemiripan konteks")

# high precision
import torch
from sentence_transformers import SentenceTransformer, util
from PIL import Image

# Cek ketersediaan GPU (Sangat disarankan untuk ViT-L-14)
device = "cuda" if torch.cuda.is_available() else "cpu"
print(f"Menggunakan perangkat: {device}")

# 1. Load Model CLIP berbasis ViT-L-14
# Perubahan dari B-32 ke L-14 memberikan sensitivitas detail yang jauh lebih tinggi
model = SentenceTransformer('clip-ViT-L-14', device=device)

# 2. Siapkan Gambar
img_path1 = "images/burger.jpg"
img_path2 = "images/burger2.jpg"

image1 = Image.open(img_path1)
image2 = Image.open(img_path2)

# 3. Proses Gambar menjadi Embedding (Vektor)
# Output embedding L-14 berukuran 768 dimensi (lebih besar dari B-32 yang 512)
embeddings = model.encode([image1, image2])

# 4. Hitung Kemiripan (Cosine Similarity)
cos_sim = util.cos_sim(embeddings[0], embeddings[1])

print(f"Tingkat Kemiripan Semantik (L-14): {cos_sim.item():.4f}")

# Thresholding (Mungkin perlu sedikit penyesuaian karena L-14 lebih diskriminatif)
if cos_sim.item() > 0.85: # L-14 biasanya lebih tegas, threshold bisa dinaikkan sedikit
    print("Konteks sangat identik")
elif cos_sim.item() > 0.6:
    print("Gambar memiliki keterkaitan tema yang kuat")
else:
    print("Gambar tidak memiliki kemiripan konteks")