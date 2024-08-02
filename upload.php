<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

try {
    function uploadImage($file) {
        error_log("Uploading file: " . print_r($file, true));
        $allowed = ['jpeg', 'png', 'gif'];
        $filename = $file['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));    
        if (in_array($filetype, $allowed)) {
            $upload_dir = './uploads/';    
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $new_filename = uniqid() . '.' . $filetype;
            $upload_path = $upload_dir . $new_filename;
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                error_log("File uploaded successfully to: " . $upload_path);
                return [
                    'success' => true,
                    'path' => $upload_path,
                    'filename' => $new_filename
                ];
            } else {
                error_log("Failed to move uploaded file.");
                return [
                    'success' => false,
                    'error' => "Erreur lors du déplacement du fichier uploadé."
                ];
            }
        } else {
            error_log("Invalid file type: " . $filetype);
            return [
                'success' => false,
                'error' => "Type de fichier non autorisé. Seuls jpeg, png et gif sont acceptés."
            ];
        }
    }

    if (isset($_FILES['image'])) {
        $result = uploadImage($_FILES['image']);
        error_log("Upload result: " . print_r($result, true));
        echo json_encode($result);
    } else {
        error_log("No file uploaded");
        echo json_encode([
            'success' => false,
            'error' => 'Aucun fichier n\'a été uploadé.'
        ]);
    }
} catch (Throwable $e) {
    error_log("Uncaught exception: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Une erreur inattendue s\'est produite.'
    ]);
}
?>