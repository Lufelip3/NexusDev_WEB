<?php
include_once ("../objetos/drogariaController.php");

$controller = new drogariaController();
$excluidos = $controller->excluidos();
global $excluidos;

if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["reativar"])){
    $controller->reativarDrogaria($_GET["reativar"]);
}

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laboratórios Excluídos</title>
</head>
<body>
<style>
    table, tr, td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 5px;
    }
</style>

<h1>Laboratórios Excluídos</h1>
<a href="index.php">Voltar</a>

<table>
    <tr>
        <td>CNPJ</td>
        <td>Nome</td>
        <td>Telefone</td>
        <td>Email</td>
        <td>CEP</td>
        <td>Número</td>
        <td>Ação</td>
    </tr>

    <?php if($excluidos) : ?>
        <?php foreach($excluidos as $drog) : ?>
            <tr>
                <td><?= $drog->CNPJ_Drog ?></td>
                <td><?= $drog->Nome_Drog ?></td>
                <td><?= $drog->Telefone_Drog ?></td>
                <td><?= $drog->Email_Drog ?></td>
                <td><?= $drog->Cep_Drog ?></td>
                <td><?= $drog->Num_Drog ?></td>
                <td><a href="excluidos.php?reativar=<?= $drog->CNPJ_Drog ?>">Reativar</a></td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="7">Nenhum drogoratório excluído.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>