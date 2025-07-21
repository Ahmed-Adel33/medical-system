<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

include 'config/db.php';

// استعلام لجلب الفواتير مع اسم المريض
$sql = "SELECT invoices.*, patients.name AS patient_name 
        FROM invoices 
        JOIN patients ON invoices.patient_id = patients.id 
        ORDER BY invoice_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoices List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
  <div class="container bg-white p-4 rounded shadow">
    <h2 class="mb-4 text-center">Invoices List</h2>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Patient Name</th>
          <th>Service</th>
          <th>Amount</th>
          <th>Invoice Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['patient_name']) ?></td>
              <td><?= htmlspecialchars($row['service']) ?></td>
              <td><?= $row['amount'] ?> EGP</td>
              <td><?= $row['invoice_date'] ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">No invoices found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
