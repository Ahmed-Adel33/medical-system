<?php
session_start();
include 'config/db.php';

// Fetch patients
$result = $conn->query("SELECT * FROM patients ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient List</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: #f9f9f9;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .btn-sm {
      padding: 4px 10px;
    }
  </style>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="p-4">
  <div class="container">
    <h2 class="mb-4">Patient List</h2>

    <table class="table table-bordered table-hover bg-white">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Date of Birth</th>
          <th>Gender</th>
          <th>Phone</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['dob'] ?></td>
            <td><?= $row['gender'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td>
              <a href="referrals.php?patient_id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success">Referrals</a>
              <a href="reports.php?patient_id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Reports</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
