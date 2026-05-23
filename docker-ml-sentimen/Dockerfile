# Menggunakan Python 3.12 Slim
FROM python:3.12-slim

# Menghindari pembuatan file .pyc dan memastikan log tampil realtime
ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1

# Set working directory di dalam container
WORKDIR /app

# Menginstal framework web, CORS, dan WSGI server untuk production
RUN pip install --no-cache-dir flask flask-cors waitress

# Salin seluruh kode aplikasi dari folder ke dalam container
COPY . .

# Expose port untuk Flask/Waitress
EXPOSE 5000

# Perintah default untuk menjalankan aplikasi
CMD ["python", "app.py"]