<?php

function clone_file($src, $dst) {
    if(!file_exists($src)) return;
    $content = file_get_contents($src);
    
    $replacements = [
        'Laboratorio' => 'Drogaria',
        'laboratorio' => 'drogaria',
        'Nome_Lab' => 'Nome_Drog',
        'CNPJ_Lab' => 'CNPJ_Drog',
        'Telefone_Lab' => 'Telefone_Drog',
        'Email_Lab' => 'Email_Drog',
        'Cep_Lab' => 'Cep_Drog',
        'Num_Lab' => 'Num_Drog',
        'Ativo_Lab' => 'Ativo_Drog',
        'numerolab' => 'numerodrog',
        'lab' => 'drog' // caution here
    ];
    
    foreach($replacements as $old => $new) {
        $content = str_replace($old, $new, $content);
    }
    
    file_put_contents($dst, $content);
}

if (!is_dir('c:/xampp/htdocs/NexusDev_WEB/drogaria')) {
    mkdir('c:/xampp/htdocs/NexusDev_WEB/drogaria', 0777, true);
}

clone_file('c:/xampp/htdocs/NexusDev_WEB/Objetos/laboratorio.php', 'c:/xampp/htdocs/NexusDev_WEB/Objetos/drogaria.php');
clone_file('c:/xampp/htdocs/NexusDev_WEB/Objetos/laboratorioController.php', 'c:/xampp/htdocs/NexusDev_WEB/Objetos/drogariaController.php');

clone_file('c:/xampp/htdocs/NexusDev_WEB/laboratorio/index.php', 'c:/xampp/htdocs/NexusDev_WEB/drogaria/index.php');
clone_file('c:/xampp/htdocs/NexusDev_WEB/laboratorio/cadastro.php', 'c:/xampp/htdocs/NexusDev_WEB/drogaria/cadastro.php');
clone_file('c:/xampp/htdocs/NexusDev_WEB/laboratorio/atualizar.php', 'c:/xampp/htdocs/NexusDev_WEB/drogaria/atualizar.php');
clone_file('c:/xampp/htdocs/NexusDev_WEB/laboratorio/excluidos.php', 'c:/xampp/htdocs/NexusDev_WEB/drogaria/excluidos.php');

echo "Clone completed.";
