<div class="fi-fo-placeholder text-sm">
    @php
        $rawData = $getState();
        $parsedData = [];

        // 1. Penanganan Decode JSON
        if (is_string($rawData)) {
            $decoded = json_decode($rawData, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $rawData = $decoded;
            }
        }

        // 2. Normalisasi Data Gizi (Anti-Fragile Parser Ditingkatkan)
        // Kita perlu menangani 3 variasi struktur JSON Anda
        $giziItemsRaw = [];

        if (is_array($rawData)) {
            // Cek apakah data dibungkus dalam array tunggal (seperti contoh pertama & ketiga)
            if (count($rawData) === 1 && isset($rawData[0]) && is_array($rawData[0])) {
                // Contoh Pertama: [{"makanan_pokok": {...}}, ...] - Nested object
                // Contoh Ketiga: [{"Susu (Pelengkap)": "Tidak Ada", ...}] - Flat key-value
                
                // Cek apakah ini format nested (seperti contoh 1)
                $firstValue = reset($rawData[0]);
                if (is_array($firstValue)) {
                    // Ini adalah format nested seperti contoh 1. Kita ubah menjadi array key-value datar untuk konsistensi.
                    foreach ($rawData[0] as $kategoriRaw => $dataGizi) {
                        $kategori = ucwords(str_replace('_', ' ', $kategoriRaw));
                        $golongan = $dataGizi['status_golongan'] ?? 'Tidak Ada';
                        $deskripsi = $dataGizi['deskripsi'] ?? 'Deskripsi tidak tersedia.';
                        $gizi = $dataGizi['gizi'] ?? null;
                        
                        $parsedData[] = [
                            'kategori'  => $kategori,
                            'golongan'  => $golongan,
                            'deskripsi' => $deskripsi,
                            'gizi'      => $gizi,
                        ];
                    }
                } else {
                    // Ini adalah format flat key-value seperti contoh 3.
                    $giziItemsRaw = $rawData[0];
                }
            } 
            // Cek apakah data datar (seperti contoh kedua): [{"Food_Group": "...", ...}]
            elseif (isset($rawData[0]['Food_Group'])) {
                 foreach ($rawData as $item) {
                    $kategori = $item['Food_Group'];
                    $golongan = $item['Food_Status'] ?? 'Tidak Ada';
                    $deskripsi = $item['Food_Description'] ?? 'Deskripsi tidak tersedia.';
                    
                    $parsedData[] = [
                        'kategori'  => $kategori,
                        'golongan'  => $golongan,
                        'deskripsi' => $deskripsi,
                        'gizi'      => null, // Format 2 tidak memiliki data gizi macro terpisah
                    ];
                }
            }
            // Skenario fallback jika data adalah array datar langsung
            else {
                $giziItemsRaw = $rawData;
            }
        }

        // Lanjutkan memproses format flat key-value (Format 3 dan fallback)
        if (!empty($giziItemsRaw) && empty($parsedData)) {
            foreach ($giziItemsRaw as $kategoriRaw => $value) {
                if (!is_string($value)) continue;

                $kategori = ucwords(str_replace('_', ' ', trim($kategoriRaw)));
                
                $parts = explode('-', $value, 2);
                if (count($parts) < 2) {
                    // Handle cases like "Tidak Ada"
                    $golongan = trim($value); 
                    if (strtolower($golongan) === 'tidak ada' || strtolower($golongan) === 'minimal/sangat rendah (hiasan)') {
                        $golongan = 'Tidak Ada';
                        $deskripsi = 'Kategori ini tidak memiliki kandungan signifikan.';
                    } else {
                        // Jika tidak ada tanda '-', anggap semua adalah deskripsi
                        $golongan = 'Tidak Diketahui';
                        $deskripsi = trim($value);
                    }
                } else {
                    // Handle normal cases like "Sedang/Tinggi - ..."
                    $golongan = trim($parts[0], '[]');
                    $deskripsi = trim($parts[1]);
                }

                $parsedData[] = [
                    'kategori'  => $kategori,
                    'golongan'  => $golongan,
                    'deskripsi' => $deskripsi,
                    'gizi'      => null, // Format 3 tidak memiliki data gizi macro terpisah
                ];
            }
        }

        // 3. Mapping Warna Kategori (Menggunakan palet Filament)
        $getColor = function($text) {
            $text = strtolower((string) $text);
            return match (true) {
                str_contains($text, 'protein') || str_contains($text, 'lauk') => 'bg-emerald-500',
                str_contains($text, 'karbohidrat') || str_contains($text, 'pokok') => 'bg-amber-500',
                str_contains($text, 'sayur') || str_contains($text, 'vitamin & mineral') => 'bg-lime-500',
                str_contains($text, 'buah') || str_contains($text, 'vitamin') => 'bg-rose-500',
                str_contains($text, 'susu') => 'bg-cyan-500',
                str_contains($text, 'lemak') => 'bg-orange-500', 
                default => 'bg-primary-500',
            };
        };

        // 4. Mapping Badge Status
        $getBadgeColor = function($status) {
            $status = strtolower(trim((string) $status));
            if (str_contains($status, 'sangat tinggi')) return 'danger';
            if (str_contains($status, 'tinggi')) return 'warning';
            if (str_contains($status, 'sedang') || str_contains($status, 'cukup')) return 'success';
            if (str_contains($status, 'rendah') || str_contains($status, 'tidak ada') || str_contains($status, 'n/a') || str_contains($status, 'minimal')) return 'gray';
            return 'primary';
        };
    @endphp

    @if(!empty($parsedData))
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($parsedData as $item)
                {{-- Gunakan x-filament::card untuk UI yang lebih native dan konsisten --}}
                <x-filament::card class="relative flex flex-col gap-y-3 dark:bg-gray-900 border-none">
                    
                    {{-- Header Card: Kategori dan Badge Golongan --}}
                    <dt class="flex items-center justify-between gap-x-3">
                        <div class="flex items-center gap-x-2">
                            <div class="relative flex h-3 w-3 shrink-0 items-center justify-center">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $getColor($item['kategori']) }} opacity-40"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $getColor($item['kategori']) }}"></span>
                            </div>
                            
                            {{-- Judul Kategori: Jelas dan Mudah Dibaca --}}
                            <span class="font-bold tracking-tight text-gray-950 dark:text-white uppercase text-xs truncate" title="{{ $item['kategori'] }}">
                                {{ $item['kategori'] }}
                            </span>
                        </div>
                        
                        {{-- Badge Golongan: Diposisikan dengan rapi dan tidak mengambang --}}
                        @if($item['golongan'] && $item['golongan'] !== 'Tidak Diketahui')
                            <x-filament::badge 
                                :color="$getBadgeColor($item['golongan'])" 
                                size="sm"
                                class="shrink-0"
                            >
                                {{ $item['golongan'] }}
                            </x-filament::badge>
                        @endif
                    </dt>

                    {{-- Deskripsi: Terbaca penuh, tidak terpotong, dan memiliki hierarki --}}
                    @if($item['deskripsi'])
                        <dd class="flex-1 flex flex-col space-y-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed text-justify">
                                {{ $item['deskripsi'] }}
                            </p>
                        </dd>
                    @endif

                </x-filament::card>
            @endforeach
        </dl>
    @else
        {{-- State Kosong yang Informatif --}}
        <div class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-gray-300 dark:border-white/10 rounded-2xl text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-transparent text-center">
            <x-heroicon-o-document-text class="w-12 h-12 mb-3 shrink-0 text-gray-400 dark:text-gray-600" />
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Data Gizi Belum Tersedia</h3>
            <p class="text-sm mt-1 max-w-sm">Informasi kandungan gizi untuk kotak MBG ini tidak dapat ditemukan dalam format yang sesuai. Silakan periksa kembali data input.</p>
        </div>
    @endif
</div>