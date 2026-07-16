<?php
require_once __DIR__ . '/includes/api_client.php';
requireLogin();

$pesan = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'tambah') {
    $res = callApi('siswa.php', 'POST', [
        'nis'   => trim($_POST['nis'] ?? ''),
        'nama'  => trim($_POST['nama'] ?? ''),
        'kelas' => trim($_POST['kelas'] ?? ''),
    ], auth: true);

    if ($res['success']) {
        $pesan = $res['message'];
    } else {
        $error = $res['message'];
    }
}

if (isset($_GET['hapus'])) {
    callApi('siswa.php?id=' . urlencode($_GET['hapus']), 'DELETE', null, auth: true);
    header('Location: siswa.php');
    exit;
}

$res = callApi('siswa.php', 'GET', null, auth: true);
$daftarSiswa = $res['success'] ? $res['data'] : [];

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-head"><h1>Data Siswa</h1></div>

<?php if ($pesan): ?><p class="alert alert-success"><?= htmlspecialchars($pesan) ?></p><?php endif; ?>
<?php if ($error): ?><p class="alert alert-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

<div class="content-grid">
  <div class="card">
    <h2>Tambah Siswa</h2>
    <form method="POST" action="siswa.php">
      <input type="hidden" name="aksi" value="tambah">
      <label>NIS</label>
      <input type="text" name="nis" required>
      <label>Nama</label>
      <input type="text" name="nama" required>
      <label>Kelas</label>
      <input type="text" name="kelas" placeholder="XII RPL 1" required>
      <button type="submit" class="btn-primary">Tambah</button>
    </form>
  </div>

  <div class="card">
    <h2>Daftar Siswa</h2>
    <table class="table">
      <thead><tr><th>NIS</th><th>Nama</th><th>Kelas</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($daftarSiswa as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['nis']) ?></td>
            <td><?= htmlspecialchars($s['nama']) ?></td>
            <td><?= htmlspecialchars($s['kelas']) ?></td>
            <td>
              <a href="siswa.php?hapus=<?= $s['id'] ?>"
                 onclick="return confirm('Hapus siswa ini beserta riwayat presensinya?')"
                 class="link-hapus">Hapus</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
