<?php
include_once("../Objetos/funcionarioController.php");

$controller = new funcionarioController();
$a = null;

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['alterar'])){
    $a = $controller->localizarFuncionario($_GET['alterar']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['funcionario'])){
    $controller->atualizarFuncionario($_POST['funcionario'], $_FILES['funcionario'] ?? null);
    header("location: index.php");
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
    <form action="atualizar.php" method="post" enctype="multipart/form-data">

        <label>Nome</label>
        <input type="text" name="funcionario[nome]" value="<?= htmlspecialchars($a->Nome_Fun) ?>"><br><br>

        <input type="hidden" name="funcionario[CPF]" value="<?= htmlspecialchars($a->CPF) ?>">

        <label>Telefone</label>
        <input type="tel" name="funcionario[telefone]" value="<?= htmlspecialchars($a->Telefone_Fun) ?>"><br><br>

        <label>CEP</label>
        <input type="text" name="funcionario[CEP]" value="<?= htmlspecialchars($a->Cep_Fun) ?>"><br><br>

        <label>Número</label>
        <input type="number" name="funcionario[numero]" value="<?= htmlspecialchars($a->Num_Fun) ?>"><br><br>

        <label>E-mail</label>
        <input type="email" name="funcionario[email]" value="<?= htmlspecialchars($a->Email_Fun) ?>"><br><br>

        <label>Senha</label>
        <input type="password" name="funcionario[senha]" placeholder="Digite nova senha ou repita a atual"><br><br>

        <label for="funcao">Função</label>
        <select name="funcionario[funcao]" id="funcao">
            <option value="Usuario"       <?= ($a->Funcao) === 'Usuario'       ? 'selected' : '' ?>>Usuário</option>
            <option value="Administrador" <?= ($a->Funcao) === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
        </select>
        <br><br>

        <label for="foto">Foto de Perfil:</label><br>
        <?php if($a->imagem): ?>
            <img src="../uploads/funcionarios/<?= $a->imagem ?>" width="100"><br>
        <?php endif; ?>
        <input type="file" name="funcionario[fileToUpload]" id="foto"><br><br>

        <button name="atualizar">Atualizar</button>
    </form>
<?php else: ?>
    <p>Funcionário não encontrado.</p>
<?php endif; ?>

</body>
</html>