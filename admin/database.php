<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Backup Database</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<!-- Header -->
<div class="bg-blue-700 text-white shadow">
    <div class="max-w-6xl mx-auto flex justify-between items-center p-4">
        <h1 class="text-2xl font-bold">🗄️ Backup Database</h1>

        <a href="index.php"
           class="bg-white text-blue-700 px-4 py-2 rounded hover:bg-gray-100">
            ← Dashboard
        </a>
    </div>
</div>

<div class="max-w-5xl mx-auto mt-10">

    <div class="grid md:grid-cols-2 gap-8">

        <!-- EXPORT -->
        <div class="bg-white rounded-xl shadow-lg p-8">

            <div class="text-center">

                <div class="text-6xl mb-4">
                    💾
                </div>

                <h2 class="text-2xl font-bold text-green-700">
                    Export Database
                </h2>

                <p class="text-gray-600 mt-3">
                    Simpan seluruh database desa menjadi file
                    <b>.sql</b> sebagai cadangan data.
                </p>

                <a href="export.php"
                   class="inline-block mt-8 bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                    ⬇ Download Backup
                </a>

            </div>

        </div>

        <!-- IMPORT -->
        <div class="bg-white rounded-xl shadow-lg p-8">

            <div class="text-center">

                <div class="text-6xl mb-4">
                    📥
                </div>

                <h2 class="text-2xl font-bold text-blue-700">
                    Import Database
                </h2>

                <p class="text-gray-600 mt-3">
                    Restore database menggunakan file
                    <b>.sql</b>.
                </p>

            </div>

            <form action="import.php"
                  method="POST"
                  enctype="multipart/form-data"
                  class="mt-8">

                <input
                    type="file"
                    name="database"
                    accept=".sql"
                    required
                    class="w-full border rounded-lg p-3 mb-5">

                <button
                    onclick="return confirm('Yakin ingin mengimpor database? Data lama akan diganti.')"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">

                    ⬆ Import Database

                </button>

            </form>

        </div>

    </div>

    <!-- Informasi -->
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg mt-10 p-6">

        <h3 class="font-bold text-yellow-700 mb-2">
            ⚠ Perhatian
        </h3>

        <ul class="list-disc ml-6 text-gray-700 space-y-1">
            <li>Lakukan backup database sebelum melakukan import.</li>
            <li>Import akan menimpa data yang ada apabila file SQL berisi perintah DROP atau DELETE.</li>
            <li>Simpan file backup di tempat yang aman.</li>
        </ul>

    </div>

</div>

</body>
</html>
