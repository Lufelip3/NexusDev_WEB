<?php if(session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION["login"])) {
    header("Location: " . (file_exists("login.php") ? "" : "../") . "login.php");
    exit();
}
?>
﻿<?php
include "configs/database.php";
$db = (new Database())->conectar();
$stmt = $db->query("DESCRIBE laboratorio");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($result);
$stmt = $db->query("SHOW INDEX FROM laboratorio");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($result);

