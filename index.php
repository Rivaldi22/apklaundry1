<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Laundry</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: row;
    }
    .sidebar {
      width: 220px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 1rem;
      background-color: #343a40;
      color: white;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 0.75rem 1.25rem;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #495057;
    }
    .content {
      margin-left: 220px;
      padding: 2rem;
      width: 100%;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <h5 class="text-white text-center mb-4">MENU</h5>
  <a href="index.php" class="active">Dashboard</a>
  <a href="pelanggan.php">Data Pelanggan</a>
  <a href="transaksi.php">Transaksi Laundry</a>
  <a href="laporan.php">Laporan</a>
  <a href="logout.php">Logout</a>
</div>

<main class="content">
  <h2>Selamat Datang di Sistem Laundry</h2>
  <p>Halo, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>! Pilih menu di sidebar untuk mulai.</p>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
