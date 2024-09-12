<?php
require('fpdf.php');

// Função para ler o conteúdo de um arquivo
function readFileContent($filePath) {
    return file_exists($filePath) ? htmlspecialchars(file_get_contents($filePath)) : "Arquivo não encontrado.";
}

class PDF extends FPDF {
    // Cabeçalho
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Documentação do Projeto', 0, 1, 'C');
        $this->Ln(10);
    }

    // Rodapé
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }

    // Adiciona um título de seção
    function SectionTitle($title) {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, $title, 0, 1, 'L');
        $this->Ln(5);
    }

    // Adiciona o código do arquivo
    function FileContent($content) {
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(0, 5, $content);
        $this->Ln(10);
    }
}

// Cria o objeto PDF
$pdf = new PDF();
$pdf->AddPage();

// Lista de arquivos a serem incluídos no PDF
$files = [
    'index.php' => 'Código do index.php',
    'upload.php' => 'Código do upload.php',
    'login.php' => 'Código do login.php',
    'login.html' => 'Código do login.html',
];

// Adiciona o conteúdo de cada arquivo no PDF
foreach ($files as $file => $title) {
    $pdf->SectionTitle($title);
    $content = readFileContent($file);
    $pdf->FileContent($content);
}

// Adiciona informações adicionais
$pdf->SectionTitle('Informações Adicionais');
$additionalInfo = "Estrutura de Pastas:\n\n/seu-projeto/\n    auth.php\n    index.php\n    upload.php\n    login.php\n    login.html\n    /uploads/\n        /images/\n        /videos/\n        /others/\n";
$pdf->FileContent($additionalInfo);

// Saída do documento
$pdf->Output('documentacao_projeto.pdf', 'I');
?>
