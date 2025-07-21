<?php
session_start();
include 'config/db.php';

// Ensure patient_id exists
if (!isset($_GET['patient_id'])) {
  die("Patient ID not found.");
}

$patient_id = intval($_GET['patient_id']);

// Get patient's name
$stmt = $conn->prepare("SELECT name FROM patients WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
if (!$patient) {
  die("Patient not found.");
}

// Get referrals for this patient
$referrals = $conn->query("SELECT * FROM referrals WHERE patient_id = $patient_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Referrals</title>
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
  </style>
</head>
<body class="p-4">
  <div class="container">
    <h3 class="mb-3">Referrals for: <span class="text-primary"><?= htmlspecialchars($patient['name']) ?></span></h3>
<a href="add_referral.php?patient_id=<?= $patient_id ?>" class="btn btn-success mb-3">➕ Add New Referral</a>

    <table class="table table-bordered bg-white">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Referral Date</th>
          <th>Service Requested</th>
          <th>Notes</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($referrals->num_rows > 0): ?>
          <?php while($row = $referrals->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= $row['referral_date'] ?></td>
              <td><?= htmlspecialchars($row['service_requested']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['notes'])) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center text-muted">No referrals found for this patient.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <a href="patients.php" class="btn btn-secondary mt-3">⬅ Back to Patients List</a>
  </div>
</body>
</html>
