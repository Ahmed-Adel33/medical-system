<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$result = $conn->query("SELECT * FROM patients");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>قائمة المرضى</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-image: url('images/clinic-bg.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      backdrop-filter: blur(5px);
    }
    .table {
      background-color: #fff;
    }
    .header-title {
      font-size: 28px;
      font-weight: bold;
      margin: 30px 0;
      color: #fff;
    }
  </style>
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
<div class="container py-5">
  <div class="text-center header-title">قائمة المرضى</div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
      <thead class="table-dark">
        <tr>
          <th>الرقم</th>
          <th>الاسم</th>
          <th>رقم الهاتف</th>
          <th>العنوان</th>
          <th>تاريخ الميلاد</th>
          <th>تاريخ الإضافة</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td><?= htmlspecialchars($row['dob']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-outline-light">⬅ رجوع للوحة التحكم</a>
  </div>
</div>
</body>
</html>
