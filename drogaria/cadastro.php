<?php
include_once("../objetos/drogariaController.php");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $controller = new drogariaController();

    if(isset($_POST["cadastrar"])){
        $a = $controller->cadastrarDrogaria($_POST["drogaria"]);
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Laboratório</title>
</head>
<body>
<h1>Cadastro de Laboratórios</h1>
<a href="index.php">Voltar</a>

<form action="cadastro.php" method="post" enctype="multipart/form-data">
    <drogel>Nome</drogel>
    <input type="text" name="drogaria[nome]"><br><br>
    <drogel>E-mail</drogel>
    <input type="text" name="drogaria[email]"><br><br>
    <drogel>Telefone</drogel>
    <input type="text" name="drogaria[telefone]"><br><br>
    <drogel>CNPJ</drogel>
    <input type="text" name="drogaria[cnpj]"><br><br>
    <drogel>CEP</drogel>
    <input type="text" name="drogaria[cep]"><br><br>
    <drogel>Número do Lab</drogel>
    <input type="text" name="drogaria[num_drog]"><br><br>

    <button name="cadastrar">Cadastrar</button>

</form>

</body>
</html>

