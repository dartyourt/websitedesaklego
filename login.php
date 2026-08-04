<?php
// file login.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin | <?= htmlspecialchars($APP_PROFIL['nama_desa']) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-6 rounded-lg shadow w-full max-w-sm">
  <h2 class="text-xl font-bold text-center mb-4">Login Admin Desa</h2>

  <?php if (isset($_GET['error'])): ?>
    <div class="mb-4 p-3 text-sm rounded bg-red-100 text-red-700">
      <?php
        if ($_GET['error'] == 'username') echo "Username tidak ditemukan.";
        elseif ($_GET['error'] == 'password') echo "Password salah.";
        elseif ($_GET['error'] == 'kosong') echo "Username dan Password wajib diisi.";
      ?>
    </div>
  <?php endif; ?>

  <form action="proses-login.php" method="POST" class="space-y-4">

    <div>
      <label class="text-sm font-medium">Username</label>
      <input type="text" name="username" required
        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
    </div>

    <div>
      <label class="text-sm font-medium">Password</label>
      <input type="password" name="password" required
        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
    </div>

    <button type="submit"
      class="w-full bg-blue-700 text-white py-2 rounded hover:bg-blue-800 transition">
      Masuk
    </button>

  </form>
</div>

</body>
</html>
