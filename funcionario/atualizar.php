<?php
include_once("../Objetos/funcionarioController.php");

$controller = new funcionarioController();
$a = null;

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['alterar'])){
    $a = $controller->localizarfuncionario($_GET['alterar']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['funcionario'])){
    $controller->atualizarfuncionario($_POST['funcionario']);
    header("location: index.php"); // Redireciona após salvar
    exit();
} else {
    header("location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro Funcionário</title>
</head>
<body>
<h1>Cadastro Funcionário</h1>
<a href="index.php">Voltar</a><br><br>

<?php if($a): ?>
    <form action="atualizar.php" method="post">

        <label>Nome</label>
        <input type="text" name="funcionario[nome]" value="<?= htmlspecialchars($a->nome) ?>"><br><br>

        <input type="hidden" name="funcionario[CPF]" value="<?= htmlspecialchars($a->CPF) ?>">

        <label>Telefone</label>
        <input type="tel" name="funcionario[telefone]" value="<?= htmlspecialchars($a->telefone) ?>"><br><br>

        <label>CEP</label>
        <input type="text" name="funcionario[CEP]" value="<?= htmlspecialchars($a->CEP) ?>"><br><br>

        <label>Número</label>
        <input type="number" name="funcionario[numero]" value="<?= htmlspecialchars($a->numero) ?>"><br><br>

        <label>E-mail</label>
        <input type="email" name="funcionario[email]" value="<?= htmlspecialchars($a->email) ?>"><br><br>

        <label>Senha</label>
        <input type="password" name="funcionario[senha]" value="<?= htmlspecialchars($a->senha) ?>"><br><br>

        <button name="atualizar">Atualizar</button>
    </form>
<?php else: ?>
    <p>Funcionário não encontrado.</p>
<?php endif; ?>

</body>
</html>