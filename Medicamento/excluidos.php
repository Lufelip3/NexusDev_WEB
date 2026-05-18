<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION["login"])) {
    header("Location: " . (file_exists("login.php") ? "" : "../") . "login.php");
    exit();
}
ob_start();
include_once("../Objetos/medicamentoController.php");

$controller  = new medicamentoController();
$excluidos   = $controller->lerExcluidos();

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["reativar"])) {
    $controller->reativarMedicamento((int)$_GET["reativar"]);
    // reativarMedicamento já redireciona
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medicamentos Excluídos – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="d-flex flex-nowrap" style="font-family: 'Manrope', sans-serif;">

  <aside class="b5-sidebar offcanvas-lg offcanvas-start p-3" tabindex="-1" id="menuLateral" aria-labelledby="menuLateralLabel">
    <div class="offcanvas-header d-lg-none border-bottom border-opacity-25 border-light mb-3">
      <h5 class="offcanvas-title fw-bold text-white text-uppercase" id="menuLateralLabel"><img src="../cfa_logo.png" alt="Distribuidora CFA" class="img-fluid rounded" style="max-height: 70px;"></h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#menuLateral" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column flex-grow-1 p-0">
      <a href="../index.php" class="d-none d-lg-flex align-items-center mb-4 text-white text-decoration-none border-bottom pb-3 border-opacity-25" style="border-color:#fff;">
        <img src="../cfa_logo.png" alt="Distribuidora CFA" class="img-fluid w-100 rounded" style="object-fit: contain;">
      </a>

      <?php include_once __DIR__ . '/../includes/sidebar_user.php'; ?>
      <ul class="nav nav-pills flex-column mb-auto gap-2">
        <li class="nav-item">
          <a href="../index.php" class="nav-link">
            <span class="fs-5">🏠</span> Menu Principal
          </a>
        </li>
        <li class="nav-item"><a href="index.php" class="nav-link active"><span class="fs-5">💊</span> Medicamentos</a></li>
        <?php if (($_SESSION['login']->Funcao ?? '') === 'Administrador'): ?>
          <li class="nav-item"><a href="../funcionario/index.php" class="nav-link"><span class="fs-5">👥</span> Funcionários</a></li>
        <?php endif; ?>
        <li class="nav-item"><a href="../laboratorio/index.php" class="nav-link"><span class="fs-5">🔬</span> Laboratórios</a></li>
        <li class="nav-item"><a href="../drogaria/index.php" class="nav-link"><span class="fs-5">🏪</span> Drogarias</a></li>
        <li class="nav-item"><a href="../Compra/index.php" class="nav-link"><span class="fs-5">🛒</span> Compras</a></li>
        <li class="nav-item"><a href="../Venda/index.php" class="nav-link"><span class="fs-5">📈</span> Vendas</a></li>
      </ul>
      <hr class="border-secondary mt-auto">
      <div class="ph-sidebar-footer">
        <a href="../logout.php" class="ph-btn-exit w-100 mt-2 text-decoration-none"><span class="fs-5">⏻</span> Sair do Sistema</a>
      </div>
    </div>
  </aside>

  <main class="b5-main p-4 p-md-5">
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4 gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
          <span class="fs-4">☰</span>
        </button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">Medicamentos Excluídos</h1>
          <p class="text-secondary mb-0">Itens removidos do estoque ativo — possível reativação.</p>
        </div>
      </div>
      <a href="index.php" class="btn btn-outline-secondary fw-bold px-4">← Voltar ao Estoque</a>
    </div>

    <!-- Indicador -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card border-0 bg-white shadow-sm" style="border-left: 4px solid #dc3545 !important;">
          <div class="card-body">
            <h6 class="text-secondary mb-1">Medicamentos Desativados</h6>
            <h2 class="fw-bold mb-0 text-danger"><?= $excluidos ? count($excluidos) : 0 ?></h2>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-pharma">
      <div class="card-body p-0">
        <?php if ($excluidos): ?>
        <div class="table-responsive">
          <table class="table table-pharma mb-0 align-middle">
            <thead>
              <tr>
                <th class="ps-4">Cód.</th>
                <th>EAN</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Qtd.</th>
                <th>Validade</th>
                <th>Valor</th>
                <th class="text-end pe-4">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($excluidos as $med): ?>
              <tr>
                <td class="ps-4 fw-bold text-secondary">#<?= htmlspecialchars($med->Cod_Med) ?></td>
                <td><span class="badge bg-light text-dark border" style="font-size:.8rem;"><?= htmlspecialchars($med->EAN_Med ?? '—') ?></span></td>
                <td class="fw-bold"><?= htmlspecialchars($med->Nome_Med) ?></td>
                <td class="text-secondary"><?= htmlspecialchars($med->Desc_Med) ?></td>
                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($med->Qtd_Med) ?> un.</span></td>
                <td><?= $med->DataVal_Med ? date('d/m/Y', strtotime($med->DataVal_Med)) : '—' ?></td>
                <td class="fw-bold text-success">R$ <?= number_format($med->Valor_Med, 2, ',', '.') ?></td>
                <td class="text-end pe-4">
                  <a href="#"
                     class="btn btn-sm btn-pharma-success fw-bold"
                     onclick="abrirModalReativar(event, 'excluidos.php?reativar=<?= $med->Cod_Med ?>', '<?= htmlspecialchars(addslashes($med->Nome_Med)) ?>')">♻️ Reativar</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="p-5 text-center">
          <p class="text-secondary fs-5 mb-0">✅ Nenhum medicamento desativado. Tudo limpo!</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <!-- Modal de Confirmação de Reativação -->
  <div class="modal fade" id="modalConfirmarReativacao" tabindex="-1" aria-labelledby="modalReativacaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius:16px; overflow:hidden;">
        <div class="modal-header" style="background:#28a745;">
          <h5 class="modal-title text-white fw-bold" id="modalReativacaoLabel">♻️ Reativar Medicamento</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body p-4">
          <p class="mb-0 fw-bold" id="modalReativacaoMensagem" style="color:#333;"></p>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
          <a href="#" id="modalReativacaoBtnConfirmar" class="btn btn-success px-4 fw-bold">♻️ Confirmar</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function abrirModalReativar(e, url, nome) {
      e.preventDefault();
      document.getElementById('modalReativacaoMensagem').textContent = 'Deseja reativar o medicamento "' + nome + '"? Ele voltará a aparecer no estoque ativo.';
      document.getElementById('modalReativacaoBtnConfirmar').href = url;
      new bootstrap.Modal(document.getElementById('modalConfirmarReativacao')).show();
    }
  </script>
</body>
</html>
<?php ob_end_flush(); ?>
