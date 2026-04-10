<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

include_once "../Objetos/compraController.php";
include_once "../Objetos/catalogoController.php";
include_once "../Objetos/medicamentoController.php";
include_once "../Objetos/itemCompraController.php";

if (!isset($_GET['nota_fiscal_entrada']) || !isset($_GET['cnpj_lab'])) {
    header("Location: index.php");
    exit();
}
$nota_fiscal = $_GET['nota_fiscal_entrada'];
$cnpj_lab = $_GET['cnpj_lab'];

if (!isset($_SESSION['carrinho_compra'])) {
    $_SESSION['carrinho_compra'] = [];
}

$catController = new CatalogoController();

if (isset($_POST['pesquisa_cat']) && !empty($_POST['termo_cat'])) {
    $catalogos = $catController->pesquisarPorTermo($cnpj_lab, $_POST['termo_cat']);
} else {
    $catalogos = $catController->lerPorCnpj($cnpj_lab);
}

$totalCompra = 0;
foreach($_SESSION['carrinho_compra'] as $item){
    $totalCompra += ($item['Valor'] * $item['Qtd']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ============================================
    // ADICIONAR ITEM AO CARRINHO DA SESSÃO
    // ============================================
    if (isset($_POST['adicionar'])) {
        $codCatMed = $_POST['cod_catMed'];
        $qtd = (int)$_POST['qtd'];
        $cat = $catController->buscarCatalogo($codCatMed);
        
        if ($cat && $qtd > 0 && $qtd <= $cat->quantidade) {
            $found = false;
            foreach ($_SESSION['carrinho_compra'] as &$c_item) {
                if ($c_item['EAN_Med'] == $cat->EAN_Med) {
                    if ($c_item['Qtd'] + $qtd <= $cat->quantidade) {
                        $c_item['Qtd'] += $qtd;
                    } else {
                        echo "<script>alert('Quantidade acumulada excede o estoque de catálogo disponível.');</script>";
                    }
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $_SESSION['carrinho_compra'][] = [
                    'Cod_CatMed'         => $cat->Cod_CatMed,
                    'EAN_Med'            => $cat->EAN_Med,
                    'Nome_CatMed'        => $cat->Nome_CatMed,
                    'Desc_CatMed'        => $cat->Desc_CatMed,
                    'Valor'              => $cat->Valor_CatMed,
                    'Qtd'                => $qtd,
                    'datacompraItemCat'  => $cat->datacompraItemCat,
                    'dataValItemCat'     => $cat->dataValItemCat
                ];
            }
        } else {
            echo "<script>alert('Quantidade inválida ou acima do estoque de catálogo disponível.');</script>";
        }
        
        // Redireciona de volta para evitar reenvio de POST
        header("Location: itensCompra.php?nota_fiscal_entrada=$nota_fiscal&cnpj_lab=$cnpj_lab");
        exit();
    }
    
    // ============================================
    // FINALIZAR COMPRA
    // ============================================
    if (isset($_POST['finalizar'])) {
        $itemController = new ItemCompraController();
        $medController  = new MedicamentoController();
        
        foreach($_SESSION['carrinho_compra'] as $item) {
            
            // Busca se aquele mesmo remédio físico (EAN) já existe na prateleira da farmácia local
            $medExistente = $medController->buscarPorEAN($item['EAN_Med']);
            
            if ($medExistente) {
                // Já existe, não re-cadastramos. Apenas usamos a Stored Procedure para aumentar a "Qtd_Med" local
                $medController->adicionarEstoqueProcedimento($medExistente->Cod_Med, $item['Qtd']);
                $codMedGerado = $medExistente->Cod_Med;
            } else {
                // 1. O medicamento é NOVO e não existia na farmácia. Registra o lote vindo no lab.
                $dadosMed = [
                    'EAN_Med'     => $item['EAN_Med'],
                    'Nome_Med'    => $item['Nome_CatMed'],
                    'Desc_Med'    => $item['Desc_CatMed'],
                    'DataVal_Med' => $item['dataValItemCat'],
                    'Qtd_Med'     => $item['Qtd'],
                    'Valor_Med'   => $item['Valor'],
                    'Cod_CatMed'  => $item['Cod_CatMed']
                ];
                
                $codMedGerado = $medController->cadastrarMedicamentoERetornarId($dadosMed);
            }
            
            // 2. Transfere para a tabela 'item' da compra
            $dadosItem = [
                'DataVal_Item'       => $item['dataValItemCat'],
                'Qtd_Item'           => $item['Qtd'],
                'Valor_Item'         => $item['Valor'],
                'Data_Venda'         => date('Y-m-d'), // simulando data em que entrou/vendeu do lab
                'NotaFiscal_Entrada' => $nota_fiscal,
                'Cod_CatMed'         => $item['Cod_CatMed'],
                'Cod_Med'            => $codMedGerado
            ];
            $itemController->cadastrarItemCompra($dadosItem);
            
            // 3. O trigger 'trg_baixa_estoque_apos_compra' já atualiza o catálogo, subtraindo valor comprado
            // $catController->atualizarQuantidade($item['Cod_CatMed'], $item['Qtd']);
        }
        
        // 4. Salvar valor total da transação de compra
        $compraController = new CompraController();
        if ($compraController->finalizarCompra($nota_fiscal, $totalCompra)) {
            $_SESSION['carrinho_compra'] = [];
            
            echo "<script>alert('Compra Finalizada com Sucesso! Foram gerados os medicamentos correspondentes.'); window.location.href='index.php';</script>";
            exit();
        }
    }
    
    // ============================================
    // CANCELAR COMPRA E DELETAR NOTA VAZIA
    // ============================================
    if (isset($_POST['cancelar'])) {
        $compraController = new CompraController();
        $compraController->excluirCompra($nota_fiscal);
        $_SESSION['carrinho_compra'] = [];
        header("Location: index.php");
        exit();
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Itens Nova Compra</title>
    <style>
        table, tr, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
            text-align: center;
        }
        .container { display: flex; gap: 20px; }
        .column { flex: 1; }
        button { cursor: pointer; padding: 4px; }
    </style>
</head>
<body>

<h1>Itens de Nova Compra - Nota Fiscal Entrada: <?= $nota_fiscal ?></h1>
<h3>Filtro aplicado (CNPJ Laboratório): <?= $cnpj_lab ?></h3>

<div class="container">
    <div class="column">
        <h2>Catálogo do Laboratório</h2>

        <form method="POST" style="margin-bottom: 10px;">
            <input type="text" name="termo_cat" placeholder="Nome ou EAN..." value="<?= $_POST['termo_cat'] ?? '' ?>">
            <button type="submit" name="pesquisa_cat">Pesquisar</button>
            <?php if(isset($_POST['pesquisa_cat'])): ?>
                <a href="itensCompra.php?nota_fiscal_entrada=<?= $nota_fiscal ?>&cnpj_lab=<?= $cnpj_lab ?>">Limpar</a>
            <?php endif; ?>
        </form>

        <table>
            <tr>
                <td>Código</td>
                <td>EAN</td>
                <td>Nome do Catálogo</td>
                <td>Data Validade</td>
                <td>Disp. Catálogo</td>
                <td>Valor</td>
                <td>Ação</td>
            </tr>
            <?php if($catalogos): ?>
                <?php foreach($catalogos as $cat): ?>
                <tr>
                    <td><?= $cat->Cod_CatMed ?></td>
                    <td><?= $cat->EAN_Med ?></td>
                    <td><?= $cat->Nome_CatMed ?></td>
                    <td><?= $cat->dataValItemCat ?></td>
                    <td><?= $cat->quantidade ?></td>
                    <td>R$ <?= number_format($cat->Valor_CatMed, 2, ',', '.') ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="cod_catMed" value="<?= $cat->Cod_CatMed ?>">
                            <input type="number" name="qtd" min="1" max="<?= $cat->quantidade ?>" value="1" style="width:50px">
                            <button type="submit" name="adicionar">Add</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">O catálogo do laboratório está vazio! Verifique o banco.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="column">
        <h2>Itens da Compra</h2>
        <table>
            <tr>
                <td>Código Cat</td>
                <td>EAN</td>
                <td>Nome</td>
                <td>Qtd Comprada</td>
                <td>Valor Unit.</td>
                <td>Subtotal</td>
            </tr>
            <?php if(!empty($_SESSION['carrinho_compra'])): ?>
                <?php foreach($_SESSION['carrinho_compra'] as $item): ?>
                <tr>
                    <td><?= $item['Cod_CatMed'] ?></td>
                    <td><?= $item['EAN_Med'] ?></td>
                    <td><?= $item['Nome_CatMed'] ?></td>
                    <td><?= $item['Qtd'] ?></td>
                    <td>R$ <?= number_format($item['Valor'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['Valor'] * $item['Qtd'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum item inserido na compra.</td></tr>
            <?php endif; ?>
            <tr>
                <td colspan="4" align="right"><b>Total Estimado:</b></td>
                <td><b>R$ <?= number_format($totalCompra, 2, ',', '.') ?></b></td>
            </tr>
        </table>
        
        <br>
        <form method="POST">
            <button type="submit" name="finalizar" id="btn_finalizar" <?php if(empty($_SESSION['carrinho_compra'])) echo 'disabled'; ?>>Finalizar Compra</button>
            <button type="submit" name="cancelar" style="background-color: #ffcccc;" formnovalidate>Cancelar Compra</button>
        </form>
    </div>
</div>

</body>
</html>
