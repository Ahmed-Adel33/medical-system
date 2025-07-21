<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$patients = $conn->query("SELECT COUNT(*) as count FROM patients")->fetch_assoc()['count'];
$referrals = $conn->query("SELECT COUNT(*) as count FROM referrals")->fetch_assoc()['count'];
$reports = $conn->query("SELECT COUNT(*) as count FROM reports")->fetch_assoc()['count'];
$invoices = $conn->query("SELECT COUNT(*) as count FROM invoices")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>لوحة التحكم</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-image: url('assets/images/clinic-bg.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      backdrop-filter: blur(4px);
      min-height: 100vh;
    }
    .card {
      border-radius: 20px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      transition: transform 0.3s;
      cursor: pointer;
    }
    .card:hover {
      transform: scale(1.03);
    }
    .dashboard-header {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 35px;
      color: #fff;
    }
    .card h4 {
      font-size: 22px;
    }
    .btn-outline-light {
      border-radius: 25px;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="dashboard-header text-center">لوحة التحكم الرئيسية</div>

  <div class="row text-center mb-4">
    <div class="col-md-3 mb-3">
      <a href="view_patients.php" style="text-decoration:none">
        <div class="card text-white bg-primary p-4">
          <h4><i class="fas fa-user-injured"></i> المرضى</h4>
          <p class="fs-3"><?= $patients ?></p>
        </div>
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="add_referral.php" style="text-decoration:none">
        <div class="card text-white bg-success p-4">
          <h4><i class="fas fa-file-medical"></i> التحويلات</h4>
          <p class="fs-3"><?= $referrals ?></p>
        </div>
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="final_reports.php" style="text-decoration:none">
        <div class="card text-white bg-warning p-4">
          <h4><i class="fas fa-notes-medical"></i> التقارير</h4>
          <p class="fs-3"><?= $reports ?></p>
        </div>
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="add_invoice.php" style="text-decoration:none">
        <div class="card text-white bg-danger p-4">
          <h4><i class="fas fa-file-invoice-dollar"></i> الفواتير</h4>
          <p class="fs-3"><?= $invoices ?></p>
        </div>
      </a>
    </div>
  </div>

  <div class="card p-4 mb-4">
    <h5 class="text-center">الرسم البياني للنشاط</h5>
    <canvas id="activityChart" height="100"></canvas>
  </div>

  <div class="text-center mt-3">
    <a href="add_patient.php" class="btn btn-outline-light me-2">➕ إضافة مريض</a>
    <a href="logout.php" class="btn btn-outline-light">🚪 تسجيل الخروج</a>
  </div>
</div>

<script>
  const ctx = document.getElementById('activityChart').getContext('2d');
  const activityChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['المرضى', 'التحويلات', 'التقارير', 'الفواتير'],
      datasets: [{
        label: 'إحصائيات النظام',
        data: [<?= $patients ?>, <?= $referrals ?>, <?= $reports ?>, <?= $invoices ?>],
        backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545']
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });
</script>

</body>
</html>
