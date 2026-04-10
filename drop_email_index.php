<?php
include "configs/database.php";
$db = (new Database())->conectar();
try {
    $db->exec("ALTER TABLE laboratorio DROP INDEX Email_Lab");
    echo "Index Email_Lab dropped successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
