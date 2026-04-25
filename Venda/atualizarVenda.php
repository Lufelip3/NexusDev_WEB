<?php
include_once("../Objetos/vendaController.php");
$controller = new VendaController();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["alterar"])) {
    $a = $controller->localizarVenda($_GET["alterar"]);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["venda"])) {
    $controller->atualizarVenda($_POST["venda"]);
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Atualização de Venda – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <style>
      .form-container {
          background: #fff;
          padding: 30px;
          border-radius: 8px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.05);
          max-width: 500px;
          margin: 0 auto;
      }
      .form-group {
          margin-bottom: 20px;
      }
      .form-group label {
          display: block;
          margin-bottom: 8px;
          font-weight: 600;
          color: #333;
      }
      .form-control {
          width: 100%;
          padding: 10px 12px;
          border: 1px solid #ccc;
          border-radius: 6px;
          font-family: inherit;
      }
      .btn-submit {
          background: #1a73e8;
          color: #fff;
          border: none;
          padding: 12px 20px;
          border-radius: 6px;
          font-weight: 600;
          cursor: pointer;
          width: 100%;
      }
      .btn-submit:hover {
          background: #1557b0;
      }
  </style>
</head>
<body class="pharma-app">
  <aside class="ph-sidebar" id="ph-sidebar">
    <div class="ph-sidebar-brand">
      <img src="../cfa_logo.png" alt="Logo CFA" class="ph-brand-logo">
      <span class="ph-brand-name">Distribuidora<br>CFA Ltda.</span>
    </div>
    <nav class="ph-sidebar-nav">
      <a href="../Medicamento/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">💊</span>
        <span class="ph-nav-label">Medicamentos</span>
      </a>
      <a href="../index.php" class="ph-nav-item">
        <span class="ph-nav-icon">👥</span>
        <span class="ph-nav-label">Funcionários</span>
      </a>
      <a href="../Laboratorio/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">🔬</span>
        <span class="ph-nav-label">Laboratórios</span>
      </a>
      <a href="../drogaria/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">🏪</span>
        <span class="ph-nav-label">Drogarias</span>
      </a>
      <a href="../Compra/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">🛒</span>
        <span class="ph-nav-label">Compras</span>
      </a>
      <a href="../Venda/index.php" class="ph-nav-item ph-nav-active">
        <span class="ph-nav-icon">📈</span>
        <span class="ph-nav-label">Vendas</span>
      </a>
    </nav>
    <div class="ph-sidebar-footer">
      <a href="../logout.php" class="ph-btn-exit">
        <span>⏻</span> Sair
      </a>
    </div>
  </aside>

  <div class="ph-main" id="ph-main">
    <header class="ph-topbar">
      <div class="ph-topbar-left">
        <button class="ph-hamburger" id="ph-hamburger" aria-label="Abrir menu">
          <span></span><span></span><span></span>
        </button>
        <div class="ph-topbar-titles">
          <h1 class="ph-page-title">Atualização de Venda</h1>
          <p class="ph-page-subtitle">Modificação manual de registro de venda</p>
        </div>
      </div>
    </header>

    <div class="ph-content">
      <div style="margin-bottom: 20px;">
        <a href="index.php" class="ph-action-btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
           <span>⬅</span> Voltar
        </a>
      </div>
      
      <div class="form-container">
          <form action="atualizarVenda.php" method="post">
              <input type="hidden" name="venda[NotaFiscal_Saida]" value="<?= htmlspecialchars($a->NotaFiscal_Saida) ?>">
              
              <div class="form-group">
                  <label>Data da Venda</label>
                  <input type="date" name="venda[Data_Venda]" class="form-control" value="<?= htmlspecialchars($a->Data_Venda) ?>" required>
              </div>

              <div class="form-group">
                  <label>Valor da Venda</label>
                  <input type="number" step="0.01" name="venda[Valor_Venda]" class="form-control" value="<?= htmlspecialchars($a->Valor_Venda) ?>" required>
              </div>

              <div class="form-group">
                  <label>CNPJ Drogaria</label>
                  <input type="text" name="venda[CNPJ_Drog]" class="form-control" value="<?= htmlspecialchars($a->CNPJ_Drog) ?>">
              </div>

              <div class="form-group">
                  <label>CPF Cliente</label>
                  <input type="text" name="venda[CPF]" class="form-control" value="<?= htmlspecialchars($a->CPF) ?>" required>
              </div>

              <button type="submit" name="atualizar" class="btn-submit">Atualizar Venda</button>
          </form>
      </div>
    </div>
  </div>

  <script>
    const hamburger = document.getElementById('ph-hamburger');
    const sidebar   = document.getElementById('ph-sidebar');
    hamburger.addEventListener('click', () => { sidebar.classList.toggle('ph-sidebar--open'); });
    document.addEventListener('click', (e) => {
      if (sidebar.classList.contains('ph-sidebar--open') && !sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.remove('ph-sidebar--open');
      }
    });
  </script>
</body>
</html>