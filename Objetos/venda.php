<?php

Class venda{
    public $NotaFiscal_Saida;
    public $Data_Venda;
    public $Valor_Venda;
    public $CNPJ_Drog;
    public $CPF;
    public $bd;

    public function __construct($bd){
        $this->bd = $bd;
    }
    public function lerTodos(){
        $sql = "SELECT * FROM venda";
        $resultado = $this->bd->query($sql);
        $resultado->execute();

        return $resultado->fetchAll(PDO::FETCH_OBJ);
    }
    public function pesquisarVenda($NotaFiscal_Saida){
        $sql = "SELECT * FROM venda WHERE NotaFiscal_Saida = :NotaFiscal_Saida";
        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(":NotaFiscal_Saida", $NotaFiscal_Saida);
        $resultado->execute();

        return $resultado->fetch(PDO::FETCH_OBJ);
    }

    public function cadastrar(){
        $sql = "INSERT INTO venda (Data_Venda, Valor_Venda, CNPJ_Drog, CPF) 
            VALUES (:Data_Venda, :Valor_Venda, :CNPJ_Drog, :CPF)";

        $stmt = $this->bd->prepare($sql);

        $stmt->bindParam(":Data_Venda", $this->Data_Venda, PDO::PARAM_STR);
        $stmt->bindParam(":Valor_Venda", $this->Valor_Venda);
        $stmt->bindParam(":CNPJ_Drog", $this->CNPJ_Drog, PDO::PARAM_STR);
        $stmt->bindParam(":CPF", $this->CPF, PDO::PARAM_STR);

        if($stmt->execute()){
            return true;
        } else{
            return false;
        }
    }

    public function iniciarRetornandoId(){
        $sql = "INSERT INTO venda (Data_Venda, Valor_Venda, CNPJ_Drog, CPF) VALUES (NOW(), 0.00, NULL, :CPF)";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":CPF", $this->CPF, PDO::PARAM_STR);
        $stmt->execute();
        return $this->bd->lastInsertId();
    }

    public function excluir()
    {
        $sql = "DELETE FROM venda WHERE NotaFiscal_Saida = :NotaFiscal_Saida";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":NotaFiscal_Saida", $this->NotaFiscal_Saida, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function atualizar()
    {
        $sql = "UPDATE venda SET Data_Venda = :Data_Venda, Valor_Venda = :Valor_Venda, CNPJ_Drog = :CNPJ_Drog, CPF = :CPF WHERE NotaFiscal_Saida = :NotaFiscal_Saida";

        $stmt = $this->bd->prepare($sql);

        $stmt->bindParam(":Data_Venda", $this->Data_Venda, PDO::PARAM_STR);
        $stmt->bindParam(":Valor_Venda", $this->Valor_Venda);
        $stmt->bindParam(":CNPJ_Drog", $this->CNPJ_Drog, PDO::PARAM_STR);
        $stmt->bindParam(":CPF", $this->CPF, PDO::PARAM_STR);
        $stmt->bindParam(":NotaFiscal_Saida", $this->NotaFiscal_Saida, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function buscaVenda($NotaFiscal_Saida)
    {
        $sql = "SELECT * FROM venda WHERE NotaFiscal_Saida = :NotaFiscal_Saida";
        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(":NotaFiscal_Saida", $NotaFiscal_Saida);
        $resultado->execute();

        return $resultado->fetch(PDO::FETCH_OBJ);
    }
}