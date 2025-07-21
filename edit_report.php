<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

include 'config/db.php';

if (!isset($_GET['id'])) {
  header("Location: final_reports.php");
  exit();
}

$report_id = intval($_GET['id']);

// جلب بيانات التقرير مع بيانات المريض
$stmt = $conn->prepare("SELECT rp.id AS report_id, rp.file_path, r.patient_name, r.dob, r.id AS referral_id 
                        FROM reports rp 
                        JOIN referrals r ON rp.referral_id = r.id 
                        WHERE rp.id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
  header("Location: final_reports.php");
  exit();
}

$report = $result->fetch_assoc();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // في حالة رفع ملف جديد
  if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] == 0) {
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $file = $_FILES['report_file'];
    $filename = basename($file['name']);
    $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if ($filetype !== 'pdf') {
      $error = "الملف يجب أن يكون PDF فقط.";
    } else {
      $target_file = $upload_dir . time() . "_" . $filename;
      if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // حذف الملف القديم من السيرفر
        if (file_exists($report['file_path'])) {
          unlink($report['file_path']);
        }

        // تحديث المسار في قاعدة البيانات
        $stmt_update = $conn->prepare("UPDATE reports SET file_path = ?, uploaded_at = NOW() WHERE id = ?");
        $stmt_update->bind_param("si", $target_file, $report_id);
        $stmt_update->execute();

        header("Location: final_reports.php");
        exit();
      } else {
        $error = "فشل رفع الملف.";
      }
    }
  } else {
    $error = "يرجى اختيار ملف PDF للرفع.";
  }
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title>تعديل التقرير</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #f8f9fa;
      padding: 30px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-primary {
      border-radius: 10px;
    }
  </style>
</head>
<body>

<div class="container">
  <h3 class="mb-4 text-center">تعديل تقرير المريض</h3>

  <p><strong>اسم المريض:</strong> <?= htmlspecialchars($report['patient_name']) ?></p>
  <p><strong>تاريخ الميلاد:</strong> <?= htmlspecialchars($report['dob']) ?></p>
  <p><strong>التقرير الحالي:</strong> <a href="<?= htmlspecialchars($report['file_path']) ?>" target="_blank">عرض / تحميل</a></p>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="report_file" class="form-label">رفع تقرير PDF جديد</label>
      <input type="file" name="report_file" id="report_file" class="form-control" accept="application/pdf" required />
    </div>
    <button type="submit" class="btn btn-primary w-100">تحديث التقرير</button>
    <a href="final_reports.php" class="btn btn-secondary w-100 mt-2">عودة للتقارير</a>
  </form>
</div>

</body>
</html>
