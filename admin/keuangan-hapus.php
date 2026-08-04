<?php
include '../config/database.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM keuangan WHERE id='$id'");

header("Location: keuangan.php");
