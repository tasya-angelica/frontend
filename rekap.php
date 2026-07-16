<?php
require_once __DIR__ . '/includes/api_client.php';
requireLogin();

$dari = $_GET['dari'] ?? date('Y-m-01');
$sampai = $_GET['sampai'] ?? date('Y-m-d');

$res = callApi('rekap.php?dari=' . urlencode($dari) . '&sampai=' . urlencode($sampai), 'GET', null, auth: true);
$rekap = $res['success'] ? $res['data']['rekap'] : [];

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-head">
  <h1>Rekap Presensi</h1>
  <form method="GET" class="tanggal-picker">
    <label>Dari:</label>
    <input type="date" name="dari" value="<?= htmlspecialchars($dari) ?>">
    <label>Sampai:</label>
    <input type="date" name="sampai" value="<?= htmlspecialchars($sampai) ?>">
    <button type="submit" class="btn-secondary btn-small">Tampilkan</button>
  </form>
</div>

<table class="table">
  <thead>
    <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Hadir</th><th>Sakit</th><th>Izin</th><th>Alpa</th></tr>
  </thead>
  <tbody>
    <?php foreach ($rekap as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['nis']) ?></td>
        <td><?= htmlspecialchars($r['nama']) ?></td>
        <td><?= htmlspecialchars($r['kelas']) ?></td>
        <td class="text-center"><?= (int) $r['hadir'] ?></td>
        <td class="text-center"><?= (int) $r['sakit'] ?></td>
        <td class="text-center"><?= (int) $r['izin'] ?></td>
        <td class="text-center"><?= (int) $r['alpa'] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
