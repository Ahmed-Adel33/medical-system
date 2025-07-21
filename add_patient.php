<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $dob = $_POST['dob'];
  $phone = $_POST['phone'];

  $stmt = $conn->prepare("INSERT INTO patients (name, dob, phone) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $dob, $phone);
  $stmt->execute();
  header("Location: dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>Add New Patient</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-image: url('images/clinic.jpg');
      background-size: cover;
      background-position: center;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      padding: 30px;
      width: 100%;
      max-width: 500px;
    }

    .form-label {
      font-weight: bold;
    }

    .btn-primary {
      width: 100%;
    }
  </style>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="card">
    <h3 class="text-center mb-4">🩺 Add New Patient</h3>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Patient Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="dob" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">💾 Save Patient</button>
    </form>
  </div>
</body>
</html>
