<?php
require_once __DIR__ . '/includes/api_client.php';

$error = '';

if (isset($_GET['expired'])) {
    $error = 'Sesi berakhir, silakan login kembali.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Frontend TIDAK cek password sendiri, murni delegasikan ke backend API
    $res = callApi('login.php', 'POST', ['username' => $username, 'password' => $password]);

    if ($res['success']) {
        $_SESSION['token'] = $res['data']['token'];
        $_SESSION['username'] = $res['data']['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = $res['message'] ?: 'Login gagal.';
    }
}

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$infoServer = infoServerFrontend();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Presensi Siswa</title>
<link rel="stylesheet" href="css/style.css">
<script>
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

<button type="button" id="toggleTema" class="btn-tema btn-tema-login" title="Ganti mode gelap/terang">🌓</button>

<div class="auth-wrap">
  <div class="auth-card">
    <h1>📋 Presensi Siswa</h1>
    <p class="subtitle">Masuk untuk mencatat kehadiran siswa hari ini</p>

    <?php if ($error): ?>
      <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <label>Username</label>
      <input type="text" name="username" placeholder="guru1 / guru2 / guru3" required autofocus>

      <label>Password</label>
      <input type="password" name="password" placeholder="123" required>

      <button type="submit" class="btn-primary">Masuk</button>
    </form>

    <p class="hint">Akun contoh: <code>guru1</code>, <code>guru2</code>, <code>guru3</code> — password semua: <code>123</code></p>
  </div>
</div>

<script>
  var tombolTema = document.getElementById('toggleTema');
  if (tombolTema) {
    tombolTema.addEventListener('click', function () {
      var root = document.documentElement;
      var temaSekarang = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
      var temaBaru = temaSekarang === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', temaBaru);
      localStorage.setItem('tema', temaBaru);
    });
  }
</script>
</body>
</html>
