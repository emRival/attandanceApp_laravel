const faceapi = require("face-api.js");
const path = require("path");
const canvas = require("canvas");

const MODEL_PATH = path.join(__dirname, "../models");

async function generateFaceDescriptor(imagePath) {
  try {
    // Load the models
    await faceapi.nets.ssdMobilenetv1.loadFromDisk(MODEL_PATH);
    await faceapi.nets.faceLandmark68Net.loadFromDisk(MODEL_PATH);
    await faceapi.nets.faceRecognitionNet.loadFromDisk(MODEL_PATH);

    const img = await canvas.loadImage(imagePath);
    const imgCanvas = canvas.createCanvas(img.width, img.height);
    const ctx = imgCanvas.getContext("2d");
    ctx.drawImage(img, 0, 0);

    const netInput = faceapi.tf.browser.fromPixels(imgCanvas);
    const detections = await faceapi
      .detectAllFaces(netInput)
      .withFaceLandmarks()
      .withFaceDescriptors();

    if (detections.length > 0) {
      // Collect all face descriptors
      const allDescriptors = detections.map((d) => d.descriptor);

      // Calculate average descriptor
      const avgDescriptor = calculateAverageDescriptor(allDescriptors);

      // Output the average descriptor as a JSON string
      console.log(JSON.stringify([avgDescriptor])); // Output array of one descriptor
    } else {
      console.log("No faces detected in the image.");
    }
  } catch (error) {
    console.error("Error processing image:", error);
  }
}

// Function to calculate the average of face descriptors
function calculateAverageDescriptor(descriptors) {
  const numDescriptors = descriptors.length;
  const avgDescriptor = new Array(descriptors[0].length).fill(0);

  // Sum all descriptors element-wise
  descriptors.forEach((descriptor) => {
    descriptor.forEach((value, index) => {
      avgDescriptor[index] += value;
    });
  });

  // Divide by the number of descriptors to get the average
  return avgDescriptor.map((value) => value / numDescriptors);
}

// Get the image path from the arguments and process the image
const imagePath = process.argv[2]; // Receive a single image path as argument
generateFaceDescriptor(imagePath);
