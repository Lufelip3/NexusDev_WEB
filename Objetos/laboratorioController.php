<?php
include_once "../configs/database.php";
include_once "laboratorio.php";

class laboratorioController{
    private $bd;
    private $laboratorio;

    public function __construct(){
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->laboratorio = new Laboratorio($this->bd);
    }

    public function index(){
        return $this->laboratorio->lerTodos();
    }

    public function pesquisarLaboratorio($tipo, $valor) {
        if ($tipo == 'cnpj') {
            // Remove qualquer símbolo (/, -, .) do valor pesquisado para comparar apenas números
            $valor = preg_replace('/[^0-9]/', '', $valor);
            $sql = "SELECT * FROM laboratorio 
                    WHERE REPLACE(REPLACE(REPLACE(CNPJ_Lab, '.', ''), '/', ''), '-', '') = :busca 
                    AND Ativo_Lab = 1";
        } else if ($tipo == 'nome') {
            $sql = "SELECT * FROM laboratorio WHERE Nome_Lab LIKE :busca AND Ativo_Lab = 1";
            $valor = "%$valor%";
        } else {
            return null;
        }

        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(':busca', $valor);
        $resultado->execute();
        
        $dados = $resultado->fetchAll(PDO::FETCH_OBJ);
        return count($dados) > 0 ? $dados : null;
    }

    public function cadastrarLaboratorio($dados, $arquivos = null){
        $this->laboratorio->nome = $dados['nome'] ?? '';
        $this->laboratorio->cnpj = $dados['cnpj'] ?? '';
        $this->laboratorio->telefone = $dados['telefone'] ?? '';
        $this->laboratorio->email = $dados['email'] ?? '';
        $this->laboratorio->numerolab = $dados['num_lab'] ?? $dados['numerolab'] ?? 0;
        $this->laboratorio->cep = $dados['cep'] ?? '';

        if ($arquivos && isset($arquivos['Foto_Lab']) && $arquivos['Foto_Lab']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($arquivos['Foto_Lab']['name'], PATHINFO_EXTENSION);
            $foto_nome = uniqid() . '.' . $extensao;
            $destino = "../uploads/laboratorios/" . $foto_nome;
            if (!is_dir("../uploads/laboratorios/")) {
                mkdir("../uploads/laboratorios/", 0777, true);
            }
            move_uploaded_file($arquivos['Foto_Lab']['tmp_name'], $destino);
            $this->laboratorio->foto = $foto_nome;
        } else {
            $this->laboratorio->foto = null;
        }

        if($this->laboratorio->cnpjExiste($this->laboratorio->cnpj)){
            if($this->laboratorio->reativar($this->laboratorio->cnpj)){
                header("location: index.php");
                exit();
            }
        } else {
            if($this->laboratorio->cadastrar()){
                header("location: index.php");
                exit();
            }
        }
    }

    public function atualizarLaboratorio($dados, $arquivos = null){
        $this->laboratorio->cnpj      = $dados['CNPJ_Lab'] ?? '';
        $this->laboratorio->nome      = $dados['Nome_Lab'] ?? '';
        $this->laboratorio->email     = $dados['Email_Lab'] ?? '';
        $this->laboratorio->telefone  = $dados['Telefone_Lab'] ?? '';
        $this->laboratorio->cep       = $dados['Cep_Lab'] ?? '';
        $this->laboratorio->numerolab = $dados['Num_Lab'] ?? '';

        if ($arquivos && isset($arquivos['Foto_Lab']) && $arquivos['Foto_Lab']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($arquivos['Foto_Lab']['name'], PATHINFO_EXTENSION);
            $foto_nome = uniqid() . '.' . $extensao;
            $destino = "../uploads/laboratorios/" . $foto_nome;
            if (!is_dir("../uploads/laboratorios/")) {
                mkdir("../uploads/laboratorios/", 0777, true);
            }
            move_uploaded_file($arquivos['Foto_Lab']['tmp_name'], $destino);
            $this->laboratorio->foto = $foto_nome;
        } else {
            $this->laboratorio->foto = null;
        }

        if($this->laboratorio->atualizar()){
            header("location: index.php");
            exit();
        }
    }

public function localizarLaboratorio($cnpj){
        return $this->laboratorio->buscar($cnpj);
}

    public function excluirLaboratorio($cnpj){
        if($this->laboratorio->excluir($cnpj)){
            header("location: index.php");
            exit();
        }
    }
    public function excluidos(){
        return $this->laboratorio->lerExcluidos();
    }

    public function reativarLaboratorio($cnpj){
        if($this->laboratorio->reativar($cnpj)){
            header("location: index.php");
            exit();
        }
    }
}