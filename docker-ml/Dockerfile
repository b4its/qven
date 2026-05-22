# Menggunakan Python 3.12 Slim (Versi stabil dan ringan untuk environment ML di 2026)
FROM python:3.12-slim

# Menghindari pembuatan file .pyc dan memastikan log tampil realtime
ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1

# Install dependensi sistem yang mungkin dibutuhkan oleh library ML (seperti GCC untuk build C-extensions)
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

# Set working directory di dalam container
WORKDIR /app

# Menginstal library ML dan Web Framework yang Anda butuhkan
RUN pip install --no-cache-dir flask scikit-learn xgboost numpy

# Salin seluruh kode aplikasi dari folder docker-ml ke dalam container
COPY . .

# Expose port untuk Flask
EXPOSE 5000

# Perintah default untuk menjalankan Flask (pastikan Anda memiliki file app.py di folder docker-ml)
CMD ["python", "app.py"]