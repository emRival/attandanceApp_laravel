<div>
    @if ($image)
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
    @else
        <p>No image available</p>
    @endif

    <style>
        .image-view img {
            max-width: 100%;
            max-height: 400px;
            height: auto;
            display: block;
            margin: auto;
            border-radius: 8px;
            object-fit: contain;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</div>
