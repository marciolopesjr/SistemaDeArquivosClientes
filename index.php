<?php
include 'auth.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Galeria de Arquivos</title>
</head>
<body>
    <h2>Galeria de Arquivos</h2>
    
    <h3>Imagens</h3>
    <?php
    $imageDir = "uploads/images/";
    if (is_dir($imageDir)) {
        if ($dh = opendir($imageDir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    echo "<img src='$imageDir$file' alt='$file' style='width:200px; height:auto;'><br>";
                }
            }
            closedir($dh);
        }
    }
    ?>
    
    <h3>Vídeos</h3>
    <?php
    $videoDir = "uploads/videos/";
    if (is_dir($videoDir)) {
        if ($dh = opendir($videoDir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    echo "<video width='320' height='240' controls>
                            <source src='$videoDir$file' type='video/$fileType'>
                            Seu navegador não suporta a tag de vídeo.
                          </video><br>";
                }
            }
            closedir($dh);
        }
    }
    ?>
    
    <h3>Outros Arquivos</h3>
    <?php
    $otherDir = "uploads/others/";
    if (is_dir($otherDir)) {
        if ($dh = opendir($otherDir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    echo "<a href='$otherDir$file'>$file</a><br>";
                }
            }
            closedir($dh);
        }
    }
    ?>
</body>
</html>
