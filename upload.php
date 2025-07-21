<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$referral_id = $_GET['referral_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $referral_id = $_POST['referral_id'];
  $upload_dir = 'uploads/';
  if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

  $file = $_FILES['report_file'];
  $filename = basename($file['name']);
  $target_file = $upload_dir . time() . "_" . $filename;
  $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  if ($filetype !== 'pdf') {
    die('⚠️ Only PDF files are allowed.');
  }

  if (move_uploaded_file($file['tmp_name'], $target_file)) {
    $stmt = $conn->prepare("INSERT INTO reports (referral_id, file_path) VALUES (?, ?)");
    $stmt->bind_param("is", $referral_id, $target_file);
    $stmt->execute();
    header("Location: final_reports.php");
    exit();
  } else {
    echo "❌ Failed to upload the report.";
  }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>Upload Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; margin: 50px; }
  </style>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <h3>Upload Report for Referral ID <?= htmlspecialchars($referral_id) ?></h3>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="referral_id" value="<?= htmlspecialchars($referral_id) ?>">
      <div class="mb-3">
        <label for="report_file" class="form-label">Choose PDF file</label>
        <input type="file" name="report_file" class="form-control" required accept=".pdf">
      </div>
      <button type="submit" class="btn btn-success">Upload</button>
      <a href="final_reports.php" class="btn btn-secondary">Back</a>
    </form>
  </div>
</body>
</html>
