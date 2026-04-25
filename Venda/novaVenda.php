<?php
if (session_status() !== PHP_SESSION_ACTIVE)
  session_start();
include_once "../Objetos/vendaController.php";
include_once "../Objetos/medicamentoController.php";
include_once "../Objetos/drogariaController.php";
include_once "../Objetos/itemVendaController.php";

if (!isset($_GET['nota_fiscal_saida'])) {
  header("Location: index.php");
  exit();
}
$nota_fiscal = (int) $_GET['nota_fiscal_saida'];

$vendaController = new VendaController();
$venda = $vendaController->localizarVenda($nota_fiscal);

if (!$venda) {
  header("Location: index.php");
  exit();
}

if ($venda->Finalizada == 1) {
  header("Location: ../ItemVenda/index.php?notaFiscal_Saida=" . $nota_fiscal);
  exit();
}

$itemController = new ItemVendaController();
$medController = new MedicamentoController();
$drogController = new DrogariaController();

// ===== AÇÕES POST =====
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['adicionar'])) {
    $codMed = (int) $_POST['cod_med'];
    $qtd = (int) $_POST['qtd'];
    $med = $medController->localizarMedicamento($codMed);

    if ($med && $qtd > 0 && $qtd <= $med->Qtd_Med) {
      $itemController->cadastrarItemVenda([
        'DataVal_ItemVenda' => $med->DataVal_Med,
        'Qtd_ItemVenda' => $qtd,
        'Valor_ItemVenda' => $med->Valor_Med,
        'Cod_Med' => $med->Cod_Med,
        'NotaFiscal_Saida' => $nota_fiscal
      ]);
    } else {
      $_SESSION['erro_venda'] = 'Quantidade inválida ou acima do estoque disponível.';
    }
    header("Location: novaVenda.php?nota_fiscal_saida=" . $nota_fiscal);
    exit();
  }

  if (isset($_POST['remover'])) {
    $itemController->excluirItem((int) $_POST['cod_item_venda']);
    header("Location: novaVenda.php?nota_fiscal_saida=" . $nota_fiscal);
    exit();
  }

  if (isset($_POST['salvar'])) {
    $cnpj_drog = $_POST['cnpj_drog'] ?? null;
    $totalVenda = $itemController->calcularTotal($nota_fiscal);
    $vendaController->salvarRascunhoVenda($nota_fiscal, $totalVenda, $cnpj_drog);
    header("Location: index.php");
    exit();
  }

  if (isset($_POST['finalizar'])) {
    $cnpj_drog = $_POST['cnpj_drog'];
    $totalVenda = $itemController->calcularTotal($nota_fiscal);
    if ($vendaController->finalizarVenda($nota_fiscal, $totalVenda, $cnpj_drog)) {
      header("Location: index.php");
      exit();
    }
  }

  if (isset($_POST['cancelar'])) {
    $vendaController->excluirVenda($nota_fiscal);
    header("Location: index.php");
    exit();
  }
}

// ===== DADOS PARA A VIEW =====
if (isset($_POST['pesquisa_med']) && !empty($_POST['termo_med'])) {
  $medicamentos = $medController->pesquisarPorTermo($_POST['termo_med']);
} else {
  $medicamentos = $medController->index();
}

$drogarias = $drogController->index();
$itensAtuais = $itemController->localizarItemVenda($nota_fiscal);
$totalVenda = $itemController->calcularTotal($nota_fiscal);

