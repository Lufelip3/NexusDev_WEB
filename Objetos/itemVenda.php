<?php

Class itemVenda{
    public $Cod_ItemVenda;
    public $DataVal_ItemVenda;
    public $Qtd_ItemVenda;
    public $Valor_ItemVenda;
    public $Cod_Med;
    public $NotaFiscal_Saida;
    public $bd;

    public function __construct($bd){
        $this->bd = $bd;
    }
    public function lerTodos(){
        $sql = "SELECT * FROM item_venda";
        $resultado = $this->bd->query($sql);
        $resultado->execute();

        return $resultado->fetchAll(PDO::FETCH_OBJ);
    }
    public function pesquisarItemVenda($tipo, $valor) {
        if ($tipo == 'cod') {
            $sql = "SELECT * FROM item_venda WHERE Cod_ItemVenda = :busca";
        } else if ($tipo == 'nota') {
            $sql = "SELECT * FROM item_venda WHERE NotaFiscal_Saida = :busca";
        } else {
            return null;
        }

        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(':busca', $valor);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_OBJ);
    }

    public function cadastrarItemVenda() {
        $sql = "INSERT INTO item_venda (DataVal_ItemVenda, Qtd_ItemVenda, Valor_ItemVenda, Cod_Med, NotaFiscal_Saida) 
            VALUES (:dataval, :qtd, :valor, :cod_med, :nota_fiscal)";
        $stmt = $this->bd->prepare($sql);

        $stmt->bindParam(":dataval", $this->DataVal_ItemVenda, PDO::PARAM_STR);
        $stmt->bindParam(":qtd", $this->Qtd_ItemVenda, PDO::PARAM_INT);
        $stmt->bindParam(":valor", $this->Valor_ItemVenda, PDO::PARAM_STR);
        $stmt->bindParam(":cod_med", $this->Cod_Med, PDO::PARAM_INT);
        $stmt->bindParam(":nota_fiscal", $this->NotaFiscal_Saida, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function excluirItemVenda()
    {
        $sql = "DELETE FROM item_venda WHERE Cod_ItemVenda = :Cod_ItemVenda";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":Cod_ItemVenda", $this->Cod_ItemVenda, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function atualizarQuantidade()
    {
        $sql = "UPDATE item_venda SET Qtd_ItemVenda = :Qtd_ItemVenda WHERE Cod_ItemVenda = :Cod_ItemVenda";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":Qtd_ItemVenda", $this->Qtd_ItemVenda, PDO::PARAM_INT);
        $stmt->bindParam(":Cod_ItemVenda", $this->Cod_ItemVenda, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function atualizarItemVenda()
    {
        $sql = "UPDATE item_venda SET DataVal_ItemVenda = :DataVal_ItemVenda, Qtd_ItemVenda = :Qtd_ItemVenda, 
                      Valor_ItemVenda = :Valor_ItemVenda, Cod_Med = :Cod_Med, NotaFiscal_Saida = :NotaFiscal_Saida   
                  WHERE Cod_ItemVenda = :Cod_ItemVenda";

        $stmt = $this->bd->prepare($sql);

        $stmt->bindParam(":DataVal_ItemVenda", $this->DataVal_ItemVenda, PDO::PARAM_STR);
        $stmt->bindParam(":Qtd_ItemVenda", $this->Qtd_ItemVenda, PDO::PARAM_INT);
        $stmt->bindParam(":Valor_ItemVenda", $this->Valor_ItemVenda, PDO::PARAM_INT);
        $stmt->bindParam(":Cod_Med", $this->Cod_Med, PDO::PARAM_INT);
        $stmt->bindParam(":NotaFiscal_Saida", $this->NotaFiscal_Saida, PDO::PARAM_INT);
        $stmt->bindParam(":Cod_ItemVenda", $this->Cod_ItemVenda, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function buscaItemVenda($NotaFiscal_Saida)
    {
        $sql = "SELECT * FROM item_venda WHERE NotaFiscal_Saida = :NotaFiscal_Saida";
        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(":NotaFiscal_Saida", $NotaFiscal_Saida);
        $resultado->execute();

        return $resultado->fetchAll(PDO::FETCH_OBJ);
    }
}

