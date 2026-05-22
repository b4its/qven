<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @php
        $gizi = $getRecord()->kotakMbg->kandunganGizi;
        // Penyiapan data, berikan fallback 0
        $chartData = [
            $gizi->zat_besi ?? 0,
            $gizi->natrium ?? 0,
            $gizi->karbohidrat ?? 0,
            $gizi->protein ?? 0,
            $gizi->serat ?? 0,
            $gizi->kalsium ?? 0,
        ];
    @endphp

    <div 
        x-data="{
            chartInstance: null,
            isDark: false,
            observer: null,

            init() {
                // Tentukan status tema saat pertama kali dirender
                this.isDark = document.documentElement.classList.contains('dark');

                // Siapkan MutationObserver untuk memantau perubahan class 'dark' pada html
                this.observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.attributeName === 'class') {
                            const currentlyDark = document.documentElement.classList.contains('dark');
                            if (this.isDark !== currentlyDark) {
                                this.isDark = currentlyDark;
                                this.updateChartTheme();
                            }
                        }
                    });
                });

                this.observer.observe(document.documentElement, { attributes: true });

                // Muat Chart.js via CDN jika belum ada
                if (typeof Chart === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                    script.onload = () => this.renderChart();
                    document.head.appendChild(script);
                } else {
                    this.renderChart();
                }
            },

            // Membersihkan observer saat elemen dihancurkan
            destroy() {
                if (this.observer) this.observer.disconnect();
                if (this.chartInstance) this.chartInstance.destroy();
            },

            // Fungsi helper untuk mendapatkan warna berdasarkan tema
            getColors() {
                return {
                    grid: this.isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)',
                    angleLines: this.isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)',
                    pointLabels: this.isDark ? '#e5e7eb' : '#1f2937', // Teks label: light gray for dark, dark gray for light
                };
            },

            renderChart() {
                if (this.chartInstance) {
                    this.chartInstance.destroy();
                }

                const ctx = this.$refs.canvas.getContext('2d');
                const colors = this.getColors();
                
                this.chartInstance = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: ['Zat Besi', 'Natrium', 'Karbo', 'Protein', 'Serat', 'Kalsium'],
                        datasets: [{
                            label: 'Kandungan Gizi',
                            data: {{ json_encode($chartData) }},
                            backgroundColor: 'rgba(96, 165, 250, 0.4)', // Warna area biru tetap cocok untuk kedua tema
                            borderColor: 'rgba(59, 130, 246, 1)',      
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: 'rgba(59, 130, 246, 1)',
                            pointHoverBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointHoverBorderColor: '#ffffff',
                            borderWidth: 2,
                            pointBorderWidth: 2,
                            pointRadius: 4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                angleLines: {
                                    display: true,
                                    color: colors.angleLines
                                },
                                grid: {
                                    color: colors.grid,
                                    circular: true 
                                },
                                pointLabels: {
                                    font: {
                                        family: 'inherit',
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    color: colors.pointLabels 
                                },
                                ticks: {
                                    display: false,
                                    maxTicksLimit: 5
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                // Tooltip di set netral (agak gelap) atau bisa dikustomisasi jika mau
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                padding: 12,
                                titleFont: { size: 14 },
                                bodyFont: { size: 13 },
                                displayColors: false
                            }
                        }
                    }
                });
            },

            // Fungsi untuk memperbarui warna saat tema berubah tanpa merender ulang seluruh chart
            updateChartTheme() {
                if (!this.chartInstance) return;

                const colors = this.getColors();

                this.chartInstance.options.scales.r.grid.color = colors.grid;
                this.chartInstance.options.scales.r.angleLines.color = colors.angleLines;
                this.chartInstance.options.scales.r.pointLabels.color = colors.pointLabels;

                this.chartInstance.update();
            }
        }"
        x-on:unmounted="destroy()"
        class="w-full mt-4"
    >
        <div class="relative h-[400px] w-full flex justify-center items-center">
            <canvas x-ref="canvas"></canvas>
        </div>
    </div>
</x-dynamic-component>