<?php

use App\Core\Template;

?>

<body>
<!-- THEME CSS -->
<link href="<?php echo Template::layoutUrl() . Template::$layout . '/css/style.css" rel="stylesheet" type="text/css'; ?>" />

<!-- THEME JS -->
<script type="text/javascript" src="<?php echo Template::layoutUrl() . Template::$layout . '/js/scripts.js'; ?>"></script>
<script type="text/javascript" src="<?php echo Template::layoutUrl() . Template::$layout . '/js/load.js'; ?>"></script>

<script type="text/javascript">

    //INICIO - ESSE SCRIPT É PARA OS NAVEGADORES QUE NÃO TEM SUPORTE PARA REQUIRED
    function hasHtml5Validation() {
        return typeof document.createElement('input').checkValidity === 'function';
    }

    jQuery(window).ready(function () {
        if (hasHtml5Validation()) {
            $('form').submit(function (e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    //$(this).addClass('invalid');
                    alert_erro("Preencher os campos obrigatórios.");
                } else {
                    //$(this).removeClass('invalid');
                }
            });
        }
    });
    //FIM - ESSE SCRIPT É PARA OS NAVEGADORES QUE NÃO TEM SUPORTE PARA REQUIRED

    projeto = "<?=PROJETO;?>";
</script>

<div id="wrapper">
    <!--  MENU -->
    <div id="header">
        <!-- Top nav -->
        <header id="topBar">
            <div class="container">


                <div id="barMain" style="width:60%" class="hide_mobile">

                    <!-- Search -->
                    <form class="search" method="post" action="#">
                        <!--                            <input type="text" class="form-control" name="palavra" value="" placeholder="O que você procura?" required>
                                                    <button class="fa fa-search"></button>-->
                    </form>


                </div>
            </div>
        </header>
        <!-- /Top Nav -->

        <!-- Menu nav Ativado 03/09/2019 Michael -->
        <header id="topNav" style="background-color: #fafafa;">
            <div class="container">

                <h2><!-- Page Title -->
                    MD System
                </h2><!-- /Page Title -->

            </div>
        </header>

    <!-- CONTEUDO -->
    <section>
        <div name="div-conteudo" id="div-conteudo" class="container">

