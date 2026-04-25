<?php

include_once __DIR__ . "/../configs/database.php";
include_once __DIR__ . "/compra.php";

class CompraController
{
    private $bd;
    private $compra;

    public function __construct()
    {
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->compra = new Compra($this->bd);
    }

    public function index()
    {
        return $this->compra->lerTodos();
    }

    public function pesquisaCompra($NotaFiscal_Entrada)
    {
        return $this->compra->pesquisarCompra($NotaFiscal_Entrada);
    }

    public function iniciarCompra($cpf, $cnpj)
    {
        $this->compra->CPF = $cpf;
        $this->compra->CNPJ_Lab = $cnpj;
        return $this->compra->cadastrarEretornarId();
    }

    /**
     * Salva o estado atual da compra (valor parcial) sem finalizar.
     * Nenhum trigger de estoque é disparado.
     */
    public function salvarRascunhoCompra($notaFiscal, $valorTotal)
    {
        return $this->compra->salvarRascunho($notaFiscal, $valorTotal);
    }

    /**
     * Finaliza a compra: atualiza o valor total e seta Finalizada=1.
     * O trigger trg_finalizar_compra dispara e decrementa o catálogo.
     */
    public function finalizarCompra($notaFiscal, $valorTotal)
    {
        // Primeiro salva o valor total
        $this->compra->salvarRascunho($notaFiscal, $valorTotal);
        // Depois seta Finalizada=1 (dispara o trigger)
        return $this->compra->finalizarStatus($notaFiscal);
    }

    public function localizarCompra($NotaFiscal_Entrada)
    {
        return $this->compra->buscarCompra($NotaFiscal_Entrada);
    }

    public function excluirCompra($NotaFiscal_Entrada)
    {
        // Primeiro remove os itens (sem CASCADE no banco)
        $this->bd->exec("DELETE FROM item WHERE NotaFiscal_Entrada = " . (int)$NotaFiscal_Entrada);
        $this->compra->NotaFiscal_Entrada = $NotaFiscal_Entrada;
        if ($this->compra->excluir()) {
            header("location: index.php");
            exit();
        }
    }

    public function atualizarCompra($dados)
    {
        $this->compra->NotaFiscal_Entrada = $dados["NotaFiscal_Entrada"];
        $this->compra->Valor_Total = $dados["Valor_Total"];
        $this->compra->Data_Compra = $dados["Data_Compra"];
        $this->compra->CPF = $dados["CPF"];
        $this->compra->CNPJ_Lab = $dados["CNPJ_Lab"];

        if ($this->compra->atualizar()) {
            header("location: index.php");
            exit();
        }
    }
}
