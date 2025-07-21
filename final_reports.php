<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

include 'config/db.php';

// Fetch final report data with patient information
$sql = "SELECT r.id AS referral_id, r.patient_name, r.dob, rp.id AS report_id, rp.file_path, rp.uploaded_at 
        FROM referrals r 
        JOIN reports rp ON r.id = rp.referral_id 
        ORDER BY rp.uploaded_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>Final Reports - Referral System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background-color: #f8f9fa; }
    .container { margin-top: 50px; }
    .card { border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .btn-primary, .btn-warning, .btn-danger { border-radius: 10px; }
    .form-control { border-radius: 10px; }
    .navbar { border-radius: 0 0 15px 15px; }
  </style>
  <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Referral System</a>
    <div class="d-flex">
      <a class="btn btn-light" href="logout.php">Logout</a>
    </div>
  </div>
</nav>
<div class="container">

<div class="card p-4">
  <h3 class="mb-4">Final Reports</h3>
  <table class="table table-bordered table-striped text-center">
    <thead class="table-primary">
      <tr>
        <th>Case ID</th>
        <th>Patient Name</th>
        <th>Date of Birth</th>
        <th>Download Report</th>
        <th>Upload Date</th>
        <th>Edit</th>
        <th>Upload</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['referral_id']) ?></td>
            <td><?= htmlspecialchars($row['patient_name']) ?></td>
            <td><?= htmlspecialchars($row['dob']) ?></td>
            <td><a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="btn btn-sm btn-success">View / Download</a></td>
            <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
            <td><a href="edit_report.php?id=<?= $row['report_id'] ?>" class="btn btn-sm btn-warning">Edit</a></td>
            <td>
              <a href="upload.php?referral_id=<?= $row['referral_id'] ?>" class="btn btn-sm btn-info">Upload</a>
            </td>
            <td>
              <form method="POST" action="delete_report.php" onsubmit="return confirm('Are you sure you want to delete this report?');">
                <input type="hidden" name="report_id" value="<?= $row['report_id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="8" class="text-danger">No reports available currently.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</div>
</body>
</html>
