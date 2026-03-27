<?php
include_once("../Objetos/funcionarioController.php");

session_start();
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);


if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $controller = new funcionarioController();

    if (isset($_POST["cadastrar"])) {
        $a = $controller->cadastrarfuncionario($_POST["funcionario"], $_FILES["funcionario"]);
    }
}
//var_dump($cadastro);
?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Funcionário</title>
</head>
<body>
<h1>Cadastro de Funcionário</h1>
<a href="index.php">Voltar</a><br><br>

<?php

if (isset($_SESSION["erro"])) {
    echo $_SESSION["erro"];
    unset($_SESSION["erro"]);
}

?>

<form action="cadastro.php" method="post" enctype="multipart/form-data">

    <label>Nome:</label><br>
    <input type="text" name="funcionario[nome]"
           value="<?= htmlspecialchars($form_data['nome'] ?? '') ?>" required><br><br>

    <label>CPF:</label><br>
    <input type="text" name="funcionario[cpf]"
           value="<?= htmlspecialchars($form_data['cpf'] ?? '') ?>" required><br><br>

    <label>Telefone:</label><br>
    <input type="tel" name="funcionario[telefone]"
           value="<?= htmlspecialchars($form_data['telefone'] ?? '') ?>"><br><br>

    <label>CEP:</label><br>
    <input type="text" name="funcionario[cep]"
           value="<?= htmlspecialchars($form_data['cep'] ?? '') ?>"><br><br>

    <label>Número:</label><br>
    <input type="text" name="funcionario[numero]"
           value="<?= htmlspecialchars($form_data['numero'] ?? '') ?>"><br><br>

    <label>E-mail:</label><br>
    <input type="email" name="funcionario[email]"
           value="<?= htmlspecialchars($form_data['email'] ?? '') ?>"><br><br>

    <label>Senha:</label><br>
    <input type="password" name="funcionario[senha]"><br><br>

    <label for="funcao">Função</label>
    <select name="funcionario[funcao]" id="funcao">
        <option value="Usuario"       <?= ($form_data['funcao'] ?? '') === 'Usuario'       ? 'selected' : '' ?>>Usuário</option>
        <option value="Administrador" <?= ($form_data['funcao'] ?? '') === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
    </select>
<br><br>
    <label for="foto">Foto de Perfil:</label><br>
    <input type="file" name="funcionario[fileToUpload]" id="foto"><br><br>

    <button type="submit" name="cadastrar">Cadastrar</button>
</form>
</body>
</html>
