<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once "../Objetos/compraController.php";
include_once "../Objetos/laboratorioController.php";

$controller = new CompraController();
$compras = $controller->index();

$labController = new laboratorioController();
$laboratorios = $labController->index();

$a = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["iniciar_compra"])) {
        if(!isset($_SESSION['cpf']) || empty($_SESSION['cpf'])){
            header("Location: ../login.php");
            exit();
        }
        $cnpj_lab = trim($_POST['cnpj_lab']);
        if(empty($cnpj_lab)) {
            echo "<script>alert('Selecione um laboratório para iniciar a compra!');</script>";
        } else {
            $id_nota_fiscal = $controller->iniciarCompra($_SESSION['cpf'], $cnpj_lab);
            header("Location: itensCompra.php?nota_fiscal_entrada=" . $id_nota_fiscal . "&cnpj_lab=" . $cnpj_lab);
            exit();
        }
    }

    if (isset($_POST["pesquisar"])) {
        $a = $controller->pesquisaCompra($_POST["pesquisar"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["excluir"])) {
        $controller->excluirCompra($_GET["excluir"]);
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Compras Cadastradas</title>

    <style>
        table,tr,td{
            border:1px solid black;
            border-collapse:collapse;
            padding:5px;
        }
    </style>

</head>

<body>

<h1>Compras</h1>
<a href="../index.php">Voltar</a><br><br>

<form method="POST">
    <label>Selecione o Laboratório para a nova compra:</label><br>
    <select name="cnpj_lab" required>
        <option value="">Selecione...</option>
        <?php if($laboratorios): ?>
            <?php foreach($laboratorios as $lab): ?>
                <option value="<?= $lab->CNPJ_Lab ?>"><?= $lab->Nome_Lab ?> - <?= $lab->CNPJ_Lab ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <button name="iniciar_compra">Iniciar Compra</button>
</form>
<br>

<h3>Pesquisar Compra</h3>

<form method="POST">
    <label>Nota Fiscal</label>
    <input type="number" name="pesquisar">
    <button>Pesquisar</button>
</form>

<table>
    <tr>
        <td>Nota Fiscal</td>
        <td>Valor Total</td>
        <td>Data Compra</td>
        <td>CPF</td>
        <td>CNPJ Laboratório</td>
    </tr>

    <?php if($a) : ?>
        <tr>
            <td><a href="../ItemCompra/index.php?notaFiscal_Entrada=<?= $a->NotaFiscal_Entrada ?>"><?= $a->NotaFiscal_Entrada ?></a></td>
            <td><a href="../ItemCompra/index.php?notaFiscal_Entrada=<?= $a->NotaFiscal_Entrada ?>"><?= $a->Valor_Total ?></a></td>
            <td><a href="../ItemCompra/index.php?notaFiscal_Entrada=<?= $a->NotaFiscal_Entrada ?>"><?= $a->Data_Compra ?></a></td>
            <td><a href="../ItemCompra/index.php?notaFiscal_Entrada=<?= $a->NotaFiscal_Entrada ?>"><?= $a->CPF ?></a></td>
            <td><a href="../ItemCompra/index.php?notaFiscal_Entrada=<?= $a->NotaFiscal_Entrada ?>"><?= $a->CNPJ_Lab ?></a></td>
        </tr>
    <?php endif; ?>
</table>

<h2>Compras cadastradas</h2>

<table>
    <tr>
        <td>Nota Fiscal</td>
        <td>Valor Total</td>
        <td>Data Compra</td>
        <td>CPF</td>
        <td>CNPJ Laboratório</td>
    </tr>

    <?php if($compras) : ?>
        <?php foreach($compras as $compra) : ?>
            <tr>
                <td><a href="../ItemCompra/index.php?notaFiscal_Entrada=<?= $compra->NotaFiscal_Entrada ?>"><?= $compra->NotaFiscal_Entrada ?></a></td>
                <td><a href="../ItemCompra/index.php?notaFiscal_Entrada=<?= $compra->NotaFiscal_Entrada ?>"><?= $compra->Valor_Total ?></a></td>
                <td><a href="../ItemCompra/index.php?notaFiscal_Entrada=<?= $compra->NotaFiscal_Entrada ?>"><?= $compra->Data_Compra ?></a></td>
                <td><a href="../ItemCompra/index.php?notaFiscal_Entrada=<?= $compra->NotaFiscal_Entrada ?>"><?= $compra->CPF ?></a></td>
                <td><a href="../ItemCompra/index.php?notaFiscal_Entrada=<?= $compra->NotaFiscal_Entrada ?>"><?= $compra->CNPJ_Lab ?></a></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>

</table>

</body>
</html>