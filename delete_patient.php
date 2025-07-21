<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $stmt = $conn->prepare("DELETE FROM patients WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: patients_list.php");
exit();
?>
