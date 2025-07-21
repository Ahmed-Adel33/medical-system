<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

include 'config/db.php';

$message = '';

// Get patients for dropdown
$patients = $conn->query("SELECT id, name FROM patients");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['report'])) {
  $patient_id = $_POST['patient_id'];
  $file_name = $_FILES['report']['name'];
  $tmp_name = $_FILES['report']['tmp_name'];

  $upload_dir = 'uploads/';
  $file_path = $upload_dir . basename($file_name);

  if (move_uploaded_file($tmp_name, $file_path)) {
    $stmt = $conn->prepare("INSERT INTO reports (patient_id, file_path) VALUES (?, ?)");
    $stmt->bind_param("is", $patient_id, $file_path);
    if ($stmt->execute()) {
      $message = "Report uploaded successfully.";
    } else {
      $message = "Database error.";
    }
  } else {
    $message = "Failed to upload file.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Medical Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
  <div class="container bg-white p-4 rounded shadow" style="max-width:600px;">
    <h3 class="mb-4 text-center">Upload Medical Report</h3>

    <?php if ($message): ?>
      <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="patient_id" class="form-label">Select Patient:</label>
        <select class="form-select" name="patient_id" required>
          <option value="">-- Choose --</option>
          <?php while ($row = $patients->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="report" class="form-label">Report File (PDF):</label>
        <input type="file" name="report" class="form-control" accept="application/pdf" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Upload Report</button>
    </form>
  </div>
</body>
</html>
