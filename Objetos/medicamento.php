<?php

class medicamento {

    public $Nome;
    public $Codigo;
    public $EAN_Med;
    public $Descricao;
    public $DataValidade;
    public $Quantidade;
    public $Valor;
    public $CodCategoria;

    private $bd;

    public function __construct($bd) {
        $this->bd = $bd;
    }

    public function lerTodos() {
        $sql = "SELECT * FROM medicamento";
        $stmt = $this->bd->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function buscarMedicamento($codMed) {
        $sql = "SELECT * FROM medicamento WHERE Cod_Med = :codMed";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":codMed", $codMed, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function cadastrar() {
        $sql = "INSERT INTO medicamento (Nome_Med, Desc_Med, DataVal_Med, Qtd_Med, Valor_Med, Cod_CatMed) 
                VALUES (:nomeMed, :descMed, :dataVal, :qtdMed, :valorMed, :codCat)";

        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":nomeMed",  $this->Nome,         PDO::PARAM_STR);
        $stmt->bindParam(":descMed",  $this->Descricao,    PDO::PARAM_STR);
        $stmt->bindParam(":dataVal",  $this->DataValidade,  PDO::PARAM_STR);
        $stmt->bindParam(":qtdMed",   $this->Quantidade,   PDO::PARAM_INT);
        $stmt->bindParam(":valorMed", $this->Valor,        PDO::PARAM_STR);
        $stmt->bindParam(":codCat",   $this->CodCategoria, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function cadastrarERetornarId() {
        $sql = "INSERT INTO medicamento (EAN_Med, Nome_Med, Desc_Med, DataVal_Med, Qtd_Med, Valor_Med, Cod_CatMed) 
                VALUES (:ean, :nomeMed, :descMed, :dataVal, :qtdMed, :valorMed, :codCat)";

        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":ean",      $this->EAN_Med,      PDO::PARAM_STR);
        $stmt->bindParam(":nomeMed",  $this->Nome,         PDO::PARAM_STR);
        $stmt->bindParam(":descMed",  $this->Descricao,    PDO::PARAM_STR);
        $stmt->bindParam(":dataVal",  $this->DataValidade, PDO::PARAM_STR);
        $stmt->bindParam(":qtdMed",   $this->Quantidade,   PDO::PARAM_INT);
        $stmt->bindParam(":valorMed", $this->Valor,        PDO::PARAM_STR);
        $stmt->bindParam(":codCat",   $this->CodCategoria, PDO::PARAM_INT);

        if($stmt->execute()){
            return $this->bd->lastInsertId();
        }
        return null;
    }

    public function buscarPorEAN($ean) {
        $sql = "SELECT * FROM medicamento WHERE EAN_Med = :ean";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":ean", $ean, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function adicionarEstoqueProcedimento($codMed, $qtdComprada) {
        $sql = "CALL sp_atualiza_estoque_apos_compra(:codMed, :qtd)";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":codMed", $codMed, PDO::PARAM_INT);
        $stmt->bindParam(":qtd", $qtdComprada, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function atualizar() {
        $sql = "UPDATE medicamento 
                SET Nome_Med = :nomeMed, Desc_Med = :descMed, 
                    DataVal_Med = :dataVal, Qtd_Med = :qtdMed, 
                    Valor_Med = :valorMed, Cod_CatMed = :codCat
                WHERE Cod_Med = :codMed";

        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":codMed",   $this->Codigo,       PDO::PARAM_INT);
        $stmt->bindParam(":nomeMed",  $this->Nome,         PDO::PARAM_STR);
        $stmt->bindParam(":descMed",  $this->Descricao,    PDO::PARAM_STR);
        $stmt->bindParam(":dataVal",  $this->DataValidade,  PDO::PARAM_STR);
        $stmt->bindParam(":qtdMed",   $this->Quantidade,   PDO::PARAM_INT);
        $stmt->bindParam(":valorMed", $this->Valor,        PDO::PARAM_STR);
        $stmt->bindParam(":codCat",   $this->CodCategoria, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function excluir($codMed) {
        $sql = "DELETE FROM medicamento WHERE Cod_Med = :codMed";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":codMed", $codMed, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function codMedExiste($codMed) {
        $sql = "SELECT COUNT(*) FROM medicamento WHERE Cod_Med = :codMed";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":codMed", $codMed, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function pesquisarPorTermo($termo) {
        $sql = "SELECT * FROM medicamento 
                WHERE Nome_Med LIKE :termo 
                OR EAN_Med LIKE :termo";
        $stmt = $this->bd->prepare($sql);
        $likeTermo = "%$termo%";
        $stmt->bindParam(":termo", $likeTermo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}