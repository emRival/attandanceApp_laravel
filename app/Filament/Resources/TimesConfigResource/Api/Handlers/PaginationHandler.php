<?php

namespace App\Filament\Resources\TimesConfigResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\TimesConfigResource;

class PaginationHandler extends Handlers
{
    public static string | null $uri = '/';
    public static string | null $resource = TimesConfigResource::class;

    public function handler()
    {
        // Ambil data tanpa kolom `created_at` dan `updated_at`
        $data = static::getEloquentQuery()->select('id', 'name', 'start', 'end')->get();

        // Format jam agar hanya menampilkan jam dan menit (HH:MM)
        $data = $data->map(function ($item) {
            $item->start = \Carbon\Carbon::parse($item->start)->format('H:i'); // Mengubah format waktu
            $item->end = \Carbon\Carbon::parse($item->end)->format('H:i'); // Mengubah format waktu
            return $item;
        });

        // Transformasi data sesuai dengan API Transformer
        $transformedData = static::getApiTransformer()::collection($data);

        // Kembalikan data dalam format JSON dengan struktur yang diinginkan
        return response()->json([
            'time_segments' => $transformedData
        ]);
    }
}