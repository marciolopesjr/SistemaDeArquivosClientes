<?php
include 'auth.php';
session_start(); // Iniciar a sessão

function addActionToHistory($action) {
    $username = $_SESSION['username'];
    $timestamp = date('Y-m-d H:i:s');
    $entry = "$timestamp - $username: $action\n";
    file_put_contents('historico.txt', $entry, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files'])) {
    $files = $_FILES['files'];
    $totalFiles = count($files['name']);
    $uploadDirMap = [
        'image' => 'uploads/images/',
        'video' => 'uploads/videos/',
        'default' => 'uploads/others/'
    ];

    for ($i = 0; $i < $totalFiles; $i++) {
        $fileName = basename($files['name'][$i]);
        $fileTmpName = $files['tmp_name'][$i];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Define o diretório com base no tipo de arquivo
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $targetDir = $uploadDirMap['image'];
        } elseif (in_array($fileType, ['mp4', 'avi'])) {
            $targetDir = $uploadDirMap['video'];
        } else {
            $targetDir = $uploadDirMap['default'];
        }

        // Cria o diretório se não existir
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . $fileName;

        // Verifica se o arquivo já existe
        if (file_exists($targetFile)) {
            continue; // Pula este arquivo, pois já existe
        }

        // Faz o upload do arquivo
        if (move_uploaded_file($fileTmpName, $targetFile)) {
            addActionToHistory("Carregou arquivo: $targetFile"); // Registra a ação
        }
    }

    // Redireciona para a página inicial após o upload
    header("Location: index.php");
    exit;
} else {
    echo "Nenhum arquivo foi enviado.";
}
?>
