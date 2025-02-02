<?php

namespace App\Http\Controllers;

use App\Models\GalleryRecognation;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class FaceRecognationController extends Controller
{
    public function index($role, $id)
    {
        // Tentukan kolom berdasarkan role
        $column = $role === 'student' ? 'student_id' : 'teacher_id';

        // Ambil data berdasarkan role dan ID
        $models = GalleryRecognation::where($column, $id)->get();

        return view('face.facerecognation', compact('models', 'role', 'id'));
    }

    public function store(Request $request, $role)
    {
        // Validasi input
        $request->validate([
            'image' => 'required|image|max:2048', // Validasi gambar
            'id' => 'required', // Validasi ID
        ]);

        // Tentukan kolom dan folder penyimpanan berdasarkan role
        $column = $role === 'student' ? 'student_id' : 'teacher_id';
        $folder = $role === 'student' ? 'students_face' : 'teachers_face';

        // Ambil file gambar dari request
        $image = $request->file('image');

        // Buat nama file sesuai format yang diinginkan
        $filename = strtolower($role) . 's_' . $request->id . '_' . now()->format('Ymd_His') . '_face.' . $image->getClientOriginalExtension();

        // Simpan gambar ke storage dengan nama file yang sudah dibuat
        $path = $image->storeAs($folder, $filename, 'public');

        // Simpan data ke database
        $model = new GalleryRecognation();
        $model->image = $path;
        $model->{$column} = $request->id; // Dynamic column assignment
        $model->save();

        // Flash message dan redirect
        session()->flash('success', 'Gambar berhasil diupload!');

        return redirect()->back();
    }
}
