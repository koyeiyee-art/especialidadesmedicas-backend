<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';
    $use_insurance = isset($_POST['use_insurance']) && $_POST['use_insurance'] === 'true';
    $is_underage = isset($_POST['is_underage']) && $_POST['is_underage'] === 'true';

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        echo json_encode(['success' => false, 'error' => 'Todos los campos son requeridos']);
        exit;
    }

    // Create uploads directory if it doesn't exist
    $upload_dir = '../uploads/citas/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Function to handle file upload
    function handleFileUpload($file, $upload_dir) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $filename = uniqid() . '_' . basename($file['name']);
            $filepath = $upload_dir . $filename;
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                return 'uploads/citas/' . $filename;
            }
        }
        return null;
    }

    // Function to handle multiple file uploads
    function handleMultipleFileUploads($files, $upload_dir) {
        $uploaded_files = [];
        if (isset($files['name']) && is_array($files['name'])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $filename = uniqid() . '_' . basename($files['name'][$i]);
                    $filepath = $upload_dir . $filename;
                    if (move_uploaded_file($files['tmp_name'][$i], $filepath)) {
                        $uploaded_files[] = 'uploads/citas/' . $filename;
                    }
                }
            }
        }
        return $uploaded_files;
    }

    $birth_certificate = null;
    $cedula_titular = null;
    $cedula_beneficiario = null;
    $referencia_medica = [];
    $indicaciones_medicas = [];

    if ($use_insurance) {
        if ($is_underage && isset($_FILES['birth_certificate'])) {
            $birth_certificate = handleFileUpload($_FILES['birth_certificate'], $upload_dir);
        }

        if (isset($_FILES['cedula_titular'])) {
            $cedula_titular = handleFileUpload($_FILES['cedula_titular'], $upload_dir);
        }

        if (isset($_FILES['cedula_beneficiario'])) {
            $cedula_beneficiario = handleFileUpload($_FILES['cedula_beneficiario'], $upload_dir);
        }

        if (isset($_FILES['referencia_medica'])) {
            $referencia_medica = handleMultipleFileUploads($_FILES['referencia_medica'], $upload_dir);
        }

        if (isset($_FILES['indicaciones_medicas'])) {
            $indicaciones_medicas = handleMultipleFileUploads($_FILES['indicaciones_medicas'], $upload_dir);
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO citas (name, email, phone, message, use_insurance, is_underage, birth_certificate, cedula_titular, cedula_beneficiario, referencia_medica, indicaciones_medicas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $use_insurance_int = $use_insurance ? 1 : 0;
    $is_underage_int = $is_underage ? 1 : 0;
    $referencia_medica_json = json_encode($referencia_medica);
    $indicaciones_medicas_json = json_encode($indicaciones_medicas);

    $stmt->bind_param("ssssiisssss", 
        $name, 
        $email, 
        $phone, 
        $message, 
        $use_insurance_int, 
        $is_underage_int,
        $birth_certificate,
        $cedula_titular,
        $cedula_beneficiario,
        $referencia_medica_json,
        $indicaciones_medicas_json
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cita registrada exitosamente']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al guardar la cita']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>
