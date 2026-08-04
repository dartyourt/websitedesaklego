<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($conn, "DELETE FROM agenda_desa WHERE id = $id");
}

header("Location: agenda.php?status=sukses_hapus");
exit;
