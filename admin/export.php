<?php

$host="localhost";
$user="root";
$pass="";
$db="desa_klego"; // ganti

$date=date("Y-m-d_H-i-s");

$filename="backup_".$db."_".$date.".sql";

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename");

$mysqldump="C:\\xampp\mysql\bin\mysqldump.exe";

// Jika menggunakan XAMPP ubah menjadi:
// C:\\xampp\\mysql\\bin\\mysqldump.exe

$command="\"$mysqldump\" --host=$host --user=$user --password=$pass $db";

passthru($command);

exit;
