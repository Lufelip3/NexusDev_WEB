<?php
include_once("../Objetos/drogariaController.php");

$controller = new drogariaController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["alterar"])) {
    $a = $controller->localizarDrogaria($_GET["alterar"]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["atualizar"])) {
    $controller->atualizarDrogaria($_POST["drogaria"], $_FILES["drogaria"] ?? null);
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
    <title>Atualizar Drogaria</title>
</head>
<body>
    <h1>Atualizar Drogaria</h1>
    <a href="index.php">Voltar</a>

    <?php if($a): ?>
    <form action="atualizar.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="drogaria[CNPJ_Drog]" value="<?= $a["CNPJ_Drog"] ?>">

        <label>Nome</label>
        <input type="text" name="drogaria[Nome_Drog]" value="<?= htmlspecialchars($a["Nome_Drog"]) ?>"><br><br>

        <label>Email</label>
        <input type="email" name="drogaria[Email_Drog]" value="<?= htmlspecialchars($a["Email_Drog"]) ?>"><br><br>

        <label>Telefone</label>
        <input type="text" name="drogaria[Telefone_Drog]" value="<?= htmlspecialchars($a["Telefone_Drog"]) ?>"><br><br>

        <label>CEP</label>
        <input type="text" name="drogaria[Cep_Drog]" value="<?= htmlspecialchars($a["Cep_Drog"]) ?>"><br><br>

        <label>Número</label>
        <input type="number" name="drogaria[Num_Drog]" value="<?= htmlspecialchars($a["Num_Drog"]) ?>"><br><br>

        <label>Foto da Drogaria</label><br>
        <?php if(isset($a['Foto_Drog']) && $a['Foto_Drog']): ?>
            <img src="../uploads/drogarias/<?= $a['Foto_Drog'] ?>" width="100"><br>
        <?php endif; ?>
        <input type="file" name="drogaria[Foto_Drog]"><br><br>

        <button name="atualizar">Atualizar</button>
    </form>
    <?php else: ?>
        <p>Drogaria não encontrada.</p>
    <?php endif; ?>

</body>
</html>