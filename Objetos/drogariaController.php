<?php
include_once "../configs/database.php";
include_once "drogaria.php";

class drogariaController{
    private $bd;
    private $drogaria;

    public function __construct(){
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->drogaria = new Drogaria($this->bd);
    }

    public function index(){
        return $this->drogaria->lerTodos();
    }

    public function pesquisarDrogaria($cnpj){
        return $this->drogaria->buscar($cnpj);
    }

    public function cadastrarDrogaria($dados){
        $this->drogaria->nome = $dados['nome'] ?? '';
        $this->drogaria->cnpj = $dados['cnpj'] ?? '';
        $this->drogaria->telefone = $dados['telefone'] ?? '';
        $this->drogaria->email = $dados['email'] ?? '';
        $this->drogaria->numerodrog = $dados['numerodrog'] ?? 0;
        $this->drogaria->cep = $dados['cep'] ?? '';


        if($this->drogaria->cnpjExiste($this->drogaria->cnpj)){
            if($this->drogaria->reativar()){
                header("location: index.php");
                exit();
            }
        } else {
            if($this->drogaria->cadastrar()){
                header("location: index.php");
                exit();
            }
        }
    }

    public function atualizarDrogaria($dados){
        $this->drogaria->cnpj      = $dados['CNPJ_Drog'] ?? '';
        $this->drogaria->nome      = $dados['Nome_Drog'] ?? '';
        $this->drogaria->email     = $dados['Email_Drog'] ?? '';
        $this->drogaria->telefone  = $dados['Telefone_Drog'] ?? '';
        $this->drogaria->cep       = $dados['Cep_Drog'] ?? '';
        $this->drogaria->numerodrog = $dados['Num_Drog'] ?? '';

        if($this->drogaria->atualizar()){
            header("location: index.php");
            exit();
        }
    }

public function localizarDrogaria($cnpj){
        return $this->drogaria->buscar($cnpj);
}

    public function excluirDrogaria($cnpj){
        if($this->drogaria->excluir($cnpj)){
            header("location: index.php");
            exit();
        }
    }
    public function excluidos(){
        return $this->drogaria->lerExcluidos();
    }

    public function reativarDrogaria($cnpj){
        if($this->drogaria->reativar($cnpj)){
            header("location: index.php");
            exit();
        }
    }
}