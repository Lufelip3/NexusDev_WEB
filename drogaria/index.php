<?php
include_once("../Objetos/drogariaController.php");

$controller = new DrogariaController();
$drogaria = $controller->index();
global $drogaria;
$a = null;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["pesquisar"])){
        $a = $controller->pesquisarDrogaria($_POST["pesquisar"]);
    }
}

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET["excluir"])){
        $controller->excluirDrogaria($_GET["excluir"]);
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" >
    <title>NexusDev</title>
    <style>
        table,tr,td{
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>

<h1>NexusDev</h1>
<a href="../index.php">Voltar</a><br>
<a href="cadastro.php">Cadastrar Drogaria</a><br>
<a href="excluidos.php">Ver Excluidos</a>

<h3>Pesquisar Drogaria</h3>
<form method="POST" action="index.php">
    <label>CNPJ</label>
    <input Type="number" name="pesquisar">
    <button>Pesquisar</button>
</form>

<table>
    <tr>
        <td>CNPJ</td>
        <td>Nome</td>
    </tr>

    <?php if($a) : ?>
        <tr>
            <td><?= $a->cnpj; ?></td>
            <td><?= $a->nome; ?></td>
        </tr>
    <?php endif; ?>

</table>
<h2>Laboratórios Cadastrados</h2>

<table>
        <tr>
            <td>CNPJ</td>
            <td>Nome</td>
            <td>E-mail</td>
            <td>Telefone</td>
            <td>CEP</td>
            <td>Numero</td>
            <td>Foto</td>
        </tr>
    <?php if($drogaria) : ?>
    <?php foreach($drogaria as $drog) : ?>

    <tr>
        <td><?= $drog->CNPJ_Drog; ?></td>
        <td><?= $drog->Nome_Drog; ?></td>
        <td><?= $drog->Email_Drog; ?></td>
        <td><?= $drog->Telefone_Drog; ?></td>
        <td><?= $drog->Cep_Drog; ?></td>
        <td><?= $drog->Num_Drog; ?></td>
        <td>
            <?php if(isset($drog->Foto_Drog) && $drog->Foto_Drog): ?>
                <img src="../uploads/drogarias/<?= $drog->Foto_Drog ?>" width="50" height="50">
            <?php else: ?>
                Sem foto
            <?php endif; ?>
        </td>

        <td><a href="atualizar.php?alterar=<?= $drog->CNPJ_Drog; ?>">Alterar</a> </td>
        <td><a href="index.php?excluir=<?= $drog->CNPJ_Drog; ?>">Excluir</a> </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
</table>

</body>
</html>
