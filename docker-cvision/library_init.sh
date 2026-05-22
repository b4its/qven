if command -v nvidia-smi &> /dev/null; then \
    echo "GPU terdeteksi, menginstall versi CUDA..."; \
    pip install torch torchvision torchaudio; \
else \
    echo "GPU tidak ditemukan, menginstall versi CPU..."; \
    pip install torch torchvision torchaudio --index-url https://download.pytorch.org/whl/cpu; \
fi && pip install sentence-transformers Pillow