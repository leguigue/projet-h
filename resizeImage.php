<?php
function resizeImage($sourcePath, $targetPath, $width, $height) {
    list($origWidth, $origHeight, $type) = getimagesize($sourcePath);
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }
    $thumb = imagecreatetruecolor($width, $height);
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $targetPath, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumb, $targetPath, 9);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumb, $targetPath);
            break;
    }
    imagedestroy($source);
    imagedestroy($thumb);
    return true;
}
if (isset($_GET['img']) && !empty($_GET['img'])) {
    $sourcePath = $_GET['img'];
    $targetPath = 'resized_' . basename($sourcePath);   
    if (!file_exists($targetPath)) {
        resizeImage($sourcePath, $targetPath, 300, 300);
    }  
    header('Content-Type: image/jpeg');
    readfile($targetPath);
    exit;
}