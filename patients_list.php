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
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>قائمة المرضى</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body class="container mt-4">
  <h2 class="mb-4">قائمة المرضى</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>الاسم</th>
        <th>تاريخ الميلاد</th>
        <th>الهاتف</th>
        <th>إجراءات</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['dob']) ?></td>
          <td><?= htmlspecialchars($row['phone']) ?></td>
          <td>
            <a href="edit_patient.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">تعديل</a>
            <a href="delete_patient.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
