<style>
    /* Mode Terang: Logo normal (hitam) */
    .brand-logo {
        filter: none !important;
    }

    /* Mode Gelap: Logo dibalik jadi putih */
    .dark .brand-logo {
        filter: brightness(0) invert(1) !important;
    }
</style>

<div style="display: flex; align-items: center; gap: 8px;">
    <span>
        <img src="{{ asset('assets/logo/Logo-mono.png') }}"  
         alt="Logo" 
         class="brand-logo"
         style="height: 32px; width: auto; object-fit: contain;">
    </span>
    
    <span style="font-size: 1.25rem; font-weight: 700; letter-spacing: -0.025em; color: inherit;">
        {{ $brandNames }} {{ $instansiName }}
    </span>
</div>