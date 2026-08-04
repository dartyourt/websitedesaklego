<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: ../login.php");
  exit;
}

include '../config/database.php';

// simpan data
if (isset($_POST['simpan'])) {
  $nik    = $_POST['nik'];
  $nama   = $_POST['nama'];
  $alamat = $_POST['alamat'];

  mysqli_query($koneksi, "INSERT INTO penduduk VALUES('', '$nik', '$nama', '$alamat')");
  header("Location: dashboard.php");
}

// hapus data
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($koneksi, "DELETE FROM penduduk WHERE id='$id'");
  header("Location: dashboard.php");
}

// ambil data untuk edit
$edit = false;
if (isset($_GET['edit'])) {
  $edit = true;
  $id = $_GET['edit'];
  $dataEdit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM penduduk WHERE id='$id'"));
}

// update data
if (isset($_POST['ubah'])) {
  $id     = $_POST['id'];
  $nik    = $_POST['nik'];
  $nama   = $_POST['nama'];
  $alamat = $_POST['alamat'];

  mysqli_query($koneksi, "UPDATE penduduk SET nik='$nik', nama='$nama', alamat='$alamat' WHERE id='$id'");
  header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Penduduk</title>
  <script wrc="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

<h1 class="text-xl font-bold mb-4">Pengolahan Data Penduduk</h1>

<!-- FORM -->
<form method="POST" class="bg-white p-4 rounded shadow mb-6">
  <input type="hidden" name="id" value="<?= $edit ? $dataEdit['id'] : '' ?>">

  <input type="text" name="nik" placeholder="NIK" required
    value="<?= $edit ? $dataEdit['nik'] : '' ?>" class="border p-2 w-full mb-2">

  <input type="text" name="nama" placeholder="Nama" required
    value="<?= $edit ? $dataEdit['nama'] : '' ?>" class="border p-2 w-full mb-2">

  <textarea name="alamat" placeholder="Alamat" required
    class="border p-2 w-full mb-2"><?= $edit ? $dataEdit['alamat'] : '' ?></textarea>

  <?php if ($edit): ?>
    <button name="ubah" class="bg-yellow-500 text-white px-4 py-2 rounded">Ubah</button>
    <a href="dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
  <?php else: ?>
    <button name="simpan" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
  <?php endif; ?>
</form>

<!-- TABEL -->
<table class="w-full bg-white rounded shadow">
<tr class="bg-gray-200">
  <th class="p-2">No</th>
  <th>NIK</th>
  <th>Nama</th>
  <th>Alamat</th>
  <th>Aksi</th>
</tr>

<?php
$no = 1;
$data = mysqli_query($koneksi, "SELECT * FROM penduduk");
while ($row = mysqli_fetch_assoc($data)) :
?>
<tr>
  <td class="p-2"><?= $no++ ?></td>
  <td><?= $row['nik'] ?></td>
  <td><?= $row['nama'] ?></td>
  <td><?= $row['alamat'] ?></td>
  <td>
    <a href="?edit=<?= $row['id'] ?>" class="text-blue-600">Ubah</a> |
    <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus data?')" class="text-red-600">Hapus</a>
  </td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
