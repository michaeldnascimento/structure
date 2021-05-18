<?php
ob_start();
header('Content-Type: text/html; charset=utf-8');
set_time_limit(0);
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

require_once __DIR__ . '/vendor/autoload.php';

// definindo os caminhos dos arquivos
define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT']);
define("ESTRUTURA", str_replace(ROOT_PATH, "", dirname(__FILE__)));
define("ESTRUTURA2", str_replace(ROOT_PATH, "", dirname(__FILE__)));
define('URI', $_SERVER['REQUEST_URI']);
//define("ESTRUTURA_PATH", ROOT_PATH . ESTRUTURA . DS); //error in xampp
const DS = DIRECTORY_SEPARATOR;
const ESTRUTURA_PATH = ESTRUTURA . DS;
const PUBLIC_PATH = ESTRUTURA_PATH . 'public' . DS;

// Verificar a versão do PHP
if (version_compare(PHP_VERSION, '7.2.0', '<')) {
    die('Sua versão do PHP: ' . PHP_VERSION . ' atualize para a versão: 7.2.0 ou superior.<br>');
}

// Verifica se o autoload do Composer está configurado
$composer_autoload = ESTRUTURA . DS . 'vendor' . DS . 'autoload.php';
if (!file_exists($composer_autoload)) {
    die('<b>Execute:</b> composer install --no-dev<br>');
}

// Verifica se o bower está instalado
if (!is_dir(PUBLIC_PATH . 'bower_components' . DS)) {
    die('<b>Execute o comando:</b> bower install<br>');
}

// Verifica se o arquivo .env existe
if (!file_exists(ESTRUTURA_PATH . '.env')) {

    die('<b>Arquivo .env não encontrado:</b> copie o arquivo .env.example para .env');

}else{
    //$dotenv = new Dotenv\Dotenv(__DIR__); // versão anteririor
    $dotenv =  Dotenv\Dotenv :: createUnsafeImmutable (__DIR__); // nova versão do phpdotenv
    $dotenv->load();
}

// Exemplo de como acessar uma variavel no arquivo .env
ini_set('display_errors', env('APP_DEBUG', 1));
env('APP_NAME', 'Caso não encontre a variavel');


// Remove o WWW. da URL
$url = App\Core\Tools::getUrlAtual();
//die(var_dump($url));
if(!empty($url['get'])){
    $url = $url['url'] . '?' . $url['get'];
} else {
    $url = $url['url'];
}

$encontrar = '/' . 'www.' . '/';
if (preg_match($encontrar, $url)) {
    $url = str_replace('www.','',$url);
    header("Location: $url");
    exit;
}
