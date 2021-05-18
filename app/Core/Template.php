<?php

namespace App\Core;

class Template {

    public static $scripts = array();
    public static $estilos = array();
    public static $login;
    public static $idturma;
    public static $nivel;
    public static $sistema;
    private static $session = false;
    public static $layout = 'site';

    public static $assets = [
        'jquery' => TRUE,
        'bootstrap' => TRUE,
        'font-awesome' => TRUE,
        'jquery-ui' => TRUE,
        'jquery-maskedinput' => FALSE,
        'jquery-maskmoney' => FALSE,
        'bootstrap-dialog' => FALSE,
        'toastr' => FALSE,
        'owl-carousel' => FALSE,
        'bootstrap-select' => FALSE,
        'highcharts' => FALSE,
        'auto-complete-address' => FALSE,
    ];

    public static function url(): string
    {
        $host = $_SERVER['HTTP_HOST'];
        // $porta = ($_SERVER['SERVER_PORT'] == 80) ? '' : ':' . $_SERVER['SERVER_PORT'];

        // return 'http://' . $host . $porta . '/';
        return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $host . '/';
    }

    public static function estruturaUrl(): string
    {
        return self::url() . ESTRUTURA . DS;
    }

    public static function publicUrl(): string
    {
        //return self::estruturaUrl() . 'public' . DS; dá error no xampp
        return '../../structure' . DS . 'public' . DS;
    }

    public static function layoutUrl(): string
    {
        //return self::estruturaUrl() . 'layout' . DS; dá error no xampp
        return '../../structure' . DS . 'layout' . DS;
    }

    public static function publicPath(): string
    {
        return ESTRUTURA_PATH . 'public' . DS;
    }

    public static function layoutPath(): string
    {
        return ESTRUTURA_PATH . 'layout' . DS;
    }

    /**
     * 1 lança um echo com a string do header
     * 0 retorna o conteudo do header
     *
     */
    public static function getHeader($echo = 1)
    {
        $header = file_get_contents(self::layoutPath() . "Header.php");

        $estilos = self::loadCss();
        $scripts = self::loadJs();

        $header = str_replace('$head', $estilos . $scripts, $header);

        $header = str_replace('$titulo', self::$sistema, $header);

        $header = str_replace('$sistema', self::$sistema, $header);
        $header = str_replace('$site', self::url(), $header);
        $header = str_replace('$estrutura', self::estruturaUrl(), $header);

        // Registra a visita para a tabela de historico de visitas
        //$historicoVisitasDAO = new App\Model\HistoricoVisita();
        //$historicoVisitasDAO->registrarVisita();

        if ($echo == 1)
            echo $header;
        else
            return $header;
    }

    public static function getMenu($file = null)
    {
        if ($file) {
            require_once($file);
        } else {
            require_once(self::layoutPath() . self::$layout . DS . "Menu.php");
        }
    }

    public static function getFooter($file = null)
    {
        if ($file) {
            require_once($file);
        } else {
            require_once(self::layoutPath() . self::$layout . DS . "Footer.php");
        }
    }

    public static function getFooterPortal($file = null)
    {
        if ($file) {
            require_once($file);
        } else {
            require_once(self::layoutPath() . self::$layout . DS . "FooterPortal.php");
        }
    }

    public static function setSession($boo = TRUE)
    {
        self::$session = $boo;
    }

    public static function getSession(): bool
    {
        return self::$session;
    }

    public static function insertCss($file) {
        if (strpos($file, ".css"))
            array_push(self::$estilos, $file);
    }

    /**
     * Carrega todas as folhas de estilo no Header.php
     */
    public static function loadCss(): string
    {
        $estilos = "";

        // CSS passado pelo Template::insertCss()
        foreach (self::$estilos as $estilo) {
            $estilos .= "<link rel='stylesheet' type='text/css' href='{$estilo}' />\n";
        }

        // monta os vetores com os itens encontrados na pasta
        $itens = array();
        $ponteiro = opendir(self::publicPath() . 'css' . DS);
        while ($nome_itens = readdir($ponteiro)) {
            $file = explode(".", $nome_itens);
            if (!strcmp($file[count($file) - 1], "css")) {
                $itens[] = $nome_itens;
            }
        }

        if ($itens) {
            sort($itens);

            foreach ($itens as $estilo) {
                $file = self::publicUrl() . "css/$estilo";
                $estilos .= "<link rel='stylesheet' type='text/css' href='{$file}' />\n";
            }
        }
        //-----------------

        return $estilos;
    }

    /**
     * enfilera js no cab "js"
     */
    public static function insertJs($file)
    {
        if (strpos($file, ".js"))
            array_push(self::$scripts, $file);
    }

