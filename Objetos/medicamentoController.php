<?php
include_once "../configs/database.php";
include_once "medicamento.php";

class medicamentoController{
    private $bd;
    private $medicamento;
    private $img_name;

    public function __construct(){
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->medicamento = new medicamento($this->bd);
    }

    public function index(){
        return $this->medicamento->lerTodos();
    }

    public function pesquisarMedicamento($cod_Med){
        return $this->medicamento->buscarMedicamento($cod_Med);
    }

    public function cadastrarMedicamento($dados, $arquivo = null){
        if(
            empty($dados['Nome_Med']) ||
            empty($dados['Desc_Med']) ||
            empty($dados['DataVal_Med']) ||
            empty($dados['Qtd_Med']) ||
            empty($dados['Valor_Med'])
        ){
            echo "Preencha todos os campos obrigatórios.";
            return;
        }

        $temArquivo = isset($arquivo['name']['Foto_Med'])
            && $arquivo['name']['Foto_Med'] !== ""
            && isset($arquivo['error']['Foto_Med'])
            && $arquivo['error']['Foto_Med'] === UPLOAD_ERR_OK;

        if ($temArquivo && !$this->upload($arquivo)) {
            return false;
        }

        $this->medicamento->Nome         = $dados['Nome_Med'];
        $this->medicamento->Descricao    = $dados['Desc_Med'];
        $this->medicamento->DataValidade = $dados['DataVal_Med'];
        $this->medicamento->Quantidade   = $dados['Qtd_Med'];
        $this->medicamento->Valor        = $dados['Valor_Med'];
        $this->medicamento->CodCategoria = $dados['Cod_CatMed'] ?? null;
        $this->medicamento->Foto         = $temArquivo ? $this->img_name : null;

        if($this->medicamento->cadastrar()){
            header("location: index.php");
            exit();
        }
    }

    public function cadastrarMedicamentoERetornarId($dados){
        $this->medicamento->EAN_Med      = $dados['EAN_Med'] ?? null;
        $this->medicamento->Nome         = $dados['Nome_Med'];
        $this->medicamento->Descricao    = $dados['Desc_Med'];
        $this->medicamento->DataValidade = $dados['DataVal_Med'];
        $this->medicamento->Quantidade   = $dados['Qtd_Med'];
        $this->medicamento->Valor        = $dados['Valor_Med'];
        $this->medicamento->CodCategoria = $dados['Cod_CatMed'] ?? null;
        $this->medicamento->Foto         = $dados['Foto_Med'] ?? null;

        return $this->medicamento->cadastrarERetornarId();
    }

    public function buscarPorEAN($ean) {
        return $this->medicamento->buscarPorEAN($ean);
    }

    public function adicionarEstoqueProcedimento($cod_med, $qtd){
        return $this->medicamento->adicionarEstoqueProcedimento($cod_med, $qtd);
    }

    public function localizarMedicamento($cod_Med){
        return $this->medicamento->buscarMedicamento($cod_Med);
    }

    public function atualizarMedicamento($dados, $arquivo = null){
        $temArquivo = isset($arquivo['name']['Foto_Med'])
            && $arquivo['name']['Foto_Med'] !== ""
            && isset($arquivo['error']['Foto_Med'])
            && $arquivo['error']['Foto_Med'] === UPLOAD_ERR_OK;

        if ($temArquivo && !$this->upload($arquivo)) {
            return false;
        }

        $this->medicamento->Codigo       = $dados['Cod_Med'] ?? '';
        $this->medicamento->Nome         = $dados['Nome_Med'] ?? '';
        $this->medicamento->Descricao    = $dados['Desc_Med'] ?? '';
        $this->medicamento->DataValidade = $dados['DataVal_Med'] ?? '';
        $this->medicamento->Quantidade   = $dados['Qtd_Med'] ?? 0;
        $this->medicamento->Valor        = $dados['Valor_Med'] ?? '';
        $this->medicamento->CodCategoria = $dados['Cod_CatMed'] ?? null;
        
        if ($temArquivo) {
            $this->medicamento->Foto = $this->img_name;
        } else {
            $existing = $this->localizarMedicamento($dados['Cod_Med']);
            $this->medicamento->Foto = $existing->Foto_Med ?? null;
        }

        if($this->medicamento->atualizar()){
            header("location: index.php");
            exit();
        }
    }

    public function excluirMedicamento($cod_Med){
        if($this->medicamento->excluir($cod_Med)){
            header("location: index.php");
            exit();
        }
    }

    public function pesquisarPorTermo($termo){
        return $this->medicamento->pesquisarPorTermo($termo);
    }

    public function upload($arquivo)
    {
        $target_dir    = "../uploads/medicamentos/";
        $uploadOk      = 1;
        $target_file   = $target_dir . $arquivo['name']['Foto_Med'];
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $random_name    = uniqid('med_', true) . '.' . $imageFileType;
        $this->img_name = $random_name;
        $upload_file    = $target_dir . $random_name;

        $check = getimagesize($arquivo['tmp_name']['Foto_Med']);
        if ($check === false) {
            $uploadOk = 0;
        }

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if ($uploadOk == 0) {
            return false;
        }

        if (move_uploaded_file($arquivo['tmp_name']['Foto_Med'], $upload_file)) {
            return true;
        }

        return false;
    }
}