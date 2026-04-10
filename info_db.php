<?php
include "configs/database.php";
$db = (new Database())->conectar();
$stmt = $db->query("DESCRIBE laboratorio");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($result);
$stmt = $db->query("SHOW INDEX FROM laboratorio");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($result);
