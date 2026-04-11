<!DOCTYPE html><?php
ob_start();
include_once("../objetos/laboratorioController.php");

$controller = new laboratorioController();
$a = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["alterar"])) {
    $a = $controller->localizarLaboratorio($_GET["alterar"]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["atualizar"])) {
    $controller->atualizarLaboratorio($_POST["laboratorio"], $_FILES);
} else {
    header("Location: index.php");
    exit();
}

if (!$a) {
    header("Location: index.php");
    exit();
}
?>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Atualizar dados do laboratório – PharmaPulse ERP">
  <title>Editar Laboratório – PharmaPulse</title>
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
    .ph-input-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
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
    .ph-photo-preview-wrap {
      display: flex;
      align-items: center;
      gap: 20px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 20px;
    }
    .ph-photo-thumb {
      width: 80px;
      height: 80px;
      border-radius: 14px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #e8f0fe;
      flex-shrink: 0;
      font-size: 2rem;
    }
    .ph-photo-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .ph-photo-info { flex: 1; }
    .ph-photo-name {
      font-weight: 600;
      color: #1e293b;
      font-size: 0.9rem;
      margin-bottom: 4px;
    }
    .ph-photo-hint { font-size: 0.8rem; color: #94a3b8; }
    .ph-file-upload {
      position: relative;
      border: 2px dashed #cbd5e1;
      border-radius: 16px;
      background: #f8fafc;
      padding: 32px 20px;
      text-align: center;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    .ph-file-upload:hover { border-color: #8b5cf6; background: #f5f3ff; }
    .ph-file-upload input[type="file"] {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      opacity: 0; cursor: pointer;
    }
    .ph-file-icon { font-size: 2rem; margin-bottom: 8px; display: block; }
    .ph-file-text { color: #475569; font-weight: 500; font-family: 'Inter', sans-serif; }
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
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      align-self: center;
      width: 180px;
      height: 48px;
      box-sizing: border-box;
      padding: 0;
      margin: 0;
      border: none;
      border-radius: 12px;
      font-weight: 600;
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      line-height: 1;
      white-space: nowrap;
      text-decoration: none;
      cursor: pointer;
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
      <a href="index.php" class="ph-nav-item ph-nav-active">
        <span class="ph-nav-icon">🔬</span>
        <span class="ph-nav-label">Laboratórios</span>
      </a>
      <a href="#" class="ph-nav-item">
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
          <h1 class="ph-page-title">Editar Laboratório</h1>
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
            <h2 class="ph-form-title">Informações do Laboratório</h2>
            <p class="ph-form-subtitle">Edite os campos abaixo e salve para atualizar o registro no ERP.</p>
          </div>

          <form action="atualizar.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="laboratorio[CNPJ_Lab]" value="<?= htmlspecialchars($a['CNPJ_Lab']) ?>">

            <div class="ph-form-grid">

              <div class="ph-input-group full">
                <label class="ph-label" for="nome_lab">Nome do Laboratório</label>
                <input type="text" id="nome_lab" name="laboratorio[Nome_Lab]" class="ph-input"
                       value="<?= htmlspecialchars($a['Nome_Lab']) ?>" required>
              </div>

              <div class="ph-input-group">
                <label class="ph-label" for="cnpj_lab">CNPJ</label>
                <input type="text" id="cnpj_lab" class="ph-input"
                       value="<?= htmlspecialchars($a['CNPJ_Lab']) ?>" disabled
                       title="O CNPJ não pode ser alterado">
              </div>

              <div class="ph-input-group">
                <label class="ph-label" for="telefone_lab">Telefone de Contato</label>
                <input type="text" id="telefone_lab" name="laboratorio[Telefone_Lab]" class="ph-input"
                       value="<?= htmlspecialchars($a['Telefone_Lab']) ?>">
              </div>

              <div class="ph-input-group full">
                <label class="ph-label" for="email_lab">E-mail Comercial</label>
                <input type="email" id="email_lab" name="laboratorio[Email_Lab]" class="ph-input"
                       value="<?= htmlspecialchars($a['Email_Lab']) ?>" required>
              </div>

              <div class="ph-input-group">
                <label class="ph-label" for="cep_lab">CEP</label>
                <input type="text" id="cep_lab" name="laboratorio[Cep_Lab]" class="ph-input"
                       value="<?= htmlspecialchars($a['Cep_Lab']) ?>">
              </div>

              <div class="ph-input-group">
                <label class="ph-label" for="num_lab">Número (Endereço)</label>
                <input type="text" id="num_lab" name="laboratorio[Num_Lab]" class="ph-input"
                       value="<?= htmlspecialchars($a['Num_Lab']) ?>">
              </div>

              <!-- Foto atual + upload novo -->
              <div class="ph-input-group full">
                <label class="ph-label">Foto / Logotipo do Laboratório</label>
                <?php if (!empty($a['Foto_Lab'])) : ?>
                <div class="ph-photo-preview-wrap">
                  <div class="ph-photo-thumb">
                    <img src="../uploads/laboratorios/<?= htmlspecialchars($a['Foto_Lab']) ?>"
                         alt="Foto atual">
                  </div>
                  <div class="ph-photo-info">
                    <p class="ph-photo-name">📷 Foto atual: <?= htmlspecialchars($a['Foto_Lab']) ?></p>
                    <p class="ph-photo-hint">Selecione uma nova imagem abaixo para substituir.</p>
                  </div>
                </div>
                <br>
                <?php endif; ?>
                <div class="ph-file-upload" tabindex="0">
                  <span class="ph-file-icon">📸</span>
                  <span class="ph-file-text" id="file-label">
                    <?= !empty($a['Foto_Lab']) ? 'Clique para substituir a foto atual' : 'Clique ou arraste a imagem aqui para enviar' ?>
                  </span>
                  <input type="file" name="Foto_Lab" id="foto-input" accept="image/*">
                </div>
              </div>

            </div>

            <div class="ph-form-footer">
              <a href="<?= !empty($a['CNPJ_Lab']) ? 'visualizar.php?id='.urlencode($a['CNPJ_Lab']) : 'index.php' ?>" class="ph-btn-secondary">Cancelar</a>
              <input type="submit" name="atualizar" class="ph-btn-primary" id="btn-atualizar" value="Salvar Alterações">
            </div>
          </form>

        </div>
      </div>
    </div><!-- .ph-content -->

    <footer class="ph-footer">
      <p>Desenvolvido por <strong>NexusDev</strong> &copy; 2026</p>
    </footer>
  </div><!-- .ph-main -->

  <!-- FAB -->
  <a href="cadastro.php" class="ph-fab" id="ph-fab" title="Novo Laboratório">+</a>

  <!-- Bottom Nav (mobile) -->
  <nav class="ph-bottom-nav" aria-label="Navegação principal">
    <a href="../index.php" class="ph-bottom-item" id="bn-home">
      <span class="ph-bottom-icon">🏠</span>
      <span class="ph-bottom-label">Home</span>
    </a>
    <a href="index.php" class="ph-bottom-item ph-bottom-active" id="bn-labs">
      <span class="ph-bottom-icon">🔬</span>
      <span class="ph-bottom-label">Labs</span>
    </a>
    <a href="#" class="ph-bottom-item" id="bn-scan">
      <span class="ph-bottom-icon">📷</span>
      <span class="ph-bottom-label">Scan</span>
    </a>
    <a href="#" class="ph-bottom-item" id="bn-reports">
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

    // Preview de arquivo
    const fileInput = document.getElementById('foto-input');
    const fileLabel = document.getElementById('file-label');

    if (fileInput && fileLabel) {
      fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
          fileLabel.textContent = '✔️ ' + e.target.files[0].name;
          fileLabel.style.color = '#3b82f6';
          fileLabel.style.fontWeight = '700';
        } else {
          fileLabel.textContent = 'Clique ou arraste a imagem aqui para enviar';
          fileLabel.style.color = '#475569';
          fileLabel.style.fontWeight = '500';
        }
      });
    }
  </script>

</body>
</html>
<?php ob_end_flush(); ?>
