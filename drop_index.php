<?php
include "configs/database.php";
$db = (new Database())->conectar();
try {
    $db->exec("ALTER TABLE laboratorio DROP INDEX Num_Lab");
    echo "Index Num_Lab dropped successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
