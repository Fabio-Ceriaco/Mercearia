<?php
include '../../includes/conexao.php';


session_start();

unset($_SESSION['csrf_token']);
unset($_SESSION['csrf_token_time']);
session_unset();
session_destroy();
echo "<script>console.log(sessionStorage.removeItem('user_id'))</script>";
echo "<script>window.location.href='./index.php';</script>";
$conn = null;
exit();