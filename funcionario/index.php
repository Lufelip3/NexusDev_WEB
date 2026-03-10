<?php

include_once "../Objetos/funcionarioController.php";

$controller = new funcionarioController();

$funcionarios = $controller->index();

//var_dump($funcionarios);

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Funcionários</title>
</head>
<body>

<h1>Página dos Funcionários</h1>

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
        <td><?= $funcionario->Nome_Fun?></td>
        <td><?= $funcionario->CPF?></td>
        <td><?= $funcionario->Telefone_Fun?></td>
        <td><?= $funcionario->Cep_Fun?></td>
        <td><?= $funcionario->Num_Fun?></td>
        <td><?= $funcionario->Email_Fun?></td>
        <td><?= $funcionario->Senha_Fun?></td>
    </tr>

    <?php endforeach ?>
    <?php endif; ?>
</table>

</body>
</html>
