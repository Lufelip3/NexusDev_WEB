<?php

include_once __DIR__ . "/../configs/database.php";
include_once __DIR__ . "/venda.php";

class VendaController
{
    private $bd;
    private $venda;

    public function __construct()
    {
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->venda = new Venda($this->bd);
    }

    public function index()
    {
        return $this->venda->lerTodos();
    }
    
    public function listarPorStatus($status)
    {
        return $this->venda->lerPorStatus($status);
    }

    public function filtrarVendas($nf, $status, $cnpj, $data_inicio = null, $data_fim = null)
    {
        return $this->venda->filtrar($nf, $status, $cnpj, $data_inicio, $data_fim);
    }

    public function pesquisaVenda($NotaFiscal_Saida)
    {
        return $this->venda->pesquisarVenda($NotaFiscal_Saida);
    }

    public function iniciarVenda($cpf)
    {
        $this->venda->CPF = $cpf;
        return $this->venda->iniciarRetornandoId();
    }

    /**
     * Salva o estado atual da venda (itens, CNPJ, valor) sem finalizar.
     * Nenhum trigger de estoque é disparado.
     */
    public function salvarRascunhoVenda($notaFiscal, $valorTotal, $cnpj)
    {
        return $this->venda->salvarRascunho($notaFiscal, $valorTotal, $cnpj);
    }

    /**
     * Finaliza a venda: atualiza valor/CNPJ e seta Finalizada=1.
     * O trigger trg_finalizar_venda dispara e decrementa o estoque.
     */
    public function finalizarVenda($notaFiscal, $valorTotal, $cnpj)
    {
        // Primeiro salva os dados finais
        $this->venda->salvarRascunho($notaFiscal, $valorTotal, $cnpj);
        // Depois seta Finalizada=1 (dispara o trigger)
        return $this->venda->finalizarStatus($notaFiscal);
    }

    public function excluirVenda($NotaFiscal_Saida)
    {
        // Primeiro remove os itens (sem CASCADE no banco)
        $this->bd->exec("DELETE FROM item_venda WHERE NotaFiscal_Saida = " . (int)$NotaFiscal_Saida);
        $this->venda->NotaFiscal_Saida = $NotaFiscal_Saida;
        $this->venda->excluir();
        header("location: index.php");
        exit();
    }

    public function atualizarVenda($dados)
    {
        $this->venda->NotaFiscal_Saida = $dados["NotaFiscal_Saida"];
        $this->venda->Data_Venda = $dados["Data_Venda"];
        $this->venda->Valor_Venda = $dados["Valor_Venda"];
        $this->venda->CNPJ_Drog = $dados["CNPJ_Drog"];
        $this->venda->CPF = $dados["CPF"];

        if ($this->venda->atualizar()) {
            header("location: index.php");
            exit();
        }
    }

    public function localizarVenda($NotaFiscal_Saida)
    {
        return $this->venda->buscaVenda($NotaFiscal_Saida);
    }
}