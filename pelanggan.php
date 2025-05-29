<?php
require_once 'auth.php';
require_once 'config.php';
include 'header.php';

// Inisialisasi variabel pesan
$message = "";

// Handle form submit untuk tambah/update pelanggan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $telepon = $_POST['telepon'] ?? '';
    $id = $_POST['id'] ?? '';

    if ($id) {
        // Update pelanggan
        $stmt = $conn->prepare("UPDATE pelanggan SET nama=?, alamat=?, telepon=? WHERE id=?");
        $stmt->bind_param("sssi", $nama, $alamat, $telepon, $id);
        $stmt->execute();
        $message = "Data pelanggan berhasil diperbarui.";
        $stmt->close();
    } else {
        // Insert pelanggan baru
        $stmt = $conn->prepare("INSERT INTO pelanggan (nama, alamat, telepon) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $alamat, $telepon);
        $stmt->execute();
        $message = "Data pelanggan berhasil ditambahkan.";
        $stmt->close();
    }
}

// Handle hapus pelanggan
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM pelanggan WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = "Data pelanggan berhasil dihapus.";
    $stmt->close();
}

// Ambil data pelanggan untuk ditampilkan
$result = $conn->query("SELECT * FROM pelanggan ORDER BY id DESC");

// Jika edit, ambil data pelanggan yang diedit
$editData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM pelanggan WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $editData = $res->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Data Pelanggan Laundry</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">

  <h2>Data Pelanggan Laundry</h2>

  <?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="card mb-4">
    <div class="card-header"><?= $editData ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru' ?></div>
    <div class="card-body">
      <form method="post" action="pelanggan.php">
        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>" />
        <div class="mb-3">
          <label for="nama" class="form-label">Nama</label>
          <input type="text" name="nama" id="nama" class="form-control" required value="<?= htmlspecialchars($editData['nama'] ?? '') ?>" />
        </div>
        <div class="mb-3">
          <label for="alamat" class="form-label">Alamat</label>
          <textarea name="alamat" id="alamat" class="form-control" rows="3"><?= htmlspecialchars($editData['alamat'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label for="telepon" class="form-label">Telepon</label>
          <input type="text" name="telepon" id="telepon" class="form-control" value="<?= htmlspecialchars($editData['telepon'] ?? '') ?>" />
        </div>
        <button type="submit" class="btn btn-primary"><?= $editData ? 'Update' : 'Tambah' ?></button>
        <?php if ($editData): ?>
          <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <h3>Daftar Pelanggan</h3>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>No.</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>Telepon</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php $no = 1; ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['alamat']) ?></td>
            <td><?= htmlspecialchars($row['telepon']) ?></td>
            <td>
              <a href="pelanggan.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="pelanggan.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center">Belum ada data pelanggan.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
