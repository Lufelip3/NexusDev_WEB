<?php
include_once("../Objetos/drogariaController.php");

$controller = new drogariaController();
$a = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["alterar"])) {
    $a = $controller->localizarDrogaria($_GET["alterar"]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["atualizar"])) {
    $controller->atualizarDrogaria($_POST["drogaria"], $_FILES["drogaria"] ?? null);
} else {
    header("Location: index.php");
    exit();
}
<<<<<<< HEAD
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atualizar Drogaria</title>
</head>
<body>
    <h1>Atualizar Drogaria</h1>
    <a href="index.php">Voltar</a>

    <?php if($a): ?>
    <form action="atualizar.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="drogaria[CNPJ_Drog]" value="<?= $a["CNPJ_Drog"] ?>">

        <label>Nome</label>
        <input type="text" name="drogaria[Nome_Drog]" value="<?= htmlspecialchars($a["Nome_Drog"]) ?>"><br><br>

        <label>Email</label>
        <input type="email" name="drogaria[Email_Drog]" value="<?= htmlspecialchars($a["Email_Drog"]) ?>"><br><br>

        <label>Telefone</label>
        <input type="text" name="drogaria[Telefone_Drog]" value="<?= htmlspecialchars($a["Telefone_Drog"]) ?>"><br><br>

        <label>CEP</label>
        <input type="text" name="drogaria[Cep_Drog]" value="<?= htmlspecialchars($a["Cep_Drog"]) ?>"><br><br>

        <label>Número</label>
        <input type="number" name="drogaria[Num_Drog]" value="<?= htmlspecialchars($a["Num_Drog"]) ?>"><br><br>

        <label>Foto da Drogaria</label><br>
        <?php if(isset($a['Foto_Drog']) && $a['Foto_Drog']): ?>
            <img src="../uploads/drogarias/<?= $a['Foto_Drog'] ?>" width="100"><br>
        <?php endif; ?>
        <input type="file" name="drogaria[Foto_Drog]"><br><br>

        <button name="atualizar">Atualizar</button>
    </form>
    <?php else: ?>
        <p>Drogaria não encontrada.</p>
    <?php endif; ?>
=======

if (!$a) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Atualizar dados da drogaria – PharmaPulse ERP">
  <title>Editar Drogaria – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .ph-form-wrapper {
      padding: 32px 16px;
      max-width: 800px;
      margin: 0 auto;
      animation: fadeIn 0.5s ease-out forwards;
    }
    .ph-form-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.06);
      border: 1px solid rgba(226, 232, 240, 0.8);
      position: relative;
      overflow: hidden;
    }
    .ph-form-card::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; height: 6px;
      background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
    }
    .ph-form-header { margin-bottom: 32px; }
    .ph-form-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 8px;
      font-family: 'Inter', sans-serif;
    }
    .ph-form-subtitle { color: #64748b; font-size: 0.95rem; }
    .ph-form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }
    .ph-input-group { display: flex; flex-direction: column; gap: 8px; }
    .ph-input-group.full { grid-column: 1 / -1; }
    .ph-label {
      font-weight: 600;
      color: #334155;
      font-size: 0.9rem;
      font-family: 'Inter', sans-serif;
    }
    .ph-input {
      padding: 14px 16px;
      border: 1px solid #cbd5e1;
      border-radius: 12px;
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      color: #0f172a;
      background: #f8fafc;
      transition: all 0.3s ease;
    }
    .ph-input:focus {
      outline: none;
      border-color: #3b82f6;
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .ph-input:disabled { opacity: 0.6; cursor: not-allowed; }
    .ph-form-footer {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 16px;
      margin-top: 40px;
      padding-top: 24px;
      border-top: 1px solid #e2e8f0;
    }
    .ph-btn-primary,
    .ph-btn-secondary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 180px;
      height: 48px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      text-decoration: none;
      cursor: pointer;
      border: none;
      transition: all 0.3s ease;
    }
    .ph-btn-primary {
      color: #ffffff;
      background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
    }
    .ph-btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }
    .ph-btn-secondary {
      color: #475569;
      background: #f1f5f9;
    }
    .ph-btn-secondary:hover { background: #e2e8f0; color: #1e293b; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @media (max-width: 768px) {
      .ph-form-grid { grid-template-columns: 1fr; }
      .ph-form-card { padding: 24px; }
    }
  </style>