$erroVenda = $_SESSION['erro_venda'] ?? null;
unset($_SESSION['erro_venda']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $venda->CNPJ_Drog ? 'Editar' : 'Nova' ?> Venda – PharmaPulse</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../css/style.css">
</head>

<body class="d-flex flex-nowrap" style="font-family: 'Manrope', sans-serif;">

  <!-- Sidebar Bootstrap Customizada (Agora com Offcanvas Responsivo) -->
  <aside class="b5-sidebar offcanvas-lg offcanvas-start p-3" tabindex="-1" id="menuLateral" aria-labelledby="menuLateralLabel">
    <div class="offcanvas-header d-lg-none border-bottom border-opacity-25 border-light mb-3">
      <h5 class="offcanvas-title fw-bold text-white text-uppercase" id="menuLateralLabel">Distribuidora CFA</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#menuLateral" aria-label="Fechar"></button>
    </div>
    
    <div class="offcanvas-body d-flex flex-column flex-grow-1 p-0">
      <a href="../index.php"
        class="d-none d-lg-flex align-items-center mb-4 text-white text-decoration-none border-bottom pb-3 border-opacity-25"
        style="border-color: #fff;">
        <span class="fs-4 fw-bold text-uppercase ms-3">Distribuidora CFA</span>
      </a>

    <ul class="nav nav-pills flex-column mb-auto gap-2">
      <li class="nav-item">
        <a href="../Medicamento/index.php" class="nav-link">
          <span class="fs-5">💊</span> Medicamentos
        </a>
      </li>
      <li class="nav-item">
        <a href="../index.php" class="nav-link">
          <span class="fs-5">👥</span> Funcionários
        </a>
      </li>
      <li class="nav-item">
        <a href="../Laboratorio/index.php" class="nav-link">
          <span class="fs-5">🔬</span> Laboratórios
        </a>
      </li>
      <li class="nav-item">
        <a href="../drogaria/index.php" class="nav-link">
          <span class="fs-5">🏪</span> Drogarias
        </a>
      </li>
      <li class="nav-item">
        <a href="../Compra/index.php" class="nav-link">
          <span class="fs-5">🛒</span> Compras
        </a>
      </li>
      <li class="nav-item">
        <a href="index.php" class="nav-link active" aria-current="page">
          <span class="fs-5">📈</span> Vendas
        </a>
      </li>
    </ul>

      <hr class="border-secondary mt-auto">
      <div class="ph-sidebar-footer">
        <a href="../logout.php" class="ph-btn-exit w-100 mt-2 text-decoration-none">
          <span class="fs-5">⏻</span> Sair do Sistema
        </a>
      </div>
    </div>
  </aside>

  <main class="b5-main p-4 p-md-5">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4 gap-3">
      <div class="d-flex align-items-center gap-3">
        <!-- Botão Hamburger (Aparece apenas em telas < lg) -->
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral" aria-controls="menuLateral">
          <span class="fs-4">☰</span>
        </button>
        <div>
          <h1 class="display-6 fw-bold text-dark m-0" style="color: #1a1c4b !important;">
            <?= $venda->CNPJ_Drog ? 'Continuar Editando NF' : 'Ponto de Venda (PDV) NF' ?> #<?= $nota_fiscal ?>
          </h1>
          <p class="text-secondary mb-0">Selecione medicamentos e finalize a saída.</p>
        </div>
      </div>
      <div>
        <a href="index.php" class="btn btn-outline-secondary px-4 fw-bold shadow-sm m-0">
          ↩ Voltar
        </a>
      </div>
    </div>

    <!-- Alertas -->
    <?php if ($erroVenda): ?>
      <div class="alert alert-danger alert-dismissible fade show fw-bold" role="alert">
        <?= htmlspecialchars($erroVenda) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Grid do Bootstrap: Duas Colunas -->
    <div class="row g-4">

      <!-- COLUNA DA ESQUERDA: MEDICAMENTOS DISPONÍVEIS -->
      <div class="col-12 col-xl-6">
        <div class="card card-pharma h-100">
          <div class="card-body p-4">
            <h4 class="fw-bold mb-4" style="color: #1a1c4b;">📦 Medicamentos em Estoque</h4>

            <form method="POST" class="d-flex gap-2 mb-4 align-items-center">
              <input type="text" name="termo_med" class="form-control" placeholder="Nome do medicamento ou cód..."
                value="<?= htmlspecialchars($_POST['termo_med'] ?? '') ?>">
              <button type="submit" name="pesquisa_med" class="btn btn-pharma-primary px-4 fw-bold">Buscar</button>
              <?php if (isset($_POST['pesquisa_med'])): ?>
                <a href="novaVenda.php?nota_fiscal_saida=<?= $nota_fiscal ?>" class="btn btn-outline-secondary">X</a>
              <?php endif; ?>
            </form>

            <div class="table-responsive" style="max-height: 500px;">
              <table class="table table-hover align-middle">
                <thead class="table-light sticky-top">
                  <tr>
                    <th>Cód</th>
                    <th>Nome</th>
                    <th>Qtd</th>
                    <th>R$ Unidade</th>
                    <th>Ação</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($medicamentos): ?>
                    <?php foreach ($medicamentos as $med): ?>
                      <tr>
                        <td class="fw-bold"><?= htmlspecialchars($med->Cod_Med) ?></td>
                        <td><?= htmlspecialchars($med->Nome_Med) ?></td>
                        <td>
                          <span class="badge bg-secondary"><?= htmlspecialchars($med->Qtd_Med) ?> unid.</span>
                        </td>
                        <td class="text-success fw-bold">R$ <?= number_format($med->Valor_Med, 2, ',', '.') ?></td>
                        <td class="text-center align-middle" style="height: 1px;">
                          <form method="POST" class="m-0" style="display: inline-block; vertical-align: middle;">
                            <div class="d-flex align-items-center gap-2">
                              <input type="hidden" name="cod_med" value="<?= $med->Cod_Med ?>">
                              <input type="number" name="qtd" min="1" max="<?= $med->Qtd_Med ?>" value="1"
                                class="form-control form-control-sm text-center m-0" style="width: 60px;">
                              <button type="submit" name="adicionar" class="btn btn-sm btn-pharma-success fw-bold p-0"
                                style="width: 32px; height: 31px; line-height: 29px; border-radius: 6px;">+</button>
                            </div>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center py-4">Nenhum medicamento encontrado no estoque.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>

      <!-- COLUNA DA DIREITA: CARRINHO E FINALIZAÇÃO -->
      <div class="col-12 col-xl-6">
        <div class="card card-pharma h-100">
          <div class="card-body p-4 d-flex flex-column">
            <h4 class="fw-bold mb-4" style="color: #1a1c4b;">🛒 Carrinho / Itens da NF</h4>

            <div class="table-responsive flex-grow-1" style="max-height: 350px;">
              <table class="table table-sm align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Item</th>
                    <th>Med.</th>
                    <th>Qtd</th>
                    <th>V. Unit</th>
                    <th>V. Total</th>
                    <th>Remover</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($itensAtuais)): ?>
                    <?php foreach ($itensAtuais as $item): ?>
                      <tr>
                        <td>#<?= htmlspecialchars($item->Cod_ItemVenda) ?></td>
                        <td><?= htmlspecialchars($item->Cod_Med) ?></td>
                        <td><?= htmlspecialchars($item->Qtd_ItemVenda) ?></td>
                        <td>R$ <?= number_format($item->Valor_ItemVenda, 2, ',', '.') ?></td>
                        <td class="fw-bold">R$
                          <?= number_format($item->Qtd_ItemVenda * $item->Valor_ItemVenda, 2, ',', '.') ?>
                        </td>
                        <td class="text-center align-middle" style="height: 1px;">
                          <form method="POST" onsubmit="return confirm('Certerza que deseja remover do carrinho?')"
                            class="m-0" style="display: inline-block; vertical-align: middle;">
                            <input type="hidden" name="cod_item_venda" value="<?= $item->Cod_ItemVenda ?>">
                            <button type="submit" name="remover" class="btn btn-sm btn-danger p-0"
                              style="width: 32px; height: 32px; line-height: 30px; border-radius: 6px;">✕</button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center py-5 text-secondary">Ainda não há itens na venda.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <!-- Totalizador -->
            <div class="bg-light p-3 rounded mt-3 mb-4 d-flex justify-content-between align-items-center border">
              <h5 class="mb-0 text-secondary fw-bold">TOTAL A PAGAR:</h5>
              <h3 class="mb-0 text-success fw-bold">R$ <?= number_format($totalVenda ?? 0, 2, ',', '.') ?></h3>
            </div>

            <!-- Form de Fechamento -->
            <form method="POST" class="mt-auto">
              <div class="mb-4">
                <label for="cnpj_drog" class="form-label fw-bold">Selecione a Drogaria Compradora:</label>
                <select name="cnpj_drog" id="cnpj_drog" class="form-select form-select-lg" required
                  onchange="verificarFinalizar()">
                  <option value="">Clique para selecionar...</option>
                  <?php if ($drogarias): ?>
                    <?php foreach ($drogarias as $drog): ?>
                      <option value="<?= htmlspecialchars($drog->CNPJ_Drog) ?>" <?= (isset($venda->CNPJ_Drog) && $venda->CNPJ_Drog == $drog->CNPJ_Drog) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($drog->Nome_Drog) ?> (CNPJ: <?= htmlspecialchars($drog->CNPJ_Drog) ?>)
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
                <div class="form-text">Faturamento vinculado ao CNPJ destino.</div>
              </div>

              <div class="d-flex flex-wrap gap-2 justify-content-end align-items-center">
                <button type="submit" name="cancelar" class="btn btn-danger px-4 fw-bold shadow-sm" formnovalidate
                  onclick="return confirm('Isso excluirá a venda e esvaziará o carrinho. Confirmar?')">Cancelar
                  Venda</button>
                <button type="submit" name="salvar" class="btn btn-warning px-4 fw-bold shadow-sm" formnovalidate>Salvar
                  Rascunho</button>
                <button type="submit" name="finalizar" id="btn_finalizar"
                  class="btn btn-pharma-success px-4 fw-bold shadow-sm" disabled>✔ Finalizar Pagamento</button>
              </div>
            </form>

          </div>
        </div>
      </div>

    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function verificarFinalizar() {
      var select = document.getElementById('cnpj_drog');
      var btn = document.getElementById('btn_finalizar');
      var temItens = <?= !empty($itensAtuais) ? 'true' : 'false' ?>;
      btn.disabled = (!temItens || select.value === "");
    }
    window.onload = verificarFinalizar;
  </script>
</body>

</html>