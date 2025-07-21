<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

include 'config/db.php';

// جلب بيانات التقارير النهائية مع بيانات المريض
$sql = "SELECT r.id AS referral_id, r.patient_name, r.dob, rp.id AS report_id, rp.file_path, rp.uploaded_at 
        FROM referrals r 
        JOIN reports rp ON r.id = rp.referral_id 
        ORDER BY rp.uploaded_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>التقارير النهائية - نظام التحويلات</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
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
    <a class="navbar-brand" href="#">نظام التحويلات</a>
    <div class="d-flex">
      <a class="btn btn-light" href="logout.php">تسجيل الخروج</a>
    </div>
  </div>
</nav>
<div class="container">

<div class="card p-4">
  <h3 class="mb-4">التقارير النهائية</h3>
  <table class="table table-bordered table-striped text-center">
    <thead class="table-primary">
      <tr>
        <th>رقم الحالة</th>
        <th>اسم المريض</th>
        <th>تاريخ الميلاد</th>
        <th>تحميل التقرير</th>
        <th>تاريخ الرفع</th>
        <th>تعديل</th>
        <th>رفع</th>
        <th>حذف</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['referral_id']) ?></td>
            <td><?= htmlspecialchars($row['patient_name']) ?></td>
            <td><?= htmlspecialchars($row['dob']) ?></td>
            <td><a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="btn btn-sm btn-success">عرض / تحميل</a></td>
            <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
            <td><a href="edit_report.php?id=<?= $row['report_id'] ?>" class="btn btn-sm btn-warning">تعديل</a></td>
            <td>
  <a href="upload.php?referral_id=<?= $row['referral_id'] ?>" class="btn btn-sm btn-info">رفع تقرير</a>
</td>

            <td>
              <form method="POST" action="delete_report.php" onsubmit="return confirm('هل أنت متأكد من حذف التقرير؟');">
                <input type="hidden" name="report_id" value="<?= $row['report_id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger">حذف</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" class="text-danger">لا توجد تقارير حالياً.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</div>
</body>
</html>