</head>
<body class="pharma-app">

  <!-- ═══ SIDEBAR ══════════════════════════════ -->
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
      <a href="../laboratorio/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">🔬</span>
        <span class="ph-nav-label">Laboratórios</span>
      </a>
      <a href="index.php" class="ph-nav-item ph-nav-active">
        <span class="ph-nav-icon">🏪</span>
        <span class="ph-nav-label">Drogarias</span>
      </a>
      <a href="../Compra/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">🛒</span>
        <span class="ph-nav-label">Compras</span>
      </a>
      <a href="../Venda/index.php" class="ph-nav-item">
        <span class="ph-nav-icon">📈</span>
        <span class="ph-nav-label">Vendas</span>
      </a>
    </nav>
    <div class="ph-sidebar-footer">
      <a href="../index.php" class="ph-btn-exit"><span>⏻</span> Sair</a>
    </div>
  </aside>

  <!-- ═══ MAIN ══════════════════════════════════ -->
  <div class="ph-main" id="ph-main">

    <header class="ph-topbar">
      <div class="ph-topbar-left">
        <button class="ph-hamburger" id="ph-hamburger" aria-label="Abrir menu">
          <span></span><span></span><span></span>
        </button>
        <div class="ph-topbar-titles">
          <h1 class="ph-page-title">Editar Drogaria</h1>
          <p class="ph-page-subtitle">Atualize os dados cadastrais desta unidade</p>
        </div>
      </div>
      <div class="ph-topbar-right">
        <div class="ph-avatar" title="Perfil do usuário">A</div>
      </div>
    </header>

    <div class="ph-content">
      <div class="ph-form-wrapper">
        <div class="ph-form-card">

          <div class="ph-form-header">
            <h2 class="ph-form-title">Informações da Drogaria</h2>
            <p class="ph-form-subtitle">Edite os campos abaixo e salve para atualizar o registro no ERP.</p>
          </div>

          <form action="atualizar.php" method="post">
            <input type="hidden" name="drogaria[CNPJ_Drog]" value="<?= htmlspecialchars($a["CNPJ_Drog"]) ?>">

            <div class="ph-form-grid">

              <div class="ph-input-group full">
                <label class="ph-label" for="nome_drog">Nome da Drogaria</label>
                <input type="text" id="nome_drog" name="drogaria[Nome_Drog]" class="ph-input"
                       value="<?= htmlspecialchars($a["Nome_Drog"]) ?>" required>
              </div>

              <div class="ph-input-group">
                <label class="ph-label" for="cnpj_drog">CNPJ</label>
                <input type="text" id="cnpj_drog" class="ph-input"
                       value="<?= htmlspecialchars($a["CNPJ_Drog"]) ?>" disabled
                       title="O CNPJ não pode ser alterado">
              </div>

              <div class="ph-input-group">
                <label class="ph-label" for="telefone_drog">Telefone de Contato</label>
                <input type="text" id="telefone_drog" name="drogaria[Telefone_Drog]" class="ph-input"
                       value="<?= htmlspecialchars($a["Telefone_Drog"]) ?>">
              </div>

              <div class="ph-input-group full">
                <label class="ph-label" for="email_drog">E-mail Comercial</label>
                <input type="email" id="email_drog" name="drogaria[Email_Drog]" class="ph-input"
                       value="<?= htmlspecialchars($a["Email_Drog"]) ?>" required>
              </div>

              <div class="ph-input-group">
                <label class="ph-label" for="cep_drog">CEP</label>
                <input type="text" id="cep_drog" name="drogaria[Cep_Drog]" class="ph-input"
                       value="<?= htmlspecialchars($a["Cep_Drog"]) ?>">
              </div>

              <div class="ph-input-group">
                <label class="ph-label" for="num_drog">Número (Endereço)</label>
                <input type="text" id="num_drog" name="drogaria[Num_Drog]" class="ph-input"
                       value="<?= htmlspecialchars($a["Num_Drog"]) ?>">
              </div>

            </div>

            <div class="ph-form-footer">
              <a href="index.php" class="ph-btn-secondary">Cancelar</a>
              <input type="submit" name="atualizar" class="ph-btn-primary" value="Salvar Alterações">
            </div>
          </form>

        </div>
      </div>
    </div><!-- .ph-content -->

    <footer class="ph-footer">
      <p>Desenvolvido por <strong>NexusDev</strong> &copy; 2026</p>
    </footer>
  </div><!-- .ph-main -->

  <a href="cadastro.php" class="ph-fab" title="Nova Drogaria">+</a>

  <nav class="ph-bottom-nav" aria-label="Navegação principal">
    <a href="../index.php" class="ph-bottom-item">
      <span class="ph-bottom-icon">🏠</span>
      <span class="ph-bottom-label">Home</span>
    </a>
    <a href="index.php" class="ph-bottom-item ph-bottom-active">
      <span class="ph-bottom-icon">🏪</span>
      <span class="ph-bottom-label">Drogarias</span>
    </a>
    <a href="#" class="ph-bottom-item">
      <span class="ph-bottom-icon">📷</span>
      <span class="ph-bottom-label">Scan</span>
    </a>
    <a href="#" class="ph-bottom-item">
      <span class="ph-bottom-icon">📊</span>
      <span class="ph-bottom-label">Reports</span>
    </a>
  </nav>

  <script>
    const hamburger = document.getElementById('ph-hamburger');
    const sidebar   = document.getElementById('ph-sidebar');

    hamburger.addEventListener('click', () => {
      sidebar.classList.toggle('ph-sidebar--open');
    });
    document.addEventListener('click', (e) => {
      if (
        sidebar.classList.contains('ph-sidebar--open') &&
        !sidebar.contains(e.target) &&
        !hamburger.contains(e.target)
      ) {
        sidebar.classList.remove('ph-sidebar--open');
      }
    });
  </script>
>>>>>>> c10428bab887fe52fce0ce24eca8f8fc24d3f195

</body>
</html>