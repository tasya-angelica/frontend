<?php
require_once __DIR__ . '/includes/api_client.php';
requireLogin();

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$pesan = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggalPost = $_POST['tanggal'] ?? date('Y-m-d');
    $statusList = $_POST['status'] ?? [];
    $keteranganList = $_POST['keterangan'] ?? [];

    $data = [];
    foreach ($statusList as $siswaId => $status) {
        $data[] = [
            'siswa_id'   => $siswaId,
            'status'     => $status,
            'keterangan' => trim($keteranganList[$siswaId] ?? ''),
        ];
    }

    $res = callApi('presensi.php', 'POST', ['tanggal' => $tanggalPost, 'data' => $data], auth: true);

    if ($res['success']) {
        $pesan = $res['message'];
    } else {
        $error = $res['message'];
    }
    $tanggal = $tanggalPost;
}

$res = callApi('presensi.php?tanggal=' . urlencode($tanggal), 'GET', null, auth: true);
$daftarSiswa = $res['success'] ? $res['data']['siswa'] : [];

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-head">
  <div>
    <h1>Presensi Siswa</h1>
    <p class="tanggal-info"><?= htmlspecialchars(tanggalIndonesia($tanggal)) ?></p>
  </div>
  <form method="GET" class="tanggal-picker">
    <label>Tanggal:</label>
    <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" onchange="this.form.submit()">
  </form>
</div>

<?php if ($pesan): ?><p class="alert alert-success"><?= htmlspecialchars($pesan) ?></p><?php endif; ?>
<?php if ($error): ?><p class="alert alert-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

<?php if (empty($daftarSiswa)): ?>
  <p class="empty-state">Belum ada data siswa. Tambahkan dulu di menu <a href="siswa.php">Data Siswa</a>.</p>
<?php else: ?>

<form method="POST" action="index.php">
  <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">

  <table class="table">
    <thead>
      <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Status</th><th>Keterangan</th></tr>
    </thead>
    <tbody>
      <?php foreach ($daftarSiswa as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['nis']) ?></td>
          <td><?= htmlspecialchars($s['nama']) ?></td>
          <td><?= htmlspecialchars($s['kelas']) ?></td>
          <td>
            <select name="status[<?= $s['id'] ?>]" class="status-select status-<?= strtolower($s['status'] ?? 'hadir') ?>">
              <?php foreach (['Hadir', 'Sakit', 'Izin', 'Alpa'] as $opsi): ?>
                <option value="<?= $opsi ?>" <?= ($s['status'] ?? 'Hadir') === $opsi ? 'selected' : '' ?>><?= $opsi ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td>
            <input type="text" name="keterangan[<?= $s['id'] ?>]" value="<?= htmlspecialchars($s['keterangan'] ?? '') ?>" placeholder="opsional">
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <button type="submit" class="btn-primary">💾 Simpan Presensi</button>
</form>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
