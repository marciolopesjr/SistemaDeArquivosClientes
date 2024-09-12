<?php
include 'auth.php';

function deleteFile($filePath) {
    if (file_exists($filePath)) {
        unlink($filePath);
        echo "Arquivo excluído.";
    } else {
        echo "Arquivo não encontrado.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Diretórios base
    $imageDir = "uploads/images/";
    $videoDir = "uploads/videos/";
    $otherDir = "uploads/others/";

    $fileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));

    // Define o diretório com base no tipo de arquivo
    switch ($fileType) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            $targetDir = $imageDir;
            break;
        case 'mp4':
        case 'avi':
            $targetDir = $videoDir;
            break;
        default:
            $targetDir = $otherDir;
            break;
    }

    // Cria o diretório se não existir
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;

    // Verifica se o arquivo já existe
    if (file_exists($targetFile)) {
        echo "Desculpe, o arquivo já existe.";
        $uploadOk = 0;
    }

    // Verifica o tamanho do arquivo (opcional)
    if ($_FILES["file"]["size"] > 5000000) { // 5MB
        echo "Desculpe, o arquivo é muito grande.";
        $uploadOk = 0;
    }

    // Permitir certos formatos de arquivo
    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" 
    && $fileType != "gif" && $fileType != "mp4" && $fileType != "avi") {
        echo "Desculpe, apenas arquivos JPG, JPEG, PNG, GIF, MP4 & AVI são permitidos.";
        $uploadOk = 0;
    }

    // Verifica se $uploadOk está definido como 0 por um erro
    if ($uploadOk == 0) {
        echo "Desculpe, seu arquivo não foi carregado.";
    // Tenta fazer o upload do arquivo
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            echo "O arquivo ". basename( $_FILES["file"]["name"]). " foi carregado.";
        } else {
            echo "Desculpe, houve um erro ao carregar seu arquivo.";
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete'])) {
    $filePath = $_GET['delete'];
    deleteFile($filePath);
    header("Location: index.php");
    exit;
}
?>
