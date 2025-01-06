document.addEventListener("DOMContentLoaded", () => {
  const video = document.getElementById("video");
  const captureButton = document.getElementById("capture");
  const imageForm = document.getElementById("imageForm");
  const imageInput = document.getElementById("imageInput");

  // Memastikan face-api sudah dimuat
  if (typeof faceapi === "undefined") {
    console.error("faceapi is not loaded correctly");
    return;
  }

  // Memuat model face-api
  faceapi.nets.tinyFaceDetector.loadFromUri("/face/models"),
    faceapi.nets.faceLandmark68Net.loadFromUri("/face/models"),
    faceapi.nets.faceRecognitionNet.loadFromUri("/face/models"),
    faceapi.nets.faceExpressionNet.loadFromUri("/face/models").then(startVideo);

  // Fungsi untuk memulai video stream
  function startVideo() {
    navigator.mediaDevices
      .getUserMedia({
        video: {},
      })
      .then((stream) => {
        video.srcObject = stream;
        video.onloadedmetadata = () => {
          video.play();
        };
      })
      .catch((err) => console.error("Error accessing webcam: ", err));
  }

  // Fungsi untuk menggambar kotak biru dan menambahkannya pada canvas
  function drawBox(detections, canvas, context) {
    detections.forEach((detection) => {
      const { x, y, width, height } = detection.detection.box;

      // Menyesuaikan kotak agar lebih tinggi dan lebih lebar
      const adjustedX = x - 20; // Geser kotak ke kiri
      const adjustedY = y - 80; // Geser kotak ke atas
      const adjustedWidth = width + 80; // Menambah lebar kotak
      const adjustedHeight = height + 110; // Menambah tinggi kotak

      // Memeriksa apakah kotak biru menyentuh pinggiran video
      const isTooClose =
        adjustedX <= 0 ||
        adjustedY <= 0 ||
        adjustedX + adjustedWidth >= canvas.width ||
        adjustedY + adjustedHeight >= canvas.height;

      // Jika terlalu dekat, ganti warna garis menjadi merah dan tampilkan pesan
      if (isTooClose) {
        context.strokeStyle = "red"; // Garis merah jika terlalu dekat
        document.getElementById("message").textContent = "Anda terlalu dekat!";
      } else {
        context.strokeStyle = "#00F"; // Garis biru jika tidak
        document.getElementById("message").textContent = ""; // Kosongkan pesan
      }

      context.lineWidth = 3;
      context.strokeRect(adjustedX, adjustedY, adjustedWidth, adjustedHeight); // Gambar kotak dengan penyesuaian
    });
  }

  // Menangani aliran video untuk mendeteksi wajah dan menggambar kotak biru
  video.addEventListener("play", () => {
    const canvas = faceapi.createCanvasFromMedia(video);
    document.body.append(canvas);

    const scale = 1;
    const displaySize = {
      width: video.width * scale,
      height: video.height * scale,
    };

    canvas.width = displaySize.width;
    canvas.height = displaySize.height;

    faceapi.matchDimensions(canvas, displaySize);

    setInterval(async () => {
      const detections = await faceapi
        .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks()
        .withFaceExpressions();

      const resizedDetections = faceapi.resizeResults(detections, displaySize);

      const context = canvas.getContext("2d");
      context.clearRect(0, 0, canvas.width, canvas.height);

      // Gambar kotak biru dengan deteksi yang sudah disesuaikan
      drawBox(resizedDetections, canvas, context);

      // Gambar ekspresi wajah
      faceapi.draw.drawFaceExpressions(canvas, resizedDetections);
    }, 100);
  });

  // Fungsi untuk menangani tombol capture
  captureButton.addEventListener("click", async () => {
    // Deteksi wajah
    const detections = await faceapi
      .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
      .withFaceLandmarks();

    if (!detections) {
      alert("No face detected. Please ensure your face is within the frame.");
      return;
    }

    const box = detections.detection.box; // Koordinat wajah
    const canvas = document.createElement("canvas");

    // Menyesuaikan ukuran dan posisi crop gambar
    const adjustedX = box.x - 20; // Geser crop ke kiri
    const adjustedY = box.y - 100; // Geser crop ke atas
    const adjustedWidth = box.width + 80; // Perbesar lebar crop
    const adjustedHeight = box.height + 100; // Perbesar tinggi crop

    canvas.width = adjustedWidth; // Ganti dengan lebar yang sudah disesuaikan
    canvas.height = adjustedHeight; // Ganti dengan tinggi yang sudah disesuaikan
    const context = canvas.getContext("2d");

    // Potong gambar dengan ukuran dan posisi baru
    context.drawImage(
      video,
      adjustedX,
      adjustedY,
      adjustedWidth,
      adjustedHeight, // Sumber dari video dengan penyesuaian
      0,
      0,
      adjustedWidth,
      adjustedHeight // Lokasi di canvas untuk hasil crop
    );

    const dataURL = canvas.toDataURL("image/png");

    // Konversi data URL ke Blob
    const imageBlob = dataURLToBlob(dataURL);

    // Simpan Blob ke input file (agar form bisa mengirimkannya)
    const file = new File([imageBlob], "captured_image.png", {
      type: "image/png",
    });
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    imageInput.files = dataTransfer.files;

    // Kirim form (secara otomatis akan mengirim gambar)
    imageForm.submit();
  });

  // Fungsi untuk mengonversi data URL ke Blob
  function dataURLToBlob(dataURL) {
    const byteString = atob(dataURL.split(",")[1]);
    const arrayBuffer = new ArrayBuffer(byteString.length);
    const uintArray = new Uint8Array(arrayBuffer);
    for (let i = 0; i < byteString.length; i++) {
      uintArray[i] = byteString.charCodeAt(i);
    }
    return new Blob([uintArray], {
      type: "image/png",
    });
  }
});
