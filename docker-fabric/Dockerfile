# Menggunakan official image dari Bun
FROM oven/bun:1
WORKDIR /app

# Copy semua file ke dalam container
COPY . .

# Expose port API Gateway
EXPOSE 3000

# Jalankan server menggunakan Bun
CMD ["bun", "run", "server.js"]