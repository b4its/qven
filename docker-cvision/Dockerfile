# Image ini sudah berisi Python 3.10/3.11 dan PyTorch + CUDA yang sudah tersetup
FROM pytorch/pytorch:2.2.0-cuda12.1-cudnn8-runtime

ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1
ENV HF_HOME=/app/.cache/huggingface

WORKDIR /app

# Library tambahan yang mungkin dibutuhkan untuk pemrosesan gambar
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Karena Torch sudah ada di image ini, requirements.txt cukup berisi 
# library lain seperti flask, pillow, sentence-transformers, dll.
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

CMD ["python", "app.py"]