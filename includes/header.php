<?php $infoServer = infoServerFrontend(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Presensi Siswa</title>
<link rel="stylesheet" href="css/style.css">
<script>
  // Terapkan tema tersimpan sebelum halaman dirender, biar tidak "kedip".
  (function () {
    var tema = localStorage.getItem('tema') || 'light';
    document.documentElement.setAttribute('data-theme', tema);
  })();
</script>
</head>
<body>

<div class="server-info-banner">
  🖥️ Server Frontend menangani request ini &mdash;
  Hostname: <strong><?= htmlspecialchars($infoServer['hostname']) ?></strong>
  &nbsp;|&nbsp;
  IP: <strong><?= htmlspecialchars($infoServer['ip']) ?></strong>
</div>

<?php if (isLoggedIn()): ?>
<header class="navbar">
    <div class="navbar-brand">
        📋 Presensi Siswa
        <span class="navbar-date"><?= htmlspecialchars(tanggalIndonesia()) ?></span>
    </div>
    <nav class="navbar-menu">
        <a href="index.php">Presensi Hari Ini</a>
        <a href="rekap.php">Rekap</a>
        <a href="siswa.php">Data Siswa</a>
    </nav>
    <div class="navbar-user">
        Halo, <strong><?= htmlspecialchars(currentUser()) ?></strong>
        &nbsp;|&nbsp;
        <a href="logout.php">Keluar</a>
        &nbsp;
        <button type="button" id="toggleTema" class="btn-tema" title="Ganti mode gelap/terang">🌓</button>
    </div>
</header>
<?php endif; ?>
<main class="container">
