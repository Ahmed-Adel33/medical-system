<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $patient_name = $_POST['patient_name'];
  $service = $_POST['service'];
  $amount = $_POST['amount'];
  $notes = $_POST['notes'];

  $stmt = $conn->prepare("INSERT INTO invoices (patient_name, service, amount, notes) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssds", $patient_name, $service, $amount, $notes);
  $stmt->execute();

  header("Location: dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة فاتورة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #eef2f7;
      padding: 30px;
    }
    .form-container {
      max-width: 600px;
      margin: auto;
      background-color: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0,0,0,0.1);
    }
  </style>
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<div class="form-container">
  <h3 class="mb-4 text-center">🧾 إضافة فاتورة جديدة</h3>
  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label">اسم المريض</label>
      <input type="text" name="patient_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">الخدمة</label>
      <input type="text" name="service" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">المبلغ</label>
      <input type="number" step="0.01" name="amount" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">ملاحظات</label>
      <textarea name="notes" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary w-100">حفظ الفاتورة</button>
  </form>
</div>

</body>
</html>
