<?php

$host="localhost";
$user="root";
$pass="";
$db="desa_klego"; // ganti

if(isset($_FILES['database'])){

    $tmp=$_FILES['database']['tmp_name'];

    $mysql="C:\\xampp\\MySQL\\bin\\mysql.exe";

    // Jika XAMPP
    // C:\\xampp\\mysql\\bin\\mysql.exe

    $command="\"$mysql\" --host=$host --user=$user --password=$pass $db < \"$tmp\"";

    exec("cmd /c ".$command,$output,$hasil);

    if($hasil==0){
        echo "<script>
        alert('Import database berhasil');
        location='database.php';
        </script>";
    }else{
        echo "<script>
        alert('Import database gagal');
        history.back();
        </script>";
    }

}
