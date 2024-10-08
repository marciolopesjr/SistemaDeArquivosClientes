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
        .video-control {
            pointer-events: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Galeria</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Página Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="historico.php">Histórico de Ações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mt-5 mb-3">Galeria de Arquivos</h2>
        <!-- Barra de pesquisa -->
        <div class="mb-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar arquivos..." onkeyup="filterFiles()">
        </div>
        <!-- Botão para abrir o modal de upload -->
        <button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload"></i> Upload de Arquivo
        </button>
        <h3>Imagens</h3>
        <div class="row" id="imageGallery">
            <?php
            $imageDir = 'uploads/images/';
            if (is_dir($imageDir)) {
                if ($dh = opendir($imageDir)) {
                    while (($file = readdir($dh)) !== false) {
                        if ($file != '.' && $file != '..') {
                            echo "<div class='col-12 col-md-4 file-item'>
                                <div class='card'>
                                    <img src='$imageDir$file' class='card-img-top' alt='$file' data-bs-toggle='modal' data-bs-target='#fileModal' data-filepath='$imageDir$file'>
                                    <div class='card-body'>
                                        <p class='card-text'>$file</p>
                                    </div>
                                </div>
                            </div>";
                        }
                    }
                    closedir($dh);
                }
            }
            ?>
        </div>
        <h3>Vídeos</h3>
        <div class="row" id="videoGallery">
            <?php
            $videoDir = 'uploads/videos/';
            if (is_dir($videoDir)) {
                if ($dh = opendir($videoDir)) {
                    while (($file = readdir($dh)) !== false) {
                        if ($file != '.' && $file != '..') {
                            $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            echo "<div class='col-12 col-md-6 file-item'>
                                <div class='card'>
                                    <div class='card-img-top' data-filepath='$videoDir$file' data-bs-toggle='modal' data-bs-target='#fileModal'>
                                        <video class='video-control'><source src='$videoDir$file' type='video/$fileType'></video>
                                    </div>
                                    <div class='card-body'>
                                        <p class='card-text'>$file</p>
                                    </div>
                                </div>
                            </div>";
                        }
                    }
                    closedir($dh);
                }
            }
            ?>
        </div>
        <h3>Outros Arquivos</h3>
        <ul class="list-group" id="otherFiles">
            <?php
            $otherDir = 'uploads/others/';
            if (is_dir($otherDir)) {
                if ($dh = opendir($otherDir)) {
                    while (($file = readdir($dh)) !== false) {
                        if ($file != '.' && $file != '..') {
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
    <!-- Modal para Upload de Arquivo -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload de Arquivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fileInput" class="form-label">Escolha um arquivo:</label>
                            <input type="file" class="form-control" name="file" id="fileInput" required onchange="previewFile()">
                        </div>
                        <div id="filePreview" class="text-center">
                            <!-- Preview do arquivo será carregado dinamicamente -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <!-- Script para manipular os modais, filtros e botões -->
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
            modalBody.innerHTML = `<video id="modalVideo" width='100%' controls><source src='${filePath}' type='video/mp4'>Seu navegador não suporta a tag de vídeo.</video>`;
        } else {
            modalBody.innerHTML = `<img src='${filePath}' alt='Arquivo' class='img-fluid'>`;
        }
        downloadLink.href = filePath;
        deleteButton.onclick = function() { deleteFile(filePath); };
        shareButton.onclick = function() { shareFile(filePath); };
    });

    // Adicionar evento para pausar o vídeo quando o modal for fechado
    fileModal.addEventListener('hide.bs.modal', function () {
        var modalVideo = document.getElementById('modalVideo');
        if (modalVideo) {
            modalVideo.pause();
        }
    });

    function deleteFile(path) {
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteModal.show();
        document.getElementById('confirmDeleteButton').onclick = function() {
            window.location.href = 'upload.php?delete=' + encodeURIComponent(path);
        };
    }
    
    function shareFile(path) {
        if (navigator.share) {
            navigator.share({
                title: 'Compartilhamento de Arquivo',
                text: 'Confira este arquivo:',
                url: window.location.href.replace('index.php', '') + path
            }).then(() => {
                console.log('Arquivo compartilhado com sucesso!');
            }).catch((error) => {
                console.error('Erro ao compartilhar o arquivo:', error);
            });
        } else {
            var shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
            shareModal.show();
            document.getElementById('shareLink').value = window.location.href.replace('index.php', '') + path;
        }
    }

    function previewFile() {
        var file = document.getElementById('fileInput').files[0];
        var preview = document.getElementById('filePreview');
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var fileType = file.type;
                if (fileType.startsWith('image/')) {
                    preview.innerHTML = `<h5>${file.name}</h5><img src="${e.target.result}" alt="${file.name}" class="img-fluid">`;
                } else if (fileType.startsWith('video/')) {
                    preview.innerHTML = `<h5>${file.name}</h5><video width="100%" controls><source src="${e.target.result}" type="${fileType}">Seu navegador não suporta a tag de vídeo.</video>`;
                } else {
                    preview.innerHTML = `<h5>${file.name}</h5><p>Pré-visualização não disponível para este tipo de arquivo.</p>`;
                }
            }
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    }

    function filterFiles() {
        var searchInput = document.getElementById('searchInput').value.toLowerCase();
        var imageGallery = document.getElementById('imageGallery').getElementsByClassName('file-item');
        var videoGallery = document.getElementById('videoGallery').getElementsByClassName('file-item');
        var otherFiles = document.getElementById('otherFiles').getElementsByTagName('li');
        filterGallery(imageGallery, searchInput);
        filterGallery(videoGallery, searchInput);
        filterOtherFiles(otherFiles, searchInput);
    }

    function filterGallery(gallery, searchInput) {
        for (var i = 0; i < gallery.length; i++) {
            var cardText = gallery[i].getElementsByClassName('card-text')[0].innerText.toLowerCase();
            if (cardText.includes(searchInput)) {
                gallery[i].style.display = '';
            } else {
                gallery[i].style.display = 'none';
            }
        }
    }

    function filterOtherFiles(files, searchInput) {
        for (var i = 0; i < files.length; i++) {
            var fileText = files[i].innerText.toLowerCase();
            if (fileText.includes(searchInput)) {
                files[i].style.display = '';
            } else {
                files[i].style.display = 'none';
            }
        }
    }
    </script>
    <!-- Modal de confirmação de exclusão -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Você tem certeza que deseja excluir este arquivo?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Excluir</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Compartilhamento -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Compartilhar Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" id="shareLink" readonly>
                        <button class="btn btn-primary" onclick="copyToClipboard()"><i class="fas fa-copy"></i> Copiar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function copyToClipboard() {
        var shareLink = document.getElementById('shareLink');
        shareLink.select();
        document.execCommand('copy');
        var tooltip = new bootstrap.Tooltip(document.querySelector('.btn'));
        tooltip.show();
    }
    </script>
</body>
</html>
