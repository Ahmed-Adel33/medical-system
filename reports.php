<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

// Fetch reports with patient data
$sql = "SELECT r.id, r.file_path, r.uploaded_at, p.name AS patient_name
        FROM reports r
        JOIN patients p ON r.patient_id = p.id
        ORDER BY r.uploaded_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <title>Medical Reports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f4f8;
      padding: 30px;
    }
    .table-container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="table-container">
  <h2 class="mb-4 text-center">🩺 Uploaded Medical Reports</h2>
  <table class="table table-bordered table-striped text-center">
    <thead class="table-primary">
      <tr>
        <th>#</th>
        <th>Patient Name</th>
        <th>Uploaded At</th>
        <th>View Report</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['patient_name']) ?></td>
            <td><?= $row['uploaded_at'] ?></td>
            <td><a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="btn btn-success btn-sm">View / Download</a></td>
            <td>
              <form method="POST" action="delete_report.php" onsubmit="return confirm('Are you sure you want to delete this report?');">
                <input type="hidden" name="report_id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center text-muted">No reports found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>
