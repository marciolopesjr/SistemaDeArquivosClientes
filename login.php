<?php
session_start();

// Credenciais de login (troque por suas próprias credenciais)
$validUsername = 'clairtonsessim';
$validPassword = '15111';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        echo "Usuário ou senha inválidos.";
    }
}
?>
