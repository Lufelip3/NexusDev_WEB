<?php

Class venda{
    public $NotaFiscal_Saida;
    public $Data_Venda;
    public $Valor_Venda;
    public $CNPJ_Drog;
    public $CPF;
    public $Finalizada;
    public $bd;

    public function __construct($bd){
        $this->bd = $bd;
    }
    public function lerTodos(){
        $sql = "SELECT v.*, d.Nome_Drog 
                FROM venda v 
                LEFT JOIN drogaria d ON v.CNPJ_Drog = d.CNPJ_Drog
                ORDER BY v.NotaFiscal_Saida DESC";
        $resultado = $this->bd->query($sql);
        $resultado->execute();

        return $resultado->fetchAll(PDO::FETCH_OBJ);
    }
    public function lerPorStatus($status){
        $sql = "SELECT v.*, d.Nome_Drog 
                FROM venda v 
                LEFT JOIN drogaria d ON v.CNPJ_Drog = d.CNPJ_Drog
                WHERE v.Finalizada = :status
                ORDER BY v.NotaFiscal_Saida DESC";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":status", $status, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function filtrar($nf, $status, $cnpj){
        $sql = "SELECT v.*, d.Nome_Drog 
                FROM venda v 
                LEFT JOIN drogaria d ON v.CNPJ_Drog = d.CNPJ_Drog
                WHERE 1=1";
        
        if($nf) $sql .= " AND v.NotaFiscal_Saida = :nf";
        if($status !== "" && $status !== null) $sql .= " AND v.Finalizada = :status";
        if($cnpj) $sql .= " AND v.CNPJ_Drog = :cnpj";
        
        $sql .= " ORDER BY v.NotaFiscal_Saida DESC";
        
        $stmt = $this->bd->prepare($sql);
        if($nf) $stmt->bindParam(":nf", $nf, PDO::PARAM_INT);
        if($status !== "" && $status !== null) $stmt->bindParam(":status", $status, PDO::PARAM_INT);
        if($cnpj) $stmt->bindParam(":cnpj", $cnpj, PDO::PARAM_STR);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
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

    public function finalizarStatus($NotaFiscal_Saida)
    {
        $sql = "UPDATE venda SET Finalizada = 1 WHERE NotaFiscal_Saida = :NotaFiscal_Saida";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":NotaFiscal_Saida", $NotaFiscal_Saida, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function salvarRascunho($NotaFiscal_Saida, $Valor_Venda, $CNPJ_Drog)
    {
        $sql = "UPDATE venda SET Valor_Venda = :Valor_Venda, CNPJ_Drog = :CNPJ_Drog WHERE NotaFiscal_Saida = :NotaFiscal_Saida";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":Valor_Venda", $Valor_Venda);
        $stmt->bindParam(":CNPJ_Drog", $CNPJ_Drog, PDO::PARAM_STR);
        $stmt->bindParam(":NotaFiscal_Saida", $NotaFiscal_Saida, PDO::PARAM_INT);
        return $stmt->execute();
    }
}