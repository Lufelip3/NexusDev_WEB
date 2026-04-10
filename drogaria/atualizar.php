<?php
include_once("../objetos/drogariaController.php");

$controller = new drogariaController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["alterar"])) {
    $a = $controller->localizarDrogaria($_GET["alterar"]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["atualizar"])) {
    $controller->atualizarDrogaria($_POST["drogaria"]);
} else {
    header("Location: index.php");
    exit();
}

?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laboratório</title>
</head>

<body>
    <h1>Labortório</h1>
    <a href="index.php">Voltar</a>

    <form action="atualizar.php" method="post">
        <input type="hidden" name="drogaria[CNPJ_Drog]" value="<?= $a["CNPJ_Drog"] ?>">

        <drogel>Nome</drogel>
        <input type="text" name="drogaria[Nome_Drog]" value="<?= $a["Nome_Drog"] ?>"><br><br>

        <drogel>Email</drogel>
        <input type="text" name="drogaria[Email_Drog]" value="<?= $a["Email_Drog"] ?>"><br><br>

        <drogel>Telefone</drogel>
        <input type="text" name="drogaria[Telefone_Drog]" value="<?= $a["Telefone_Drog"] ?>"><br><br>

        <drogel>CEP</drogel>
        <input type="text" name="drogaria[Cep_Drog]" value="<?= $a["Cep_Drog"] ?>"><br><br>

        <drogel>Número</drogel>
        <input type="text" name="drogaria[Num_Drog]" value="<?= $a["Num_Drog"] ?>"><br><br>

        <button name="atualizar">Atualizar</button>
    </form>

</body>

</html>