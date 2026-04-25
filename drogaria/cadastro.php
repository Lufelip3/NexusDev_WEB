<?php
include_once("../Objetos/drogariaController.php");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $controller = new drogariaController();

    if(isset($_POST["cadastrar"])){
        $a = $controller->cadastrarDrogaria($_POST["drogaria"], $_FILES["drogaria"] ?? null);
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Drogaria</title>
</head>
<body>
<h1>Cadastro de Drogarias</h1>
<a href="index.php">Voltar</a>

<form action="cadastro.php" method="post" enctype="multipart/form-data">
    <label>Nome</label>
    <input type="text" name="drogaria[nome]"><br><br>
    
    <label>E-mail</label>
    <input type="email" name="drogaria[email]"><br><br>
    
    <label>Telefone</label>
    <input type="text" name="drogaria[telefone]"><br><br>
    
    <label>CNPJ</label>
    <input type="text" name="drogaria[cnpj]"><br><br>
    
    <label>CEP</label>
    <input type="text" name="drogaria[cep]"><br><br>
    
    <label>Número</label>
    <input type="number" name="drogaria[numerodrog]"><br><br>

    <label>Foto da Drogaria</label>
    <input type="file" name="drogaria[Foto_Drog]"><br><br>

    <button name="cadastrar">Cadastrar</button>

</form>

</body>
</html>
