<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

include_once "../Objetos/compraController.php";
include_once "../Objetos/catalogoController.php";
include_once "../Objetos/medicamentoController.php";
include_once "../Objetos/itemCompraController.php";

if (!isset($_GET['nota_fiscal_entrada'])) {
    header("Location: index.php");
    exit();
}
$nota_fiscal = (int)$_GET['nota_fiscal_entrada'];

$compraController = new CompraController();
$compra = $compraController->localizarCompra($nota_fiscal);

if (!$compra) {
    header("Location: index.php");
    exit();
}

// Se já finalizada, redireciona para visualização
if ($compra->Finalizada == 1) {
    header("Location: ../ItemCompra/index.php?notaFiscal_Entrada=" . $nota_fiscal);
    exit();
}

$cnpj_lab = $_GET['cnpj_lab'] ?? $compra->CNPJ_Lab;

$itemController = new ItemCompraController();
$catController  = new CatalogoController();
$medController  = new MedicamentoController();

// ===== AÇÕES POST =====
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Adicionar item diretamente ao banco (Cod_Med = NULL até finalizar)
    if (isset($_POST['adicionar'])) {
        $codCatMed = (int)$_POST['cod_catMed'];
        $qtd       = (int)$_POST['qtd'];
        $cat       = $catController->buscarCatalogo($codCatMed);

        if ($cat && $qtd > 0 && $qtd <= $cat->quantidade) {
            $itemController->cadastrarItemCompra([
                'DataVal_Item'       => $cat->dataValItemCat,
                'Qtd_Item'           => $qtd,
                'Valor_Item'         => $cat->Valor_CatMed,
                'Data_Venda'         => date('Y-m-d'),
                'NotaFiscal_Entrada' => $nota_fiscal,
                'Cod_CatMed'         => $cat->Cod_CatMed,
                'Cod_Med'            => null  // preenchido somente na finalização
            ]);
        } else {
            $_SESSION['erro_compra'] = 'Quantidade inválida ou acima do disponível em catálogo.';
        }
        header("Location: itensCompra.php?nota_fiscal_entrada={$nota_fiscal}&cnpj_lab={$cnpj_lab}");
        exit();
    }

    // Remover item do banco
    if (isset($_POST['remover'])) {
        $itemController->excluirItem((int)$_POST['cod_item']);
        header("Location: itensCompra.php?nota_fiscal_entrada={$nota_fiscal}&cnpj_lab={$cnpj_lab}");
        exit();
    }

    // Salvar e sair (Continuar Depois)
    if (isset($_POST['salvar'])) {
        $totalCompra = $itemController->calcularTotal($nota_fiscal);
        $compraController->salvarRascunhoCompra($nota_fiscal, $totalCompra);
        header("Location: index.php");
        exit();
    }

    // Finalizar compra
    if (isset($_POST['finalizar'])) {
        $itens = $itemController->lerPorNotaFiscal($nota_fiscal);

        // 1. Para cada item: criar/atualizar medicamento físico e preencher Cod_Med
        foreach ($itens as $item) {
            $medExistente = $medController->buscarPorEAN($item->EAN_Med);

            if ($medExistente) {
                // Medicamento já existe: apenas incrementa estoque
                $medController->adicionarEstoqueProcedimento($medExistente->Cod_Med, $item->Qtd_Item);
                $codMedGerado = $medExistente->Cod_Med;
            } else {
                // Novo medicamento: cadastra com base no catálogo
                $codMedGerado = $medController->cadastrarMedicamentoERetornarId([
                    'EAN_Med'     => $item->EAN_Med,
                    'Nome_Med'    => $item->Nome_CatMed,
                    'Desc_Med'    => $item->Desc_CatMed,
                    'DataVal_Med' => $item->dataValItemCat,
                    'Qtd_Med'     => $item->Qtd_Item,
                    'Valor_Med'   => $item->Valor_Item,
                    'Cod_CatMed'  => $item->Cod_CatMed
                ]);
            }

            // 2. Atualiza o Cod_Med no item (necessário para relatórios)
            $itemController->atualizarCodMed($item->Cod_Item, $codMedGerado);
        }

        // 3. Seta Finalizada=1 → trigger decrementa catálogo automaticamente
        $totalCompra = $itemController->calcularTotal($nota_fiscal);
        if ($compraController->finalizarCompra($nota_fiscal, $totalCompra)) {
            echo "<script>alert('Compra Finalizada com Sucesso!'); window.location.href='index.php';</script>";
            exit();
        }
    }

    // Cancelar (exclui tudo)
    if (isset($_POST['cancelar'])) {
        $compraController->excluirCompra($nota_fiscal);
        header("Location: index.php");
        exit();
    }
}

// ===== DADOS PARA A VIEW =====
if (isset($_POST['pesquisa_cat']) && !empty($_POST['termo_cat'])) {
    $catalogos = $catController->pesquisarPorTermo($cnpj_lab, $_POST['termo_cat']);
} else {
    $catalogos = $catController->lerPorCnpj($cnpj_lab);
}

