<?php
session_start();
include 'config/db.php';

// Fetch referrals data (linked patients)
$referrals = $conn->query("SELECT id, patient_name FROM referrals");

// Handle report upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $referral_id = $_POST['referral_id'];
  $upload_dir = 'uploads/';
  if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

  $file = $_FILES['report_file'];
  $filename = basename($file['name']);
  $target_file = $upload_dir . time() . "_" . $filename;
  $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  if ($filetype !== 'pdf') {
    die('❌ Only PDF files are allowed.');
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
  <title>Upload Medical Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #f8f9fa;
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
  <h3 class="mb-4 text-center">📄 Upload New Report</h3>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Select Patient Name</label>
      <select name="referral_id" class="form-control" required>
        <option value="">-- Select Patient --</option>
        <?php while ($row = $referrals->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['patient_name']) ?> (ID: <?= $row['id'] ?>)</option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Report File (PDF only)</label>
      <input type="file" name="report_file" class="form-control" accept="application/pdf" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Upload Report</button>
  </form>
</div>

</body>
</html>
