<?php
include_once "../Objetos/itemCompraController.php";

if (!isset($_GET['notaFiscal_Entrada'])) {
    header("Location: ../Compra/index.php");
    exit();
}

$notaFiscal = $_GET['notaFiscal_Entrada'];
$controller = new ItemCompraController();
$itens = $controller->lerPorNotaFiscal($notaFiscal);
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Itens da Compra <?= htmlspecialchars($notaFiscal) ?></title>
    <style>
        table, tr, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
        }
    </style>
</head>
<body>

<h1>Itens da Compra - Nota Fiscal: <?= htmlspecialchars($notaFiscal) ?></h1>
<a href="../Compra/index.php">Voltar para Compras</a><br><br>

<table>
    <tr>
        <td>Código do Item</td>
        <td>Nome Medicamento</td>
        <td>Quantidade</td>
        <td>Valor Unitário</td>
        <td>Subtotal</td>
        <td>Data Venda</td>
    </tr>
    <?php if ($itens): ?>
        <?php 
        $total = 0;
        foreach ($itens as $item): 
            $subtotal = $item->Qtd_Item * $item->Valor_Item;
            $total += $subtotal;
        ?>
            <tr>
                <td><?= $item->Cod_Item ?></td>
                <td><?= $item->Nome_Med ?></td>
                <td><?= $item->Qtd_Item ?></td>
                <td>R$ <?= number_format($item->Valor_Item, 2, ',', '.') ?></td>
                <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                <td><?= $item->Data_Venda ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4" align="right"><b>Total da Compra:</b></td>
            <td colspan="2"><b>R$ <?= number_format($total, 2, ',', '.') ?></b></td>
        </tr>
    <?php else: ?>
        <tr>
            <td colspan="6">Nenhum item encontrado para esta compra.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
