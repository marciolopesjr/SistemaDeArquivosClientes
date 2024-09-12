<?php
include 'auth.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria de Arquivos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Galeria de Arquivos</h2>
        
        <!-- Formulário de Upload -->
        <form action="upload.php" method="post" enctype="multipart/form-data" class="mb-5">
            <div class="mb-3">
                <label for="file" class="form-label">Escolha um arquivo:</label>
                <input type="file" class="form-control" name="file" id="file" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
        </form>
        
        <h3>Imagens</h3>
        <div class="row">
        <?php
        $imageDir = "uploads/images/";
        if (is_dir($imageDir)) {
            if ($dh = opendir($imageDir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        echo "<div class='col-3 mb-4'><img src='$imageDir$file' alt='$file' class='img-thumbnail'></div>";
                    }
                }
                closedir($dh);
            }
        }
        ?>
        </div>
        
        <h3>Vídeos</h3>
        <div class="row">
        <?php
        $videoDir = "uploads/videos/";
        if (is_dir($videoDir)) {
            if ($dh = opendir($videoDir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        echo "<div class='col-6 mb-4'><video width='100%' controls>
                                <source src='$videoDir$file' type='video/$fileType'>
                                Seu navegador não suporta a tag de vídeo.
                              </video></div>";
                    }
                }
                closedir($dh);
            }
        }
        ?>
        </div>
        
        <h3>Outros Arquivos</h3>
        <ul class="list-group">
        <?php
        $otherDir = "uploads/others/";
        if (is_dir($otherDir)) {
            if ($dh = opendir($otherDir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        echo "<li class='list-group-item'><a href='$otherDir$file'><i class='fas fa-file-alt'></i> $file</a></li>";
                    }
                }
                closedir($dh);
            }
        }
        ?>
        </ul>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
