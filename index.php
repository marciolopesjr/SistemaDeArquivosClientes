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
    <style>
        .file-item {
            margin-bottom: 1rem;
        }
        .file-item img, .file-item video {
            width: 100%;
            cursor: pointer;
        }
        .modal-body img, .modal-body video {
            width: 100%;
        }
    </style>
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
                        echo "<div class='col-12 col-md-4 file-item'>
                                <img src='$imageDir$file' alt='$file' data-bs-toggle='modal' data-bs-target='#fileModal' data-filepath='$imageDir$file'>
                              </div>";
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
                        echo "<div class='col-12 col-md-6 file-item'>
                                <video data-filepath='$videoDir$file' data-bs-toggle='modal' data-bs-target='#fileModal' controls>
                                    <source src='$videoDir$file' type='video/$fileType'>
                                    Seu navegador não suporta a tag de vídeo.
                                </video>
                              </div>";
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
                        echo "<li class='list-group-item'><a href='$otherDir$file' target='_blank'><i class='fas fa-file-alt'></i> $file</a></li>";
                    }
                }
                closedir($dh);
            }
        }
        ?>
        </ul>
    </div>
    
    <!-- Modal para Preview de Arquivo -->
    <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileModalLabel">Preview do Arquivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Conteúdo do arquivo será carregado dinamicamente -->
                </div>
                <div class="modal-footer">
                    <a href="#" id="downloadLink" class="btn btn-success"><i class="fas fa-download"></i> Download</a>
                    <button type="button" class="btn btn-danger" id="deleteButton"><i class="fas fa-trash-alt"></i> Excluir</button>
                    <button type="button" class="btn btn-primary" id="shareButton"><i class="fas fa-share-alt"></i> Compartilhar Link</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <!-- Script para manipular o modal e botões -->
    <script>
        var filePath = '';
        
        var fileModal = document.getElementById('fileModal');
        fileModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            filePath = button.getAttribute('data-filepath');
            
            var modalBody = fileModal.querySelector('.modal-body');
            var downloadLink = document.getElementById('downloadLink');
            var deleteButton = document.getElementById('deleteButton');
            var shareButton = document.getElementById('shareButton');
            
            if (filePath.endsWith('.mp4') || filePath.endsWith('.avi')) {
                modalBody.innerHTML = `<video width='100%' controls><source src='${filePath}' type='video/mp4'>Seu navegador não suporta a tag de vídeo.</video>`;
            } else {
                modalBody.innerHTML = `<img src='${filePath}' alt='Arquivo' class='img-fluid'>`;
            }
            
            downloadLink.href = filePath;
            deleteButton.onclick = function() { deleteFile(filePath); };
            shareButton.onclick = function() { shareFile(filePath); };
        });
        
        function deleteFile(path) {
            if (confirm('Você tem certeza que deseja excluir este arquivo?')) {
                // Implementar a lógica de exclusão aqui
                alert('Arquivo excluído: ' + path);
                location.reload(); // Recarregar a página após exclusão
            }
        }
        
        function shareFile(path) {
            navigator.clipboard.writeText(window.location.href.replace('index.php', '') + path).then(function() {
                alert('Link copiado para a área de transferência');
            });
        }
    </script>
</body>
</html>
