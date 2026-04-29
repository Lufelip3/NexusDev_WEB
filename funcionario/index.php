<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once("../Objetos/funcionarioController.php");

if (!isset($_SESSION["login"])){
    header("location: ../login.php");
    exit();
}

$controller   = new FuncionarioController();
$funcionarios = $controller->index();
$resultados   = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["pesquisar"]) && $_POST["pesquisar"] !== '') {
        $tipo = $_POST["tipo_busca"] ?? 'cpf';
        $resultados = $controller->pesquisarFuncionarioPorTipo($tipo, $_POST["pesquisar"]);
    }
}

if($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["excluir"])) {
        $controller->excluirFuncionario($_GET["excluir"]);
        header("Location: index.php");
        exit();
    }
}

// Função funcInitials removida conforme solicitação
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Funcionários – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    /* Funcionário: ícone gradiente navy */
    .ph-lab-icon-wrap.func-icon {
      background: linear-gradient(135deg, #1a1c4b 0%, #2c2f8a 100%);
    }
    .ph-lab-card.func-card { border-left-color: #2c2f8a; }
  </style>
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
        <li class="nav-item"><a href="index.php" class="nav-link active" aria-current="page"><span class="fs-5">👥</span> Funcionários</a></li>
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

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4 gap-3">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"><span class="fs-4">☰</span></button>
        <div>
          <h1 class="display-6 fw-bold m-0" style="color:#1a1c4b;">Funcionários</h1>
          <p class="text-secondary mb-0">Gerenciamento de equipe e acessos.</p>
        </div>
      </div>
      <a href="cadastro.php" class="btn btn-pharma-success fw-bold shadow-sm px-4">+ Novo Funcionário</a>
    </div>

    <!-- Bloco de busca — com select tipo (Nome / CPF) igual ao de laboratórios -->
    <div class="card card-pharma mb-4">
      <div class="card-body p-4">
        <form method="POST" action="index.php" class="row g-3 align-items-end">
          <div class="col-md-3">
            <label for="tipo_busca" class="form-label fw-bold">Buscar por</label>
            <select name="tipo_busca" id="tipo_busca" class="form-select">
              <option value="cpf"  <?= (isset($_POST['tipo_busca']) && $_POST['tipo_busca'] === 'cpf')  ? 'selected' : '' ?>>CPF</option>
              <option value="nome" <?= (isset($_POST['tipo_busca']) && $_POST['tipo_busca'] === 'nome') ? 'selected' : '' ?>>Nome</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="pesquisar" class="form-label fw-bold">Termo</label>
            <input type="text" id="pesquisar" name="pesquisar" class="form-control"
                   placeholder="Digite o CPF ou nome..."
                   value="<?= htmlspecialchars($_POST['pesquisar'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-pharma-primary w-100 fw-bold">Filtrar</button>
          </div>
        </form>

        <?php if (isset($resultados)): ?>
          <?php if (count($resultados) > 0): ?>
          <hr class="my-4">
          <h5 class="fw-bold mb-3 text-success">Resultados encontrados (<?= count($resultados) ?>):</h5>
          <div class="ph-lab-list">
            <?php foreach ($resultados as $res): ?>
            <div class="ph-lab-card func-card">
              <div class="ph-lab-icon-wrap func-icon" <?= empty($res->imagem) ? 'style="background:transparent;"' : '' ?>>
                <?php if (!empty($res->imagem)): ?>
                  <img src="../uploads/funcionarios/<?= htmlspecialchars($res->imagem) ?>" alt="Foto" style="width: 46px; height: 46px; border-radius: var(--ph-radius-sm); object-fit: cover;">
                <?php else: ?>
                  <span style="font-size:2rem;">👥</span>
                <?php endif; ?>
              </div>
              <div class="ph-lab-body">
                <strong><?= htmlspecialchars($res->Nome_Fun ?? '') ?></strong>
                <span>🪪 <?= htmlspecialchars($res->CPF ?? '') ?></span>
                <span>✉ <?= htmlspecialchars($res->Email_Fun ?? '') ?></span>
                <span>📞 <?= htmlspecialchars($res->Telefone_Fun ?? '') ?></span>
              </div>
              <div class="ph-lab-actions">
                <span class="ph-badge--active">● Ativo</span>
                <div class="ph-card-btns">
                  <a href="atualizar.php?alterar=<?= urlencode($res->CPF) ?>" class="ph-btn--edit">✏ Editar</a>
                  <a href="index.php?excluir=<?= urlencode($res->CPF) ?>" class="ph-btn--delete" onclick="return confirm('Excluir este funcionário?')">🗑</a>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php else: ?>
          <hr class="my-4">
          <p class="text-secondary mb-0">Nenhum resultado encontrado.</p>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Indicador -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card border-0 bg-white shadow-sm" style="border-left: 4px solid #2c2f8a !important;">
          <div class="card-body">
            <h6 class="text-secondary mb-1">Funcionários Ativos</h6>
            <h2 class="fw-bold mb-0" style="color:#1a1c4b;"><?= $funcionarios ? count($funcionarios) : 0 ?></h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Lista em Cartões -->
    <h3 class="fw-bold mb-3" style="color:#1a1c4b;">Equipe Cadastrada</h3>

    <?php if ($funcionarios): ?>
    <div class="ph-lab-list">
      <?php foreach ($funcionarios as $funcionario): ?>
      <div class="ph-lab-card func-card">
        <!-- Foto / Avatar -->
        <div class="ph-lab-icon-wrap func-icon" <?= empty($funcionario->imagem) ? 'style="background:transparent;"' : '' ?>>
          <?php if (!empty($funcionario->imagem)): ?>
            <img src="../uploads/funcionarios/<?= htmlspecialchars($funcionario->imagem) ?>" alt="Foto" style="width: 46px; height: 46px; border-radius: var(--ph-radius-sm); object-fit: cover;">
          <?php else: ?>
            <span style="font-size:2rem;">👥</span>
          <?php endif; ?>
        </div>

        <!-- Dados -->
        <div class="ph-lab-body">
          <strong><?= htmlspecialchars($funcionario->Nome_Fun) ?></strong>
          <span>🪪 <?= htmlspecialchars($funcionario->CPF) ?></span>
          <span>✉ <?= htmlspecialchars($funcionario->Email_Fun) ?></span>
          <span>📞 <?= htmlspecialchars($funcionario->Telefone_Fun) ?></span>
        </div>

        <!-- Ações -->
        <div class="ph-lab-actions">
          <span class="ph-badge--active">● Ativo</span>
          <div class="ph-card-btns">
            <a href="atualizar.php?alterar=<?= urlencode($funcionario->CPF) ?>" class="ph-btn--edit">✏ Editar</a>
            <a href="index.php?excluir=<?= urlencode($funcionario->CPF) ?>" class="ph-btn--delete" onclick="return confirm('Deseja excluir este funcionário?')">🗑</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="card card-pharma p-5 text-center">
      <p class="text-secondary fs-5 mb-0">Nenhum funcionário cadastrado.</p>
    </div>
    <?php endif; ?>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
