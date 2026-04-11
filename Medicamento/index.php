<!DOCTYPE html><?php
ob_start();
include_once ("../Objetos/medicamentoController.php");

$controller = new medicamentoController();
$medicamento = $controller->index();
global $medicamento;
$a = null;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["medicamento"])){
        $a = $controller->pesquisarMedicamento($_POST["pesquisar"]);
    }
}

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET["excluir"])){
        $controller->excluirMedicamento($_GET["excluir"]);
    }
}
?>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NexusDevMed – PharmaPulse</title>
  <style>
    table, tr, td {
      border: 1px solid black;
      border-collapse: collapse;
      padding: 8px;
    }
  </style>
</head>
<body>

  <h1>NexusDev</h1>
  <nav>
    <a href="../index.php">Voltar</a> | 
    <a href="cadastro.php">Cadastrar Medicamento</a>
  </nav>

  <h3>Pesquisar Medicamento</h3>
  <form method="POST" action="index.php">
    <label for="pesquisar">CodMed:</label>
    <input type="number" id="pesquisar" name="pesquisar">
    <input type="hidden" name="medicamento" value="1">
    <button type="submit">Pesquisar</button>
  </form>

  <?php if($a) : ?>
    <table>
      <thead>
        <tr>
          <th>CodMed</th>
          <th>Nome</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= htmlspecialchars($a->codMed ?? ''); ?></td>
          <td><?= htmlspecialchars($a->nome ?? ''); ?></td>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>

  <h2>Medicamentos Cadastrados</h2>

  <table>
    <thead>
      <tr>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Validade</th>
        <th>Qtd</th>
        <th>Valor</th>
        <th colspan="2">Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php if($medicamento) : ?>
        <?php foreach($medicamento as $med) : ?>
          <tr>
            <td><?= htmlspecialchars($med->Nome_Med ?? ''); ?></td>
            <td><?= htmlspecialchars($med->Desc_Med ?? ''); ?></td>
            <td><?= htmlspecialchars($med->DataVal_Med ?? ''); ?></td>
            <td><?= htmlspecialchars($med->Qtd_Med ?? ''); ?></td>
            <td><?= htmlspecialchars($med->Valor_Med ?? ''); ?></td>
            <td><a href="atualizar.php?alterar=<?= $med->Cod_Med; ?>">Alterar</a></td>
            <td><a href="index.php?excluir=<?= $med->Cod_Med; ?>" onclick="return confirm('Deseja excluir?')">Excluir</a></td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="7">Nenhum medicamento cadastrado.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
<?php ob_end_flush(); ?>