$itensAtuais = $itemController->lerPorNotaFiscal($nota_fiscal);
$totalCompra = $itemController->calcularTotal($nota_fiscal);

$erroCompra = $_SESSION['erro_compra'] ?? null;
unset($_SESSION['erro_compra']);
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $compra->Valor_Total > 0 ? 'Editar' : 'Nova' ?> Compra - NF <?= $nota_fiscal ?></title>
    <style>
        body  { font-family: sans-serif; margin: 16px; }
        table, tr, td, th { border: 1px solid #888; border-collapse: collapse; padding: 5px; }
        th { background: #eee; text-align: center; }
        .container { display: flex; gap: 24px; }
        .column    { flex: 1; }
        .error     { background: #ffdddd; padding: 8px; margin-bottom: 10px; border-radius: 4px; }
        .btn-danger  { background: #e74c3c; color:#fff; border:none; cursor:pointer; padding:6px 12px; }
        .btn-primary { background: #2980b9; color:#fff; border:none; cursor:pointer; padding:6px 12px; }
        .btn-success { background: #27ae60; color:#fff; border:none; cursor:pointer; padding:6px 12px; }
        .btn-warning { background: #f39c12; color:#fff; border:none; cursor:pointer; padding:6px 12px; }
        .status-badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:12px; }
        .rascunho { background:#f39c12; color:#fff; }
    </style>
</head>
<body>

<h1><?= $compra->Valor_Total > 0 ? 'Editar' : 'Nova' ?> Compra
    <span class="status-badge rascunho">Em Aberto</span>
    — Nota Fiscal: <?= $nota_fiscal ?>
</h1>
<h3>Laboratório: <?= htmlspecialchars($cnpj_lab) ?></h3>

<?php if ($erroCompra): ?>
    <div class="error"><?= htmlspecialchars($erroCompra) ?></div>
<?php endif; ?>

<div class="container">
    <!-- COLUNA ESQUERDA: Catálogo do laboratório -->
    <div class="column">
        <h2>Catálogo do Laboratório</h2>

        <form method="POST" style="margin-bottom:10px;">
            <input type="text" name="termo_cat" placeholder="Nome ou EAN..." value="<?= htmlspecialchars($_POST['termo_cat'] ?? '') ?>">
            <button type="submit" name="pesquisa_cat" class="btn-primary">Pesquisar</button>
            <?php if(isset($_POST['pesquisa_cat'])): ?>
                <a href="itensCompra.php?nota_fiscal_entrada=<?= $nota_fiscal ?>&cnpj_lab=<?= $cnpj_lab ?>">Limpar</a>
            <?php endif; ?>
        </form>

        <table>
            <tr><th>Código</th><th>EAN</th><th>Nome</th><th>Validade</th><th>Estoque Cat.</th><th>Valor</th><th>Ação</th></tr>
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
                            <button type="submit" name="adicionar" class="btn-primary">Add</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Catálogo vazio para este laboratório.</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- COLUNA DIREITA: Itens da compra + ações -->
    <div class="column">
        <h2>Itens da Compra</h2>
        <table>
            <tr><th>Cod.</th><th>EAN</th><th>Nome</th><th>Qtd</th><th>Valor Unit.</th><th>Subtotal</th><th>Ação</th></tr>
            <?php if(!empty($itensAtuais)): ?>
                <?php foreach($itensAtuais as $item): ?>
                <tr>
                    <td><?= $item->Cod_Item ?></td>
                    <td><?= $item->EAN_Med ?></td>
                    <td><?= $item->Nome_CatMed ?></td>
                    <td><?= $item->Qtd_Item ?></td>
                    <td>R$ <?= number_format($item->Valor_Item, 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item->Qtd_Item * $item->Valor_Item, 2, ',', '.') ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Remover este item?')">
                            <input type="hidden" name="cod_item" value="<?= $item->Cod_Item ?>">
                            <button type="submit" name="remover" class="btn-danger">Remover</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Nenhum item adicionado ainda.</td></tr>
            <?php endif; ?>
            <tr>
                <td colspan="5" align="right"><b>Total Estimado:</b></td>
                <td colspan="2"><b>R$ <?= number_format($totalCompra, 2, ',', '.') ?></b></td>
            </tr>
        </table>

        <br>
        <form method="POST">
            <button type="submit" name="finalizar" class="btn-success"
                <?= empty($itensAtuais) ? 'disabled' : '' ?>>✔ Finalizar Compra</button>
            <button type="submit" name="salvar"   class="btn-warning" formnovalidate>💾 Continuar Depois</button>
            <button type="submit" name="cancelar" class="btn-danger"  formnovalidate onclick="return confirm('Cancelar e excluir esta compra?')">✖ Cancelar Compra</button>
        </form>
    </div>
</div>

</body>
</html>
