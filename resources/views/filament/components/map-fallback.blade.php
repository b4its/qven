<div class="w-full" 
     wire:ignore
     x-data="{
        map: null,
        marker: null,
        async loadLeaflet() {
            if (!document.getElementById('leaflet-css')) {
                let css = document.createElement('link');
                css.id = 'leaflet-css';
                css.rel = 'stylesheet';
                css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(css);
            }

            if (typeof L === 'undefined') {
                await new Promise((resolve, reject) => {
                    let script = document.createElement('script');
                    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                    script.onload = resolve;
                    script.onerror = reject;
                    document.head.appendChild(script);
                });
            }
        },
        initMap() {
            setTimeout(() => {
                if (!this.$refs.mapContainer) return;

                let defaultLat = -0.502106;
                let defaultLng = 117.153643;

                let currentVal = $wire.get('data.lokasi');
                if (currentVal && currentVal.includes(',')) {
                    let parts = currentVal.split(',');
                    defaultLat = parseFloat(parts[0]);
                    defaultLng = parseFloat(parts[1]);
                }

                // Jika instance map sudah ada, jangan buat baru, cukup update posisinya saja
                if (this.map) {
                    this.map.setView([defaultLat, defaultLng], 13);
                    this.marker.setLatLng([defaultLat, defaultLng]);
                    setTimeout(() => this.map.invalidateSize(), 100);
                    return;
                }

                this.map = L.map(this.$refs.mapContainer).setView([defaultLat, defaultLng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(this.map);

                this.marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(this.map);

                const updateState = (lat, lng) => {
                    // Gunakan properti ketiga 'false' jika tidak ingin memicu server network request secara agresif
                    $wire.set('{{ $getStatePath() }}', lat.toFixed(6) + ',' + lng.toFixed(6));
                };

                updateState(defaultLat, defaultLng);

                this.map.on('click', (e) => {
                    this.marker.setLatLng(e.latlng);
                    updateState(e.latlng.lat, e.latlng.lng);
                });

                this.marker.on('dragend', () => {
                    let position = this.marker.getLatLng();
                    updateState(position.lat, position.lng);
                });

                setTimeout(() => {
                    this.map.invalidateSize();
                }, 300);
            }, 100);
        }
     }"
     x-init="
        await loadLeaflet();
        
        $el.closest('[wire\\:id]').addEventListener('filament-action-mounted', () => {
            initMap();
        });

        initMap();
     "
>
    <div 
        x-ref="mapContainer"
        style="height: 400px; width: 100%; min-height: 400px; z-index: 1;" 
        class="rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100"
    ></div>
</div>