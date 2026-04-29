<?php
include_once "Objetos/funcionarioController.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST["login"]) && isset($_POST["senha"])){
        $controller = new funcionarioController();
        $redirect = $_POST["redirect"] ?? "index.php";
        $controller->login($_POST["login"], $_POST["senha"], $redirect);
    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Login no sistema PharmaPulse ERP">
  <title>Login – PharmaPulse</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    body { background: var(--ph-bg); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
  </style>
</head>
<body style="font-family: 'Manrope', sans-serif;">

  <div style="width: 100%; max-width: 420px; padding: 24px;">
    <div class="card card-pharma">
      <div class="p-4 text-white text-center" style="background: linear-gradient(135deg,#1a1c4b 0%,#2c2f8a 100%); border-radius: 12px 12px 0 0;">
        <div style="font-size: 2.5rem; margin-bottom: 8px;">💊</div>
        <h1 class="fw-bold fs-4 mb-1 text-white">PharmaPulse</h1>
        <p class="opacity-75 mb-0 small">Distribuidora CFA — Sistema ERP</p>
      </div>
      <div class="card-body p-4">
        <?php if (isset($erro)): ?>
          <div class="alert alert-danger mb-3"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
          <div class="mb-3">
            <label for="login" class="form-label fw-bold">Login (CPF)</label>
            <input type="text" id="login" name="login" class="form-control" placeholder="Digite seu CPF..." required autofocus>
          </div>
          <div class="mb-4">
            <label for="senha" class="form-label fw-bold">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control" placeholder="••••••••" required>
          </div>
          <button type="submit" class="btn btn-pharma-success w-100 fw-bold py-2 fs-5">Entrar no Sistema</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
