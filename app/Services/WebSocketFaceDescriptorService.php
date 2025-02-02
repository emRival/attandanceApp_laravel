<?php

namespace App\Services;

use App\Models\GalleryRecognation;
use App\Models\StudentsDatabase;
use App\Models\TeachersDatabase;
use Ratchet\Client\WebSocket;
use Ratchet\Client\Connector;
use React\EventLoop\Factory;
use Exception;

class WebSocketFaceDescriptorService
{
    private string $websocketUrl = "ws://127.0.0.1:8765";

    public function generateDescriptorForUser(int $userId, string $role)
    {
        set_time_limit(120);
        $column = $role === 'student' ? 'student_id' : 'teacher_id';

        $userImages = GalleryRecognation::where($column, $userId)->get();

        if ($userImages->isEmpty()) {
            throw new Exception('No images found for this user.');
        }

        $imageUrls = [];
        foreach ($userImages as $image) {
            $imageUrls[] = asset('storage/' . $image->image); // Convert image path to URL
        }

        $payload = [
            "id" => $userId,
            "position" => $role,
            "images" => $imageUrls
        ];

        $descriptor = $this->sendDataToWebSocket($payload);

        if (empty($descriptor)) {
            throw new Exception('No valid face descriptor received.');
        }

        // Simpan ke database
        $user = $role === 'teacher' ? TeachersDatabase::find($userId) : StudentsDatabase::find($userId);

        if (!$user) {
            throw new Exception('User not found.');
        }

        $user->face = json_encode($descriptor);
        $user->save();

        return $user->name;
    }

    private function sendDataToWebSocket(array $data): ?array
    {
        $loop = Factory::create();
        $connector = new Connector($loop);
        $result = null;

        $connector($this->websocketUrl)->then(
            function (WebSocket $conn) use ($data, &$result, $loop) {
                $conn->send(json_encode($data));

                $conn->on('message', function ($msg) use (&$result, $conn, $loop) {
                    $response = json_decode($msg, true);
                    $result = $response['descriptor'] ?? null;
                    $conn->close();
                    $loop->stop();
                });
            },
            function (Exception $e) use ($loop) {
                echo "Could not connect: {$e->getMessage()}\n";
                $loop->stop();
            }
        );

        $loop->run();
        return $result;
    }
}