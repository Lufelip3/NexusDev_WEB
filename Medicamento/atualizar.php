<?php
include_once ("../Objetos/medicamentoController.php");

$controller = new medicamentoController();
$a = null;

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["alterar"])){
    $a = $controller->localizarMedicamento($_GET["alterar"]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["atualizar"])){
    $controller->atualizarMedicamento($_POST["medicamento"], $_FILES["medicamento"] ?? null);

} else {
    header("location: index.php");
    exit();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atualizar Medicamento</title>
</head>
<body>
<h1>Atualizar Medicamento</h1>
<a href="index.php">Voltar</a>

<?php if($a): ?>
<form action="atualizar.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="medicamento[Cod_Med]" value="<?= htmlspecialchars($a->Cod_Med) ?>">

    <label>Nome</label>
    <input type="text" name="medicamento[Nome_Med]" value="<?= htmlspecialchars($a->Nome_Med) ?>"><br><br>

    <label>Descrição</label>
    <input type="text" name="medicamento[Desc_Med]" value="<?= htmlspecialchars($a->Desc_Med) ?>"><br><br>

    <label>Data Validade</label>
    <input type="date" name="medicamento[DataVal_Med]" value="<?= htmlspecialchars($a->DataVal_Med) ?>"><br><br>

    <label>Quantidade</label>
    <input type="number" name="medicamento[Qtd_Med]" value="<?= htmlspecialchars($a->Qtd_Med) ?>"><br><br>

    <label>Valor</label>
    <input type="number" step="0.01" name="medicamento[Valor_Med]" value="<?= htmlspecialchars($a->Valor_Med) ?>"><br><br>

    <label>Foto do Medicamento</label><br>
    <?php if(isset($a->Foto_Med) && $a->Foto_Med): ?>
        <img src="../uploads/medicamentos/<?= $a->Foto_Med ?>" width="100"><br>
    <?php endif; ?>
    <input type="file" name="medicamento[Foto_Med]"><br><br>

    <button name="atualizar">Atualizar</button>
</form>
<?php else: ?>
    <p>Medicamento não encontrado.</p>
<?php endif; ?>

</body>
</html>