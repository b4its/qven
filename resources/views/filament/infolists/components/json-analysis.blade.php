<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @php
        $state = $getState();
        $data = [];

        // Memastikan data aman untuk di-decode, entah dia string JSON atau sudah berupa array (karena Model casting)
        if (is_string($state)) {
            $data = json_decode($state, true) ?? [];
        } elseif (is_array($state)) {
            $data = $state;
        }
    @endphp

    @if(!empty($data))
        <div class="mt-2 space-y-3">
            {{-- Loop melalui array/json --}}
            @foreach($data as $key => $value)
                <div class="p-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-white/10 rounded-xl">
                    <div class="flex items-start">
                        <div class="w-1/3">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 capitalize">
                                {{ str_replace('_', ' ', $key) }}
                            </span>
                        </div>
                        <div class="w-2/3">
                            @if(is_array($value))
                                {{-- Jika value di dalam JSON adalah array bersarang --}}
                                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                    @foreach($value as $subItem)
                                        <li>{{ $subItem }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{-- Jika value adalah string/number biasa --}}
                                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                    {{ $value }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-sm italic text-gray-500 dark:text-gray-400 mt-2">
            Belum ada data analisis atau format JSON tidak valid.
        </div>
    @endif
</x-dynamic-component>