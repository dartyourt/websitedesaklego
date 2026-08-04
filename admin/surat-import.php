<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['html_content'])) {
    $_SESSION['import_html'] = $_POST['html_content'];
    header("Location: surat-template.php?mode=import_preview");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Import Template Word</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.21/mammoth.browser.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="bg-blue-700 text-white p-4 flex justify-between items-center">
    <h1 class="font-bold text-lg">Import Template dari Word (DOCX)</h1>
    <a href="surat-template.php" class="text-sm hover:underline bg-blue-800 px-3 py-1 rounded">Batal</a>
</div>

<div class="p-6 max-w-3xl mx-auto mt-10">
    <div class="bg-white p-8 rounded shadow text-center">
        <h2 class="text-2xl font-bold mb-4">Upload File Template</h2>
        <p class="text-gray-600 mb-6 text-sm">Pilih file Microsoft Word (.docx) yang berisi kerangka surat. Logo dan Kop Surat resmi akan otomatis ditambahkan oleh sistem nanti, jadi pastikan file Word Anda <strong>tidak memiliki gambar/Kop Surat di dalamnya</strong> untuk hasil terbaik.</p>
        
        <input type="file" id="uploadInput" accept=".docx" class="mb-6 block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-full file:border-0
            file:text-sm file:font-semibold
            file:bg-blue-50 file:text-blue-700
            hover:file:bg-blue-100
        "/>

        <div id="loading" class="hidden text-blue-600 font-bold mb-4">Memproses dokumen...</div>

        <form method="POST" id="importForm" class="hidden">
            <input type="hidden" name="html_content" id="htmlContent">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-bold w-full">Lanjutkan & Edit Template</button>
        </form>

    </div>
</div>

<script>
    document.getElementById('uploadInput').addEventListener('change', function(event) {
        var file = event.target.files[0];
        if (!file) return;

        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('importForm').classList.add('hidden');

        var reader = new FileReader();
        reader.onload = function(loadEvent) {
            var arrayBuffer = loadEvent.target.result;

            mammoth.convertToHtml({arrayBuffer: arrayBuffer})
                .then(function(result) {
                    var html = result.value;
                    document.getElementById('htmlContent').value = html;
                    document.getElementById('loading').classList.add('hidden');
                    document.getElementById('importForm').classList.remove('hidden');
                })
                .catch(function(err) {
                    alert('Gagal membaca file: ' + err.message);
                    document.getElementById('loading').classList.add('hidden');
                });
        };
        reader.readAsArrayBuffer(file);
    });
</script>

</body>
</html>
