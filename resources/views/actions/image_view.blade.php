<div class="image-view">
    @if (Str::startsWith($image, 'data:image/'))
        {{-- Jika Base64 --}}
        <img src="{{ $image }}" alt="image">
    @elseif (Storage::exists($image))
        {{-- Jika path file di storage --}}
        <img src="{{ Storage::url($image) }}" alt="image">
    @else
        {{-- Jika format tidak dikenali --}}
        <img src="data:image/jpeg;base64,{{ $image }}" alt="image">
    @endif
</div>

<style>
    .image-view img {
        max-width: 100%;
        /* Mencegah overflow horizontal */
        max-height: 400px;
        /* Batasi tinggi agar tidak terlalu besar */
        height: auto;
        /* Biarkan tinggi mengikuti proporsi asli */
        display: block;
        /* Hindari space ekstra pada img */
        margin: auto;
        /* Pusatkan gambar */
        border-radius: 8px;
        /* Opsional: membuat sudut gambar sedikit melengkung */
        object-fit: contain;
        /* Pastikan seluruh gambar terlihat tanpa pemotongan */
    }
</style>
