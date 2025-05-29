<?php
require_once 'auth.php';
require_once 'config.php';
include 'header.php';

$message = "";

// Tambah atau update transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $pelanggan_id = $_POST['pelanggan_id'];
    $tanggal = $_POST['tanggal'];
    $layanan = $_POST['layanan'];
    $berat = $_POST['berat'];
    $harga_per_kg = $_POST['harga_per_kg'];
    $total = $berat * $harga_per_kg;

    if ($id) {
        $stmt = $conn->prepare("UPDATE transaksi SET pelanggan_id=?, tanggal=?, layanan=?, berat=?, harga_per_kg=?, total=? WHERE id=?");
        $stmt->bind_param("issiddi", $pelanggan_id, $tanggal, $layanan, $berat, $harga_per_kg, $total, $id);
        $stmt->execute();
        $message = "Transaksi berhasil diperbarui.";
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO transaksi (pelanggan_id, tanggal, layanan, berat, harga_per_kg, total) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issidd", $pelanggan_id, $tanggal, $layanan, $berat, $harga_per_kg, $total);
        $stmt->execute();
        $message = "Transaksi berhasil ditambahkan.";
        $stmt->close();
    }
}

// Hapus
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM transaksi WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    $message = "Transaksi dihapus.";
    $stmt->close();
}

// Data transaksi
$sql = "SELECT t.*, p.nama FROM transaksi t JOIN pelanggan p ON t.pelanggan_id = p.id ORDER BY t.id DESC";
$data = $conn->query($sql);

// Data pelanggan untuk dropdown
$pelanggan = $conn->query("SELECT * FROM pelanggan");

// Data untuk edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM transaksi WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $editData = $res->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Transaksi Laundry</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
  <h2>Transaksi Laundry</h2>

  <?php if ($message): ?>
    <div class="alert alert-success"><?= $message ?></div>
  <?php endif; ?>

  <div class="card mb-4">
    <div class="card-header"><?= $editData ? 'Edit Transaksi' : 'Tambah Transaksi' ?></div>
    <div class="card-body">
      <form method="post">
        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

        <div class="mb-3">
          <label>Pelanggan</label>
          <select name="pelanggan_id" class="form-control" required>
            <option value="">-- Pilih --</option>
            <?php while ($p = $pelanggan->fetch_assoc()) : ?>
              <option value="<?= $p['id'] ?>" <?= ($editData['pelanggan_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['nama']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="mb-3">
          <label>Tanggal</label>
          <input type="date" name="tanggal" class="form-control" required value="<?= $editData['tanggal'] ?? date('Y-m-d') ?>">
        </div>

        <div class="mb-3">
          <label>Layanan</label>
          <select name="layanan" class="form-control" required>
            <option <?= ($editData['layanan'] ?? '') == 'Cuci Kering' ? 'selected' : '' ?>>Cuci Kering</option>
            <option <?= ($editData['layanan'] ?? '') == 'Cuci Setrika' ? 'selected' : '' ?>>Cuci Setrika</option>
            <option <?= ($editData['layanan'] ?? '') == 'Setrika Saja' ? 'selected' : '' ?>>Setrika Saja</option>
          </select>
        </div>

        <div class="mb-3">
          <label>Berat (kg)</label>
          <input type="number" step="0.1" name="berat" class="form-control" required value="<?= $editData['berat'] ?? '' ?>">
        </div>

        <div class="mb-3">
          <label>Harga per kg</label>
          <input type="number" name="harga_per_kg" class="form-control" required value="<?= $editData['harga_per_kg'] ?? 7000 ?>">
        </div>

        <button class="btn btn-success"><?= $editData ? 'Update' : 'Simpan' ?></button>
        <?php if ($editData): ?>
          <a href="transaksi.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <h4>Riwayat Transaksi</h4>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Tanggal</th>
        <th>Layanan</th>
        <th>Berat</th>
        <th>Total</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($data->num_rows > 0): $no = 1; ?>
        <?php while($d = $data->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($d['nama']) ?></td>
            <td><?= $d['tanggal'] ?></td>
            <td><?= $d['layanan'] ?></td>
            <td><?= $d['berat'] ?> kg</td>
            <td>Rp<?= number_format($d['total']) ?></td>
            <td>
              <a href="?edit=<?= $d['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="?delete=<?= $d['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
            </td>
          </tr>
        <?php endwhile ?>
      <?php else: ?>
        <tr><td colspan="7" class="text-center">Belum ada data.</td></tr>
      <?php endif ?>
    </tbody>
  </table>
</div>
</body>
</html>
