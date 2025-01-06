<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Document</title>
    <script src="{{ asset('face/face-api.min.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100vw;
            min-height: 100vh;
            background-color: #f9fafb;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: 'Inter', sans-serif;
        }

        canvas {
            position: absolute;
        }

        video {
            position: relative;
        }
    </style>
</head>

<body>
    <!-- Tombol Back -->
    <div class="w-full flex justify-start p-4">
        <button
            onclick="window.location.href='{{ $role === 'teacher' ? route('filament.admin.resources.teachers-databases.edit', $id) : route('filament.admin.resources.students-databases.edit', $id) }}'"
            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300">
            Back
        </button>
    </div>

    <!-- Alert Success -->
    @if (session()->has('success'))
        <div id="successAlert" class="w-full flex justify-center mt-4">
            <div class="flex items-center gap-3 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md max-w-xl"
                role="alert">
                <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-6V8a1 1 0 10-2 0v4a1 1 0 102 0zm-1 4a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-semibold">
                    {{ session('success') }}
                </span>
            </div>
        </div>
    @endif

    <!-- Pesan umum -->
    <p id="message" class="w-full flex justify-center my-3 text-lg max-w-xl text-center text-red-500"></p>

    <!-- Video preview -->
    <video id="video" width="720" height="560" class="rounded-lg shadow-lg" autoplay muted></video>

    <!-- Form capture -->
    <form id="imageForm" action="{{ route('faces.store', ['role' => $role, 'id' => $id]) }}" method="POST"
        enctype="multipart/form-data" class="w-full max-w-sm flex flex-col items-center mt-4">
        @csrf
        <input type="file" id="imageInput" name="image" hidden />
        <button type="button"
            class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 my-3 rounded-full shadow-md transition-colors duration-300"
            id="capture">
            Capture & Submit
        </button>
    </form>


    <!-- Tombol toggle gambar -->
    <div class="w-full flex justify-center mt-2">
        <button
            class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-6 rounded-full shadow-md transition-colors duration-300"
            onclick="toggleImages()">
            Show/Hide Images
        </button>
    </div>

    <!-- Grid gambar -->
    <div id="imagesContainer"
        class="hidden mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-10 gap-4 max-w-5xl mx-auto px-4">
        @foreach ($models as $model)
            <div class="bg-white p-3 rounded-lg shadow-md flex justify-center">
                <img src="{{ asset('storage/' . $model->image) }}" alt="Image"
                    class="w-20 h-20 object-cover rounded" />
            </div>
        @endforeach
    </div>

    <script>
        // Hilangkan alert sukses setelah 5 detik
        window.addEventListener('DOMContentLoaded', () => {
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.remove();
                }, 1000);
            }
        });

        // Toggle show/hide container gambar
        function toggleImages() {
            const container = document.getElementById('imagesContainer');
            container.classList.toggle('hidden');
        }
    </script>

    <script src="{{ asset('face/face-script.js') }}"></script>
</body>

</html>
