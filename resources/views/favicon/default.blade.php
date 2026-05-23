<script>
    function colorizeFavicon(src) {
        const img = new Image();
        img.crossOrigin = "anonymous";
        img.onload = function() {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');

            // 1. Gambar logo asli
            ctx.drawImage(img, 0, 0);

            // 2. Ambil data pixel
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;

            // Target warna dari gambar yang diunggah (Biru Oryphem)
            const targetR = 138;  
            const targetG = 233; 
            const targetB = 253; 

            for (let i = 0; i < data.length; i += 4) {
                // Hanya ubah pixel yang tidak transparan
                if (data[i + 3] > 0) { 
                    data[i]     = targetR; // Red
                    data[i + 1] = targetG; // Green
                    data[i + 2] = targetB; // Blue
                }
            }

            ctx.putImageData(imageData, 0, 0);

            // 3. Update favicon link
            let link = document.querySelector("link[rel*='icon']") || document.createElement('link');
            link.type = 'image/png';
            link.rel = 'shortcut icon';
            link.href = canvas.toDataURL("image/png");
            document.getElementsByTagName('head')[0].appendChild(link);
        };
        img.src = src;
    }

    // Eksekusi menggunakan path dari Laravel
    colorizeFavicon("{{ asset('assets/logo/Logo-mono.png') }}");
</script>