<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

include 'config/db.php';

// عند الإرسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $patient_id = $_POST['patient_id'];
  $service = $_POST['service'];
  $amount = $_POST['amount'];
  $notes = $_POST['notes'];

  $stmt = $conn->prepare("INSERT INTO invoices (patient_id, service, amount, notes) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isds", $patient_id, $service, $amount, $notes);
  $stmt->execute();

  header("Location: dashboard.php");
  exit();
}

// جلب قائمة المرضى
$patients = $conn->query("SELECT id, name FROM patients");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>Add New Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
</head>
<body>

<div class="form-container">
  <h3 class="mb-4 text-center">🧾 Add New Invoice</h3>
  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label">Patient</label>
      <select name="patient_id" class="form-control" required>
        <option value="">-- Select Patient --</option>
        <?php while ($row = $patients->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Service</label>
      <input type="text" name="service" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Amount</label>
      <input type="number" step="0.01" name="amount" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Notes</label>
      <textarea name="notes" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary w-100">💾 Save Invoice</button>
  </form>
</div>

</body>
</html>

