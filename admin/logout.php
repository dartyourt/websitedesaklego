<?php
session_start();
session_unset();  // hapus semua session
session_destroy(); // hancurkan session
header("Location: ../index.php"); // arahkan ke index.php
exit;
