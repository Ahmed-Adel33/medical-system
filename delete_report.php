<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $report_id = intval($_POST['report_id']);

  // جلب مسار الملف أولاً لحذفه من السيرفر
  $stmt = $conn->prepare("SELECT file_path FROM reports WHERE id = ?");
  $stmt->bind_param("i", $report_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $file_path = $row['file_path'];

    // حذف الملف من السيرفر إذا كان موجود
    if (file_exists($file_path)) {
      unlink($file_path);
    }

    // حذف السجل من قاعدة البيانات
    $stmt_delete = $conn->prepare("DELETE FROM reports WHERE id = ?");
    $stmt_delete->bind_param("i", $report_id);
    $stmt_delete->execute();
  }
}

header("Location: final_reports.php");
exit();
