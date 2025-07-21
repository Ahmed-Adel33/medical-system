<?php
session_start();
include 'config/db.php';

// Fetch statistics
$patientCount = $conn->query("SELECT COUNT(*) FROM patients")->fetch_row()[0];
$referralCount = $conn->query("SELECT COUNT(*) FROM referrals")->fetch_row()[0];
$reportCount = $conn->query("SELECT COUNT(*) FROM reports")->fetch_row()[0];
$invoiceCount = $conn->query("SELECT COUNT(*) FROM invoices")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: #f0f2f5;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: 0.3s;
    }
    .card:hover {
      transform: scale(1.03);
    }
    .card-title {
      font-size: 22px;
    }
    .stat-number {
      font-size: 36px;
      font-weight: bold;
    }
    .btn-nav {
      margin: 10px 5px;
    }
  </style>
</head>
<body class="p-4">

  <div class="container text-center">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row g-4 mb-5">
      <div class="col-md-3">
        <div class="card text-white bg-primary">
          <div class="card-body">
            <div class="card-title">Total Patients</div>
            <div class="stat-number"><?= $patientCount ?></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-success">
          <div class="card-body">
            <div class="card-title">Total Referrals</div>
            <div class="stat-number"><?= $referralCount ?></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-warning">
          <div class="card-body">
            <div class="card-title">Total Reports</div>
            <div class="stat-number"><?= $reportCount ?></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-danger">
          <div class="card-body">
            <div class="card-title">Total Invoices</div>
            <div class="stat-number"><?= $invoiceCount ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="d-flex justify-content-center flex-wrap">
      <a href="add_patient.php" class="btn btn-outline-primary btn-nav">➕ Add Patient</a>
      <a href="patients.php" class="btn btn-outline-dark btn-nav">👥 Patient List</a>
      <a href="add_report.php" class="btn btn-outline-warning btn-nav">📝 Upload Report</a>
      <a href="referrals.php" class="btn btn-outline-success btn-nav">📄 Referrals</a>
      <a href="invoices.php" class="btn btn-outline-danger btn-nav">💵 Invoices</a>
      <a href="add_invoice.php" class="btn btn-success m-2">➕ Add Invoice</a>

    </div>

  </div>

</body>
</html>
