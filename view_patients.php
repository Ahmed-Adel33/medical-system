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
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>Patient List</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
</head>
<body>
<div class="container py-5">
  <div class="text-center header-title">Patient List</div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Date of Birth</th>
          <th>Created At</th>
          <th>Actions</th>
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
            <td>
              <a href="edit_patient.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Edit
              </a>
              <a href="delete_patient.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this patient?')">
                <i class="fas fa-trash-alt"></i> Delete
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-outline-light">⬅ Back to Dashboard</a>
  </div>
</div>
</body>
</html>
