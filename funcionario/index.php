<?php

include_once "../Objetos/funcionarioController.php";

$controller = new funcionarioController();

$funcionarios = $controller->index();
global $funcionarios;
$a =null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["pesquisar"])){
        $a = $controller->pesquisafuncionarios($_POST["pesquisar"]);
    }
}
if($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["excluir"])) {
        $a = $controller->excluirfuncionarios($_GET["excluir"]);
    }
}
//var_dump($funcionarios);
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Funcionários</title>
    <style>
        table,tr,td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<h1>Página dos Funcionários</h1>
<a href="cadastro.php">Cadastrar funcionario</a>

<h3>Pesquisar funcionario</h3>
<form method="POST" action="index.php">
    <label>CPF</label>
    <input type="number" name="pesquisar">
    <button>Pesquisar</button>
</form>
<table>
    <tr>
        <td>Nome</td>
        <td>CPF</td>
        <td>Telefone</td>
        <td>Cep</td>
        <td>Numero</td>
        <td>Email</td>
        <td>Senha</td>
    </tr>
    <?php if($funcionarios) : ?>
    <?php foreach($funcionarios as $funcionario) : ?>
    <tr>
        <td><?= $funcionario->Nome_Fun ?></td>
        <td><?= $funcionario->CPF ?></td>
        <td><?= $funcionario->Telefone_Fun ?></td>
        <td><?= $funcionario->Cep_Fun ?></td>
        <td><?= $funcionario->Num_Fun ?></td>
        <td><?= $funcionario->Email_Fun ?></td>
        <td><?= $funcionario->Senha_Fun ?></td>

        <td><a href="atualizar.php?alterar=<?= $funcionario->CPF ?>">Alterar</a></td>
        <td><a href="index.php?excluir=<?= $funcionario->CPF ?>">Excluir</a></td>
    </tr>

    <?php endforeach ?>
    <?php endif; ?>
</table>

</body>
</html>


