<?php
include_once("../Objetos/compraController.php");
$controller = new CompraController();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["alterar"])) {
    $a = $controller->localizarCompra($_GET["alterar"]);

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["compra"])) {
    $controller->atualizarCompra($_POST["compra"]);

} else {
    header("Location: index.php");
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atualização de Compra</title>
</head>

<body>

<h1>Atualização de Compra</h1>

<a href="index.php">Voltar</a>

<form action="atualizaCompra.php" method="post">

    <input type="hidden" name="compra[NotaFiscal_Entrada]" value="<?= $a->NotaFiscal_Entrada ?>">

    <label>Valor Total</label>
    <input type="number" step="0.01" name="compra[Valor_Total]" value="<?= $a->Valor_Total ?>"><br><br>

    <label>Data da Compra</label>
    <input type="date" name="compra[Data_Compra]" value="<?= $a->Data_Compra ?>"><br><br>

    <label>CPF</label>
    <input type="text" name="compra[CPF]" value="<?= $a->CPF ?>"><br><br>

    <label>CNPJ Laboratório</label>
    <input type="text" name="compra[CNPJ_Lab]" value="<?= $a->CNPJ_Lab ?>"><br><br>

    <button type="submit" name="atualizar">Atualizar</button>

</form>

</body>
</html>
