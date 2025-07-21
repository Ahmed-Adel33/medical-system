<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// تحقق من وجود ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("رقم المريض غير صالح.");
}

$patient_id = intval($_GET['id']);

// إذا تم إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];

    $stmt = $conn->prepare("UPDATE patients SET name=?, phone=?, address=?, dob=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $phone, $address, $dob, $patient_id);

    if ($stmt->execute()) {
        header("Location: view_patients.php?updated=1");
        exit();
    } else {
        $error = "حدث خطأ أثناء التحديث.";
    }
}

// جلب بيانات المريض
$stmt = $conn->prepare("SELECT * FROM patients WHERE id=?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if (!$patient) {
    die("المريض غير موجود.");
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل بيانات المريض</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f2f2f2;
        }
        .form-container {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container mx-auto col-md-8">
        <div class="header-title text-center">تعديل بيانات المريض</div>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">الاسم:</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($patient['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">رقم الهاتف:</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($patient['phone']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">العنوان:</label>
                <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($patient['address']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">تاريخ الميلاد:</label>
                <input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($patient['dob']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success">حفظ التعديلات</button>
            <a href="view_patients.php" class="btn btn-secondary">رجوع</a>
        </form>
    </div>
</div>
</body>
</html>
