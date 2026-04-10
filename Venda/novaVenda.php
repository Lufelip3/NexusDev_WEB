<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once "../objetos/vendaController.php";
include_once "../objetos/medicamentoController.php";
include_once "../objetos/drogariaController.php";
include_once "../objetos/itemVendaController.php";

if (!isset($_GET['nota_fiscal_saida'])) {
    header("Location: index.php");
    exit();
}
$nota_fiscal = $_GET['nota_fiscal_saida'];

if (!isset($_SESSION['carrinho_venda'])) {
    $_SESSION['carrinho_venda'] = [];
}

$medController = new medicamentoController();

if (isset($_POST['pesquisa_med']) && !empty($_POST['termo_med'])) {
    $medicamentos = $medController->pesquisarPorTermo($_POST['termo_med']);
} else {
    $medicamentos = $medController->index();
}

$drogController = new drogariaController();
$drogarias = $drogController->index();

$totalVenda = 0;
foreach($_SESSION['carrinho_venda'] as $item){
    $totalVenda += ($item['Valor'] * $item['Qtd']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar'])) {
        $codMed = $_POST['cod_med'];
        $qtd = (int)$_POST['qtd'];
        $med = $medController->localizarMedicamento($codMed);
        
        if ($med && $qtd > 0) {
            $_SESSION['carrinho_venda'][] = [
                'Cod_Med' => $med->Cod_Med,
                'Nome' => $med->Nome_Med,
                'Valor' => $med->Valor_Med,
                'Qtd' => $qtd,
                'DataVal' => $med->DataVal_Med
            ];
        }
        header("Location: novaVenda.php?nota_fiscal_saida=" . $nota_fiscal);
        exit();
    }
    
    if (isset($_POST['finalizar'])) {
        $cnpj_drog = $_POST['cnpj_drog'];
        
        $itemController = new ItemVendaController();
        foreach($_SESSION['carrinho_venda'] as $item){
            $dadosItem = [
                'DataVal_ItemVenda' => $item['DataVal'],
                'Qtd_ItemVenda' => $item['Qtd'],
                'Valor_ItemVenda' => $item['Valor'],
                'Cod_Med' => $item['Cod_Med'],
                'NotaFiscal_Saida' => $nota_fiscal
            ];
            $itemController->cadastrarItemVenda($dadosItem);
        }
        
        $vendaController = new VendaController();
        if ($vendaController->finalizarVenda($nota_fiscal, $totalVenda, $cnpj_drog)) {
            $_SESSION['carrinho_venda'] = [];
            header("Location: index.php");
            exit();
        }
    }
    
    if (isset($_POST['cancelar'])) {
        $vendaController = new VendaController();
        $vendaController->excluirVenda($nota_fiscal);
        $_SESSION['carrinho_venda'] = [];
        header("Location: index.php");
        exit();
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Venda</title>
    <style>
        table,tr,td{
            border:1px solid black;
            border-collapse:collapse;
            padding:5px;
        }
        .container { display: flex; gap: 20px; }
        .column { flex: 1; }
    </style>
</head>
<body>

<h1>Nova Venda - Nota Fiscal: <?= $nota_fiscal ?></h1>

<div class="container">
    <div class="column">
        <h2>Medicamentos Disponíveis</h2>
        
        <form method="POST" style="margin-bottom: 10px;">
            <input type="text" name="termo_med" placeholder="Nome ou EAN..." value="<?= $_POST['termo_med'] ?? '' ?>">
            <button type="submit" name="pesquisa_med">Pesquisar</button>
            <?php if(isset($_POST['pesquisa_med'])): ?>
                <a href="novaVenda.php?nota_fiscal_saida=<?= $nota_fiscal ?>">Limpar</a>
            <?php endif; ?>
        </form>

        <table>
            <tr>
                <td>Código</td>
                <td>Nome</td>
                <td>Estoque</td>
                <td>Valor</td>
                <td>Ação</td>
            </tr>
            <?php if($medicamentos): ?>
                <?php foreach($medicamentos as $med): ?>
                <tr>
                    <td><?= $med->Cod_Med ?></td>
                    <td><?= $med->Nome_Med ?></td>
                    <td><?= $med->Qtd_Med ?></td>
                    <td>R$ <?= number_format($med->Valor_Med, 2, ',', '.') ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="cod_med" value="<?= $med->Cod_Med ?>">
                            <input type="number" name="qtd" min="1" max="<?= $med->Qtd_Med ?>" value="1" style="width:50px">
                            <button type="submit" name="adicionar">Adicionar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>

    <div class="column">
        <h2>Carrinho</h2>
        <table>
            <tr>
                <td>Código</td>
                <td>Nome</td>
                <td>Qtd</td>
                <td>Valor Unit.</td>
                <td>Subtotal</td>
            </tr>
            <?php if(!empty($_SESSION['carrinho_venda'])): ?>
                <?php foreach($_SESSION['carrinho_venda'] as $item): ?>
                <tr>
                    <td><?= $item['Cod_Med'] ?></td>
                    <td><?= $item['Nome'] ?></td>
                    <td><?= $item['Qtd'] ?></td>
                    <td>R$ <?= number_format($item['Valor'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['Valor'] * $item['Qtd'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Carrinho vazio</td></tr>
            <?php endif; ?>
            <tr>
                <td colspan="4" align="right"><b>Total da Venda:</b></td>
                <td><b>R$ <?= number_format($totalVenda, 2, ',', '.') ?></b></td>
            </tr>
        </table>
        
        <br>
        <form method="POST">
            <label>Selecione a Drogaria:</label><br>
            <select name="cnpj_drog" id="cnpj_drog" required onchange="verificarFinalizar()">
                <option value="">Selecione...</option>
                <?php if($drogarias): ?>
                    <?php foreach($drogarias as $drog): ?>
                        <option value="<?= $drog->CNPJ_Drog ?>"><?= $drog->Nome_Drog ?> (<?= $drog->CNPJ_Drog ?>)</option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <br><br>
            <button type="submit" name="finalizar" id="btn_finalizar" disabled>Finalizar Venda</button>
            <button type="submit" name="cancelar" style="background-color: #ffcccc;" formnovalidate>Cancelar Venda</button>
        </form>
    </div>
</div>

<script>
    function verificarFinalizar() {
        var select = document.getElementById('cnpj_drog');
        var btn = document.getElementById('btn_finalizar');
        var carrinhoVazio = <?= empty($_SESSION['carrinho_venda']) ? 'true' : 'false' ?>;
        
        if (carrinhoVazio || select.value === "") {
            btn.disabled = true;
        } else {
            btn.disabled = false;
        }
    }
    
    // Verificar estado inicial
    window.onload = verificarFinalizar;
</script>

</body>
</html>
