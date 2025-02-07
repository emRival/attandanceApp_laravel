<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ImagePreview extends Component
{
    public $image = null; // Menyimpan gambar Base64

    public function mount($captured_image)
    {
        $this->image = $captured_image;
    }
    // Mendengarkan event 'imageUpdated' yang dipancarkan dari form
    protected $listeners = ['imageUpdated' => 'updateImage'];

    // Fungsi untuk menangani event dan memperbarui gambar
    public function updateImage($base64Image)
    {
        $this->image = $base64Image; // Update gambar base64
    }

    public function render()
    {
        return view('livewire.image-preview', [
            'image' => $this->image, // Pastikan ini dikirim ke tampilan
        ]);
    }
}