    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Menangkap notifikasi SUCCESS dari Controller
            @if(session('success'))
                toastr.success("{!! session('success') !!}", "Berhasil");
                window.setTimeout(() => { window.location.href = "{{ route('welcome') }}"; }, 8000);
            @endif

            // 2. Menangkap notifikasi ERROR manual dari Controller
            @if(session('error'))
                toastr.error("{!! session('error') !!}", "Gagal");
            @endif

            // 3. Menangkap ERROR VALIDASI otomatis dari Laravel ($request->validate)
            @if($errors->any())
                @foreach($errors->all() as $error)
                    toastr.error("{{ $error }}", "Validasi Gagal");
                @endforeach
            @endif
        });
</script>