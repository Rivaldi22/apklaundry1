<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sistem Laundry</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      min-height: 100vh;
      display: flex;
    }
    .sidebar {
      width: 220px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 1rem;
      background-color: #343a40;
    }
    .sidebar a {
      color: white;
      display: block;
      padding: 0.75rem 1.25rem;
      text-decoration: none;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #495057;
    }
    .main-content {
      margin-left: 220px;
      padding: 2rem;
      width: 100%;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <h5 class="text-white text-center">Laundry</h5>
  <a href="index.php">Dashboard</a>
  <a href="pelanggan.php">Data Pelanggan</a>
  <a href="transaksi.php">Transaksi</a>
  <a href="laporan.php">Laporan</a>
  <a href="logout.php">Logout</a>
</div>

<div class="main-content">
