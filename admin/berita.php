<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: ../login.php");
  exit;
}

include '../config/database.php';

// Ambil semua berita
$query = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Berita</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

  <!-- Header -->
  <div class="bg-blue-700 text-white p-4 flex justify-between items-center">
    <h1 class="font-bold">Kelola Berita</h1>
    <a href="logout.php" class="text-sm hover:underline">Logout</a>
  </div>

  <!-- Tombol Kembali -->
  <div class="p-4">
    <a href="index.php"
       class="inline-block bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition">
      ← Kembali ke Dashboard
    </a>

    <a href="berita-tambah.php"
       class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition ml-2">
      + Tambah Berita
    </a>
  </div>

  <!-- Tabel Berita -->
  <div class="p-4">
    <table class="w-full bg-white rounded shadow overflow-hidden">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2 text-left">No</th>
          <th class="px-4 py-2 text-left">Judul</th>
          <th class="px-4 py-2 text-left">Tanggal</th>
          <th class="px-4 py-2 text-left">Foto</th>
          <th class="px-4 py-2 text-left">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no=1; while($row = mysqli_fetch_assoc($query)) : ?>
        <tr class="border-t">
          <td class="px-4 py-2"><?= $no++; ?></td>
          <td class="px-4 py-2"><?= $row['judul']; ?></td>
          <td class="px-4 py-2"><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
          <td class="px-4 py-2">
            <?php if($row['foto']): ?>
              <img src="../uploads/berita/<?= $row['foto']; ?>" alt="Foto" class="w-20 h-16 object-cover rounded">
            <?php else: ?>
              -
            <?php endif; ?>
          </td>
          <td class="px-4 py-2">
            <a href="berita-edit.php?id=<?= $row['id']; ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
            <a href="berita-hapus.php?id=<?= $row['id']; ?>" class="text-red-600 hover:underline"
               onclick="return confirm('Yakin ingin menghapus berita ini?');">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
