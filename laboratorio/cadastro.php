<?php
include_once("../objetos/laboratorioController.php");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $controller = new laboratorioController();

    if(isset($_POST["cadastrar"])){
        $a = $controller->cadastrarLaboratorio($_POST["laboratorio"], $_FILES["laboratorio"]);
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
    <label>Nome</label>
    <input type="text" name="laboratorio[nome]"><br><br>
    <label>E-mail</label>
    <input type="text" name="laboratorio[email]"><br><br>
    <label>Telefone</label>
    <input type="text" name="laboratorio[telefone]"><br><br>
    <label>CNPJ</label>
    <input type="text" name="laboratorio[cnpj]"><br><br>
    <label>CEP</label>
    <input type="text" name="laboratorio[cep]" value="<?= htmlspecialchars($form_data['cep'] ?? '') ?>"><br><br>
    
    <label>Número do Lab</label>
    <input type="text" name="laboratorio[numerolab]" value="<?= htmlspecialchars($form_data['num_lab'] ?? '') ?>"><br><br>

    <label for="foto">Foto do Laboratório:</label><br>
    <input type="file" name="laboratorio[fileToUpload]" id="foto"><br><br>

    <button name="cadastrar">Cadastrar</button>

</form>

</body>
</html>

