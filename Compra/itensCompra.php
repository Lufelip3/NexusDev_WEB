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

if ($compra->Finalizada == 1) {
    header("Location: ../ItemCompra/index.php?notaFiscal_Entrada=" . $nota_fiscal);
    exit();
}

$cnpj_lab = $_GET['cnpj_lab'] ?? $compra->CNPJ_Lab;

$itemController = new ItemCompraController();
$catController  = new CatalogoController();
$medController  = new MedicamentoController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
                'Cod_Med'            => null
            ]);
        } else {
            $_SESSION['erro_compra'] = 'Quantidade inválida ou acima do disponível em catálogo.';
        }
        header("Location: itensCompra.php?nota_fiscal_entrada={$nota_fiscal}&cnpj_lab={$cnpj_lab}");
        exit();
    }

    if (isset($_POST['remover'])) {
        $itemController->excluirItem((int)$_POST['cod_item']);
        header("Location: itensCompra.php?nota_fiscal_entrada={$nota_fiscal}&cnpj_lab={$cnpj_lab}");
        exit();
    }

    if (isset($_POST['salvar'])) {
        $totalCompra = $itemController->calcularTotal($nota_fiscal);
        $compraController->salvarRascunhoCompra($nota_fiscal, $totalCompra);
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['finalizar'])) {
        $itens = $itemController->lerPorNotaFiscal($nota_fiscal);

        foreach ($itens as $item) {
            $medExistente = $medController->buscarPorEAN($item->EAN_Med);

            if ($medExistente) {
                $medController->adicionarEstoqueProcedimento($medExistente->Cod_Med, $item->Qtd_Item);
                $codMedGerado = $medExistente->Cod_Med;
            } else {
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

            $itemController->atualizarCodMed($item->Cod_Item, $codMedGerado);
        }

        $totalCompra = $itemController->calcularTotal($nota_fiscal);
        if ($compraController->finalizarCompra($nota_fiscal, $totalCompra)) {
            echo "<script>alert('Compra Finalizada com Sucesso!'); window.location.href='index.php';</script>";
            exit();
        }
    }

    if (isset($_POST['cancelar'])) {
        $compraController->excluirCompra($nota_fiscal);
        header("Location: index.php");
        exit();
    }
}

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
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $compra->Valor_Total > 0 ? 'Editar' : 'Nova' ?> Compra – NF <?= $nota_fiscal ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="d-flex flex-nowrap" style="font-family: 'Manrope', sans-serif;">

  <aside class="b5-sidebar offcanvas-lg offcanvas-start p-3" tabindex="-1" id="menuLateral" aria-labelledby="menuLateralLabel">
    <div class="offcanvas-header d-lg-none border-bottom border-opacity-25 border-light mb-3">
      <h5 class="offcanvas-title fw-bold text-white text-uppercase" id="menuLateralLabel">Distribuidora CFA</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#menuLateral" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column flex-grow-1 p-0">
      <a href="../index.php" class="d-none d-lg-flex align-items-center mb-4 text-white text-decoration-none border-bottom pb-3 border-opacity-25" style="border-color:#fff;">
        <span class="fs-4 fw-bold text-uppercase ms-3">Distribuidora CFA</span>
      </a>
      <ul class="nav nav-pills flex-column mb-auto gap-2">
        <li class="nav-item"><a href="../Medicamento/index.php" class="nav-link"><span class="fs-5">💊</span> Medicamentos</a></li>
        <li class="nav-item"><a href="../funcionario/index.php" class="nav-link"><span class="fs-5">👥</span> Funcionários</a></li>
        <li class="nav-item"><a href="../laboratorio/index.php" class="nav-link"><span class="fs-5">🔬</span> Laboratórios</a></li>
        <li class="nav-item"><a href="../drogaria/index.php" class="nav-link"><span class="fs-5">🏪</span> Drogarias</a></li>
        <li class="nav-item"><a href="index.php" class="nav-link active"><span class="fs-5">🛒</span> Compras</a></li>
        <li class="nav-item"><a href="../Venda/index.php" class="nav-link"><span class="fs-5">📈</span> Vendas</a></li>
      </ul>
      <hr class="border-secondary mt-auto">
      <div class="ph-sidebar-footer">
        <a href="../logout.php" class="ph-btn-exit w-100 mt-2 text-decoration-none"><span class="fs-5">⏻</span> Sair do Sistema</a>
      </div>
    </div>
  </aside>

  <main class="b5-main p-4 p-md-5">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"><span class="fs-4">☰</span></button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">
            <?= $compra->Valor_Total > 0 ? 'Editar' : 'Nova' ?> Compra
            <span class="badge ms-2 fw-bold" style="background:#f39c12;border-radius:50px;font-size:.65em;">Em Aberto</span>
          </h1>
          <p class="text-secondary mb-0">NF #<?= $nota_fiscal ?> — Lab: <?= htmlspecialchars($cnpj_lab) ?></p>
        </div>
      </div>
      <a href="index.php" class="btn btn-outline-secondary fw-bold px-4">← Voltar</a>
    </div>

    <?php if ($erroCompra): ?>
    <div class="alert alert-danger mb-4"><?= htmlspecialchars($erroCompra) ?></div>
    <?php endif; ?>

    <div class="row g-4">
      <!-- Catálogo do laboratório -->
      <div class="col-lg-6">
        <div class="card card-pharma h-100">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-3" style="color:#1a1c4b;">Catálogo do Laboratório</h5>
            <form method="POST" class="d-flex gap-2 mb-3">
              <input type="text" name="termo_cat" class="form-control" placeholder="Nome ou EAN..." value="<?= htmlspecialchars($_POST['termo_cat'] ?? '') ?>">
              <button type="submit" name="pesquisa_cat" class="btn btn-pharma-primary fw-bold">Buscar</button>
              <?php if(isset($_POST['pesquisa_cat'])): ?>
                <a href="itensCompra.php?nota_fiscal_entrada=<?= $nota_fiscal ?>&cnpj_lab=<?= $cnpj_lab ?>" class="btn btn-outline-secondary fw-bold">✕</a>
              <?php endif; ?>
            </form>
            <div class="table-responsive">
              <table class="table table-pharma mb-0 align-middle" style="font-size:.88rem;">
                <thead>
                  <tr>
                    <th class="ps-3">Cód.</th><th>EAN</th><th>Nome</th><th>Estoque</th><th>Valor</th><th>Qtd</th><th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if($catalogos): ?>
                    <?php foreach($catalogos as $cat): ?>
                    <tr>
                      <td class="ps-3"><?= $cat->Cod_CatMed ?></td>
                      <td><?= $cat->EAN_Med ?></td>
                      <td class="fw-bold"><?= htmlspecialchars($cat->Nome_CatMed) ?></td>
                      <td><span class="badge bg-light text-dark border"><?= $cat->quantidade ?></span></td>
                      <td>R$ <?= number_format($cat->Valor_CatMed, 2, ',', '.') ?></td>
                      <td>
                        <form method="POST" class="d-flex gap-1 align-items-center">
                          <input type="hidden" name="cod_catMed" value="<?= $cat->Cod_CatMed ?>">
                          <input type="number" name="qtd" min="1" max="<?= $cat->quantidade ?>" value="1" class="form-control form-control-sm" style="width:55px;">
                          <button type="submit" name="adicionar" class="btn btn-sm btn-pharma-success fw-bold">Add</button>
                        </form>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="7" class="text-center text-secondary p-3">Catálogo vazio para este laboratório.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Itens da compra -->
      <div class="col-lg-6">
        <div class="card card-pharma h-100">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-3" style="color:#1a1c4b;">Itens da Compra</h5>
            <div class="table-responsive">
              <table class="table table-pharma mb-0 align-middle" style="font-size:.88rem;">
                <thead>
                  <tr>
                    <th class="ps-3">Cód.</th><th>EAN</th><th>Nome</th><th>Qtd</th><th>Valor</th><th>Subtotal</th><th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($itensAtuais)): ?>
                    <?php foreach($itensAtuais as $item): ?>
                    <tr>
                      <td class="ps-3"><?= $item->Cod_Item ?></td>
                      <td><?= $item->EAN_Med ?></td>
                      <td class="fw-bold"><?= htmlspecialchars($item->Nome_CatMed) ?></td>
                      <td><?= $item->Qtd_Item ?></td>
                      <td>R$ <?= number_format($item->Valor_Item, 2, ',', '.') ?></td>
                      <td class="fw-bold text-success">R$ <?= number_format($item->Qtd_Item * $item->Valor_Item, 2, ',', '.') ?></td>
                      <td>
                        <form method="POST" onsubmit="return confirm('Remover este item?')">
                          <input type="hidden" name="cod_item" value="<?= $item->Cod_Item ?>">
                          <button type="submit" name="remover" class="btn btn-sm btn-outline-danger">🗑</button>
                        </form>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="table-light">
                      <td colspan="5" class="text-end fw-bold pe-2">Total Estimado:</td>
                      <td class="fw-bold text-success fs-5">R$ <?= number_format($totalCompra, 2, ',', '.') ?></td>
                      <td></td>
                    </tr>
                  <?php else: ?>
                    <tr><td colspan="7" class="text-center text-secondary p-3">Nenhum item adicionado ainda.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <!-- Botões de ação -->
            <form method="POST" class="d-flex gap-2 flex-wrap mt-4 pt-3 border-top">
              <button type="submit" name="finalizar" class="btn btn-pharma-success fw-bold flex-fill" <?= empty($itensAtuais) ? 'disabled' : '' ?>>✔ Finalizar Compra</button>
              <button type="submit" name="salvar" class="btn btn-warning text-white fw-bold flex-fill" formnovalidate>💾 Continuar Depois</button>
              <button type="submit" name="cancelar" class="btn btn-danger fw-bold flex-fill" formnovalidate onclick="return confirm('Cancelar e excluir esta compra?')">✖ Cancelar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
