<?php

namespace App\Services;

use App\Models\GalleryRecognation;
use App\Models\StudentsDatabase;
use App\Models\TeachersDatabase;
use App\Models\User;
use App\Models\UserGallerys;

class FaceDescriptorService
{
    public function generateDescriptorForUser(int $userId, String $role)
    {
        set_time_limit(120);
        $column = $role === 'student' ? 'student_id' : 'teacher_id';

        $userImages = GalleryRecognation::where($column, $userId)->get();

        if ($userImages->isEmpty()) {
            throw new \Exception('No images found for this user.');
        }

        $faceDescriptors = [];

        foreach ($userImages as $image) {
            $imagePath = public_path('storage/' . $image->image);
            $nodeScript = base_path('storage/app/scripts/face_descriptor.cjs');

            if (!file_exists($imagePath)) {
                continue;
            }

            $imagePath = escapeshellarg($imagePath);
            $nodeScript = escapeshellarg($nodeScript);

            $command = "node $nodeScript $imagePath";
            $output = [];
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                continue;
            }

            $descriptor = json_decode(implode("\n", $output), true);

            if (is_array($descriptor) && count($descriptor) > 0) {
                $faceDescriptors[] = $descriptor[0];
            }
        }

        if (empty($faceDescriptors)) {
            throw new \Exception('No valid face descriptors found for this user.');
        }

        $averageDescriptor = $this->calculateAverageDescriptor($faceDescriptors);

        $user = $role === 'teacher' ? TeachersDatabase::find($userId) : StudentsDatabase::find($userId);

        // dd($user);
        if (!$user) {
            throw new \Exception('User not found.');
        }

        $user->face = json_encode($averageDescriptor);
        $user->save();

        return $user->name;
    }

    private function calculateAverageDescriptor(array $descriptors)
    {
        $descriptorLength = count($descriptors[0]);
        $averageDescriptor = array_fill(0, $descriptorLength, 0);

        foreach ($descriptors as $descriptor) {
            for ($i = 0; $i < $descriptorLength; $i++) {
                $averageDescriptor[$i] += $descriptor[$i];
            }
        }

        for ($i = 0; $i < $descriptorLength; $i++) {
            $averageDescriptor[$i] /= count($descriptors);
        }

        return $averageDescriptor;
    }
}