    /**
     * Carrega todos os arquivos de scripts no Header.php
     */
    public static function loadJs(): string
    {
        $time = time();
        $scripts = "";

        // JS passado pelo Template::insertJs()
        foreach (self::$scripts as $js) {
            $scripts .= "<script language='javascript' type='text/javascript' src='{$js}?{$time}' ></script>\n";
        }

        // monta os vetores com os itens encontrados na pasta
        $itens = array();
        $ponteiro = opendir(self::publicPath() . 'js' . DS);
        while ($nome_itens = readdir($ponteiro)) {
            $file = explode(".", $nome_itens);
            if (!strcmp($file[count($file) - 1], "js")) {
                $itens[] = $nome_itens;
            }
        }

        if ($itens) {
            sort($itens);

            foreach ($itens as $js) {
                $file = self::publicUrl() . "js/$js";
                $time = time();
                $scripts .= "<script language=\"javascript\" type=\"text/javascript\" src=\"{$file}?{$time}\" ></script>\n";
            }
        }
        //-----------------
        /*
        // Carrega o js da sessao se verdadeiro
        if (self::$session == true) {
            $time = time();
            $js = self::publicUrl() . "js/session/session.js?{$time}";

            $scripts .= "<script language=\"javascript\" type=\"text/javascript\" src=\"{$js}\" ></script>\n";
        }
        */
        return $scripts;
    }

    public static function loadAssets()
    {

        foreach (self::$assets AS $key => $value){

            switch ($key) {
                case 'jquery':
                    if($value){
                        $js = self::publicUrl() . 'bower_components/jquery/dist/jquery.min.js';
                        self::insertJs($js);
                    }

                    break;
                case 'bootstrap':
                    if($value){
                        $css = self::publicUrl() . 'bower_components/bootstrap/dist/css/bootstrap.min.css';
                        self::insertCss($css);

                        $js = self::publicUrl() . 'bower_components/bootstrap/dist/js/bootstrap.min.js';
                        self::insertJs($js);
                    }

                    break;
                case 'font-awesome':
                    if($value){
                        $css = self::publicUrl() . 'bower_components/font-awesome/css/fontawesome.php';
                        self::insertCss($css);
                    }

                    break;
                case 'jquery-ui':
                    if($value){
                        $css = self::publicUrl() . 'bower_components/jquery-ui/themes/south-street/jquery-ui.min.css';
                        self::insertCss($css);

                        $js = self::publicUrl() . 'bower_components/jquery-ui/jquery-ui.min.js';
                        self::insertJs($js);

                        $js = self::publicUrl() . 'bower_components/jquery-ui/ui/i18n/datepicker-pt-BR.js';
                        self::insertJs($js);
                    }

                    break;
                case 'jquery-maskedinput':
                    if($value){
                        $js = self::publicUrl() . 'bower_components/jquery-mask-plugin/dist/jquery.mask.min.js';
                        self::insertJs($js);
                    }

                    break;
                case 'jquery-maskmoney':
                    if($value){
                        $js = Template::publicUrl() . 'bower_components/jquery.maskmoney/dist/jquery.maskMoney.min.js';
                        self::insertJs($js);
                    }

                    break;
                case 'bootstrap-dialog':
                    if($value){
                        $css = self::publicUrl() . 'bower_components/bootstrap3-dialog/dist/css/bootstrap-dialog.min.css';
                        self::insertCss($css);

                        $js = self::publicUrl() . 'bower_components/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js';
                        self::insertJs($js);
                    }

                    break;
                case 'toastr':
                    if($value){
                        $css = self::publicUrl() . 'bower_components/toastr/toastr.min.css';
                        self::insertCss($css);

                        $js = self::publicUrl() . 'bower_components/toastr/toastr.min.js';
                        self::insertJs($js);
                    }

                    break;
                case 'owl-carousel':
                    if($value){
                        $css = self::publicUrl() . 'bower_components/owl.carousel/dist/assets/owl.carousel.min.css';
                        self::insertCss($css);

                        $css = self::publicUrl() . 'bower_components/owl.carousel/dist/assets/owl.theme.default.css';
                        self::insertCss($css);

                        $js = self::publicUrl() . 'bower_components/owl.carousel/dist/owl.carousel.min.js';
                        self::insertJs($js);
                    }

                    break;
                case 'bootstrap-select':
                    if($value){
                        $css = self::publicUrl() . 'bower_components/bootstrap-select/dist/css/bootstrap-select.min.css';
                        self::insertCss($css);

                        $js = self::publicUrl() . 'bower_components/bootstrap-select/dist/js/bootstrap-select.min.js';
                        self::insertJs($js);
                    }

                    break;
                case 'highcharts':
                    if($value){
                        $js = self::publicUrl() . 'bower_components/highcharts/highcharts.js';
                        self::insertJs($js);

                        $js = self::publicUrl() . 'bower_components/highcharts/modules/series-label.js';
                        self::insertJs($js);

                        $js = self::publicUrl() . 'bower_components/highcharts/modules/exporting.js';
                        self::insertJs($js);

                        $js = self::publicUrl() . 'bower_components/highcharts/modules/export-data.js';
                        self::insertJs($js);
                    }

                    break;
                case 'auto-complete-address':

                    if($value){
                        $js = self::layoutUrl() . 'plugins/auto-complete-address/src/jquery.autocomplete-address.js';
                        self::insertJs($js);
                    }

                    break;
            }
        }
    }

}

