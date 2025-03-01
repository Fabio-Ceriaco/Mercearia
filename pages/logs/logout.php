<?php
include '../../includes/conexao.php';


session_start();

unset($_SESSION['csrf_token']);
unset($_SESSION['csrf_token_time']);
session_unset();
session_destroy();
header('Location: ../home.php');
$conn = null;
exit();