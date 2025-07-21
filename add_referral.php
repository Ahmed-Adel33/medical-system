<?php
session_start();
include 'config/db.php';

if (!isset($_GET['patient_id'])) {
  die("Patient ID is missing.");
}

$patient_id = intval($_GET['patient_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $referral_date = $_POST['referral_date'];
  $service_requested = $_POST['service_requested'];
  $notes = $_POST['notes'];

  $stmt = $conn->prepare("INSERT INTO referrals (patient_id, referral_date, service_requested, notes) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $patient_id, $referral_date, $service_requested, $notes);

  if ($stmt->execute()) {
    header("Location: referrals.php?patient_id=$patient_id");
    exit;
  } else {
    $error = "Failed to add referral.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Referral</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  
</head>
<body class="p-4">
  <div class="container">
    <h3 class="mb-4">Add New Referral</h3>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="referral_date" class="form-label">Referral Date</label>
        <input type="date" name="referral_date" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="service_requested" class="form-label">Service Requested</label>
        <input type="text" name="service_requested" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="notes" class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Save Referral</button>
      <a href="referrals.php?patient_id=<?= $patient_id ?>" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
