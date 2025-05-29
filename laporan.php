<?php
require_once 'auth.php';
require_once 'config.php';
include 'header.php';
$start = $_GET['start'] ?? date('Y-m-01');
$end = $_GET['end'] ?? date('Y-m-d');

$stmt = $conn->prepare("
  SELECT t.*, p.nama 
  FROM transaksi t 
  JOIN pelanggan p ON t.pelanggan_id = p.id 
  WHERE tanggal BETWEEN ? AND ? 
  ORDER BY t.tanggal ASC
");
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$result = $stmt->get_result();

$total_semua = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Laporan Transaksi Laundry</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>
<body>
<div class="container mt-4">
  <h2>Laporan Transaksi Laundry</h2>

  <form method="get" class="row g-3 no-print mb-4">
    <div class="col-md-4">
      <label>Dari Tanggal</label>
      <input type="date" name="start" value="<?= $start ?>" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label>Sampai Tanggal</label>
      <input type="date" name="end" value="<?= $end ?>" class="form-control" required>
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button class="btn btn-primary me-2">Tampilkan</button>
      <button onclick="window.print()" type="button" class="btn btn-success">Cetak</button>
    </div>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nama Pelanggan</th>
        <th>Layanan</th>
        <th>Berat</th>
        <th>Harga/kg</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): $no = 1; ?>
        <?php while ($row = $result->fetch_assoc()): 
          $total_semua += $row['total']; ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['tanggal'] ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= $row['layanan'] ?></td>
            <td><?= $row['berat'] ?> kg</td>
            <td>Rp<?= number_format($row['harga_per_kg']) ?></td>
            <td>Rp<?= number_format($row['total']) ?></td>
          </tr>
        <?php endwhile ?>
        <tr class="fw-bold">
          <td colspan="6" class="text-end">Total Keseluruhan</td>
          <td>Rp<?= number_format($total_semua) ?></td>
        </tr>
      <?php else: ?>
        <tr><td colspan="7" class="text-center">Tidak ada transaksi dalam rentang tanggal ini.</td></tr>
      <?php endif ?>
    </tbody>
  </table>
</div>
</body>
</html>
