jQuery(window).ready(function () {
    Main();
});

/** Core
 **************************************************************** **/
function Main() {
    _footer();
    _topNav();
    _scrollTo();
    _datepicker();
    _maskedinput();
    _addressComplete();
    _money();

    // Bootstrap Tooltip
    jQuery("a[data-toggle=tooltip], span[data-toggle=tooltip]").tooltip();

    // Mobile - hide on click!
    jQuery(document).bind("click", function () {
        if (jQuery("div.navbar-collapse").hasClass('in')) {
            jQuery("button.btn-mobile").trigger('click');
        }
    });

    // Side nav
    jQuery("li.list-toggle").bind("click", function () {
        jQuery(this).toggleClass("active");
    });

    jQuery('.no-copy').bind('copy', function (e) {
        e.preventDefault();
        alert('Não é permitido usar a função ' + e.type);
    });

    jQuery('.no-paste').bind('paste', function (e) {
        e.preventDefault();
        alert('Não é permitido usar a função ' + e.type);
    });
    jQuery('.no-cut').bind('cut', function (e) {
        e.preventDefault();
        alert('Não é permitido usar a função ' + e.type);
    });
    jQuery('.no-tres').bind('cut copy paste', function (e) {
        e.preventDefault();
        alert('Não é permitido usar a função ' + e.type);
    });

    jQuery("button[type='reset'], input[type='reset']").click(function () {
        if (typeof jQuery('input').selectpicker == 'function') {
            jQuery(".selectpicker").val("").selectpicker("refresh").data('selectpicker').$searchbox.val('').trigger('propertychange');
        }
    });
}

/** 01. Top Nav
 **************************************************************** **/
function _topNav() {
    window.scrollTop = 0;

    jQuery(window).scroll(function () {
        _toTop();
    });

    /* Scroll To Top */
    function _toTop() {
        _scrollTop = jQuery(document).scrollTop();

        if (_scrollTop > 50) {

            if (jQuery("#toTop").is(":hidden")) {
                jQuery("#toTop").show();
            }

        } else {

            if (jQuery("#toTop").is(":visible")) {
                jQuery("#toTop").hide();
            }

        }

    }


    // Mobile Submenu
    var addActiveClass = false;
    jQuery("#topMain a.dropdown-toggle").bind("click", function (e) {
        if (jQuery(this).attr('href') == '#') {
            e.preventDefault();
        }

        addActiveClass = jQuery(this).parent().hasClass("resp-active");
        jQuery("#topMain").find(".resp-active").removeClass("resp-active");

        if (!addActiveClass) {
            jQuery(this).parents("li").addClass("resp-active");
        }

        return;

    });

    // Drop Downs - do not hide on click
    jQuery("#topMain li.dropdown, #topMain a.dropdown-toggle").bind("click", function (e) {
        e.stopPropagation();
    });

    // IE11 Bugfix
    // Version 1.1
    // Wednesday, July 23, 2014
    if (jQuery("html").hasClass("ie") || jQuery("html").hasClass("ff3")) {
        jQuery("#topNav ul.nav > li.mega-menu div").addClass('block');
        jQuery("#topNav ul.nav > li.mega-menu div div").addClass('pull-left');
    }
}

/** 06. ScrollTo
 **************************************************************** **/
function _scrollTo() {

    jQuery("a.scrollTo").bind("click", function (e) {
        e.preventDefault();

        var href = jQuery(this).attr('href');

        if (href != '#') {
            jQuery('html,body').animate({scrollTop: jQuery(href).offset().top}, 800, 'easeInOutExpo');
        }
    });

    jQuery("a#toTop").bind("click", function (e) {
        e.preventDefault();
        jQuery('html,body').animate({scrollTop: 0}, 800, 'easeInOutExpo');
    });
}

/** 01. Rodape não comer o corpo da pagina
 **************************************************************** **/
function _footer() {
    //Para o rodape não comer o corpo da pagina
    var alturaRodape = $('#footer').height(); //altura do rodape
    alturaRodape += "px";
    $('#wrapper').css({'margin-bottom': alturaRodape});

    //Para o rodape não comer o corpo da pagina ao redimensionar a janela
    $(window).resize(function () {
        var alturaRodape = $('#footer').height(); //altura do rodape
        alturaRodape += "px";

        $('#wrapper').css({'margin-bottom': alturaRodape});
    });
}

/** 01. Calendarios para o jquery-ui
 **************************************************************** **/
function _datepicker() {
    if (typeof jQuery('input').datepicker == 'function') {
        jQuery.datepicker.setDefaults(jQuery.datepicker.regional['pt-BR']);

        /* Ao adicionar essa classe no input, ele exibe um calendário */
        jQuery(".input-data").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            //minDate: new Date(),
            //maxDate: "+11M +30D"
        }).keypress(false).css({
            width: '120px'
        }).attr('autocomplete', 'off').bind('paste', function (e) {
            e.preventDefault();
        });

        /* Ao adicionar essa classe no input, ele exibe um calendário nao deixando selecionar antes da data atual */
        jQuery(".input-data-agora").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            minDate: new Date(),
            //maxDate: "+11M +30D"
        }).keypress(false).css({
            width: '120px'
        }).attr('autocomplete', 'off').bind('paste', function (e) {
            e.preventDefault();
        });

        jQuery(".input-data-nascimento").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            maxDate: "-1D",
            yearRange: 'c-100:c+10'
        }).keypress(false).css({
            width: '120px'
        }).attr('autocomplete', 'off').bind('paste', function (e) {
            e.preventDefault();
        });

        jQuery(".input-data-documento").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            maxDate: new Date(),
            yearRange: 'c-50:c+10'
        }).keypress(false).css({
            width: '120px'
        }).attr('autocomplete', 'off').bind('paste', function (e) {
            e.preventDefault();
        });

        //======== RANGE DE DATAS ==============
        /* Ao adicionar a classe input-data-inicio em um input e
         * input-data-fim no segundo input, evita que a data Fim seja menor que a data Inicio */
        jQuery(".input-data-inicio").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            onClose: function (selectedDate) {
                jQuery(".input-data-fim").datepicker("option", "minDate", selectedDate);

                /*
                 // Esse codigo aplica um limite de 3 anos entra a range de data
                 var dt = selectedDate.split("/");
                 var maxDate = dt[0] + "/" + dt[1] + "/" + (parseInt(dt[2]) + 3);
                 jQuery( ".input-data-fim" ).datepicker( "option", "maxDate", maxDate);
                 */
            }
        }).keypress(false).css({
            width: '120px'
        }).attr('autocomplete', 'off').bind('paste', function (e) {
            e.preventDefault();
        });

        jQuery(".input-data-fim").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            onClose: function (selectedDate) {
                jQuery(".input-data-inicio").datepicker("option", "maxDate", selectedDate);

                /*
                 // Esse codigo aplica um limite de 3 anos entra a range de data
                 var dt = selectedDate.split("/");
                 var minDate = dt[0] + "/" + dt[1] + "/" + (parseInt(dt[2]) - 3);
                 jQuery( ".input-data-inicio" ).datepicker( "option", "minDate", minDate );
                 */
            }
        }).keypress(false).css({
            width: '120px'
        }).attr('autocomplete', 'off').bind('paste', function (e) {
            e.preventDefault();
        });
        //======== RANGE DE DATAS ==============


        //======== RANGE DE DATAS ILDO==============
        /* Ao adicionar a classe input-data-inicio em um input e
         * input-data-fim no segundo input, evita que a data Fim seja menor que a data Inicio */
        jQuery(".input-data-inicio-agora").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            minDate: new Date(),
            onClose: function (selectedDate) {
                jQuery(".input-data-fim-agora").datepicker("option", "minDate", selectedDate);

                /*
                 // Esse codigo aplica um limite de 3 anos entra a range de data
                 var dt = selectedDate.split("/");
                 var maxDate = dt[0] + "/" + dt[1] + "/" + (parseInt(dt[2]) + 3);
                 jQuery( ".input-data-fim" ).datepicker( "option", "maxDate", maxDate);
                 */
            }
        }).keypress(false).css({
            width: '120px'
        }).attr('autocomplete', 'off').bind('paste', function (e) {
            e.preventDefault();
        });

        jQuery(".input-data-fim-agora").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            onClose: function (selectedDate) {
                jQuery(".input-data-inicio-agora").datepicker("option", "maxDate", selectedDate);

                /*
                 // Esse codigo aplica um limite de 3 anos entra a range de data
                 var dt = selectedDate.split("/");
                 var minDate = dt[0] + "/" + dt[1] + "/" + (parseInt(dt[2]) - 3);
                 jQuery( ".input-data-inicio" ).datepicker( "option", "minDate", minDate );
                 */
            }
        }).keypress(false).css({
            width: '120px'
        }).attr('autocomplete', 'off').bind('paste', function (e) {
            e.preventDefault();
        });
        //======== RANGE DE DATAS ==============

    }
}

/** 01. Mascaras do jquery.maskedinput
 **************************************************************** **/
function _maskedinput() {
    if (typeof jQuery('input').mask == 'function') {
        /*
         jQuery('.tel-cel, .telefone-celular').focusout(function () {
         var phone, element;
         element = jQuery(this);
         element.unmask();
         phone = element.val().replace(/\D/g, '');
         if (phone.length > 10) {
         element.mask("(99) 99999-999?9");
         } else {
         element.mask("(99) 9999-9999?9");
         }
         }).trigger('focusout');
         */

        var behavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        };

        var options = {
            onKeyPress: function (val, e, field, options) {
                field.mask(behavior.apply({}, arguments), options);
            }
        };

        $('.tel-cel, .telefone-celular').mask(behavior, options);

        jQuery('.telefone, .tel').mask("(99) 9999-9999");
        jQuery('.celular, .cel').mask("(99) 9999-99999");
        jQuery(".cep").mask("99999-999");
        jQuery(".data").mask("99/99/9999");
        jQuery(".hora").mask("99:99");
        jQuery(".datahora").mask("99/99/9999 99:99");
        jQuery(".cnpj").mask("99.999.999/9999-99");

        jQuery(".cpf").mask("999.999.999-99").focusout(function () {
            var valor, element, erro = new String;

            element = jQuery(this);

            valor = element.val().replace(/\D/g, '');

            if (valor.length == 11) {
                if (valor == "00000000000" ||
                    valor == "11111111111" ||
                    valor == "22222222222" ||
                    valor == "33333333333" ||
                    valor == "44444444444" ||
                    valor == "55555555555" ||
                    valor == "66666666666" ||
                    valor == "77777777777" ||
                    valor == "88888888888" ||
                    valor == "99999999999") {

                    erro = "Número de CPF inválido: ";
                } else {
                    var a = [];
                    var b = new Number;
                    var c = 11;

                    for (i = 0; i < 11; i++) {
                        a[i] = valor.charAt(i);
                        if (i < 9)
                            b += (a[i] * --c);
                    }

                    if ((x = b % 11) < 2) {
                        a[9] = 0
                    } else {
                        a[9] = 11 - x
                    }
                    b = 0;
                    c = 11;

                    for (y = 0; y < 10; y++) {
                        b += (a[y] * c--);
                    }

                    if ((x = b % 11) < 2) {
                        a[10] = 0;
                    } else {
                        a[10] = 11 - x;
                    }

                    if ((valor.charAt(9) != a[9]) || (valor.charAt(10) != a[10])) {
                        erro = "Número de CPF inválido: ";

                    }
                }
            } else {
                if (valor.length == 0) {
                    return;
                } else {
                    erro = "Número de CPF inválido: ";
                }
            }

            if (erro.length > 0) {
                alert_erro(erro + element.val());
                element.val("");
            }
        });
    }
}

/** 01. Mascaras do jquery.maskmoney
 **************************************************************** **/
function _money() {
    if (typeof jQuery('input').maskMoney == 'function') {
        jQuery(".dinheiro-sigla, real-sigla").maskMoney({
            prefix: 'R$ ',
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            precision: 2,
            affixesStay: true
        });

        jQuery(".dinheiro, real").maskMoney({
            prefix: 'R$ ',
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            precision: 2,
            affixesStay: false
        });

        jQuery(".dinheiro-sem-sigla").maskMoney({
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            precision: 2,
            affixesStay: false
        });
    }
}

function _addressComplete() {
    if (typeof jQuery('.cep').autocompleteAddress == 'function') {
        $(".cep").autocompleteAddress();
    }
}

/** 01. Alert do bootstrap3-dialog
 **************************************************************** **/
function alert_info(mensagem, milisegundos = false) {
    if (typeof BootstrapDialog == 'function') {
        var dialog = BootstrapDialog.show({
            title: "<h4><span class='glyphicons glyphicons-circle-info'></span> Informação</h4>",
            message: mensagem,
            draggable: true,
            type: BootstrapDialog.TYPE_INFO
        });

        if ($.isNumeric(milisegundos)) {
            setTimeout(function () {
                dialog.close();
            }, milisegundos);
        }
    } else {
        alert(mensagem);
    }
}

function alert_info_redirecionar(mensagem, milisegundos, redirect) {

    if (typeof BootstrapDialog == 'function') {

        var dialog = BootstrapDialog.show({
            title: "<h4><span class='glyphicons glyphicons-circle-info'></span> Informação</h4>",
            message: mensagem,
            draggable: true,
            type: BootstrapDialog.TYPE_INFO,
            onhide: function (dialogRef) {
                location = redirect;
            }
        });

        if ($.isNumeric(milisegundos)) {
            setTimeout(function () {
                dialog.close();
            }, milisegundos);
        }

    } else {
        alert(mensagem);
        location = redirect;
    }
}

function alert_sucesso(mensagem, milisegundos = false) {
    if (typeof BootstrapDialog == 'function') {
        var dialog = BootstrapDialog.show({
            title: "<h4><span class='glyphicons glyphicons-ok-2'></span> Sucesso</h4>",
            message: mensagem,
            draggable: true,
            type: BootstrapDialog.TYPE_SUCCESS
        });

        if ($.isNumeric(milisegundos)) {
            setTimeout(function () {
                dialog.close();
            }, milisegundos);
        }
    } else {
        alert(mensagem);
    }
}

function alert_sucesso_redirecionar(mensagem, milisegundos, redirect) {

    if (typeof BootstrapDialog == 'function') {

        var dialog = BootstrapDialog.show({
            title: "<h4><span class='glyphicons glyphicons-ok-2'></span> Sucesso</h4>",
            message: mensagem,
            draggable: true,
            type: BootstrapDialog.TYPE_SUCCESS,
            onhide: function (dialogRef) {
                location = redirect;
            }
        });

        if ($.isNumeric(milisegundos)) {
            setTimeout(function () {
                dialog.close();
            }, milisegundos);
        }

    } else {
        alert(mensagem);
        location = redirect;
    }
}

function alert_atencao(mensagem, milisegundos = false) {
    if (typeof BootstrapDialog == 'function') {
        var dialog = BootstrapDialog.show({
            title: "<h4><span class='glyphicons glyphicons-warning-sign'></span> Atenção</h4>",
            message: mensagem,
            draggable: true,
            type: BootstrapDialog.TYPE_WARNING
        });

        if ($.isNumeric(milisegundos)) {
            setTimeout(function () {
                dialog.close();
            }, milisegundos);
        }
    } else {
        alert(mensagem);
    }
}

function alert_atencao_redirecionar(mensagem, milisegundos, redirect) {

    if (typeof BootstrapDialog == 'function') {

        var dialog = BootstrapDialog.show({
            title: "<h4><span class='glyphicons glyphicons-warning-sign'></span> Atenção</h4>",
            message: mensagem,
            draggable: true,
            type: BootstrapDialog.TYPE_WARNING,
            onhide: function (dialogRef) {
                location = redirect;
            }
        });

        if ($.isNumeric(milisegundos)) {
            setTimeout(function () {
                dialog.close();
            }, milisegundos);
        }

    } else {
        alert(mensagem);
        location = redirect;
    }
}

function alert_erro(mensagem, milisegundos = false) {
    if (typeof BootstrapDialog == 'function') {
        var dialog = BootstrapDialog.show({
            title: "<h4><span class='glyphicons glyphicons-remove-2'></span> Ops! Algo deu errado.</h4>",
            message: mensagem,
            draggable: true,
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: false,
            type: BootstrapDialog.TYPE_DANGER
        });

        if ($.isNumeric(milisegundos)) {
            setTimeout(function () {
                dialog.close();
            }, milisegundos);
        }
    } else {
        alert(mensagem);
    }
}

function alert_erro_redirecionar(mensagem, milisegundos, redirect) {

    if (typeof BootstrapDialog == 'function') {

        var dialog = BootstrapDialog.show({
            title: "<h4><span class='glyphicons glyphicons-remove-2'></span> Ops! Algo deu errado.</h4>",
            message: mensagem,
            draggable: true,
            type: BootstrapDialog.TYPE_DANGER,
            onhide: function (dialogRef) {
                location = redirect;
            }
        });

        if ($.isNumeric(milisegundos)) {
            setTimeout(function () {
                dialog.close();
            }, milisegundos);
        }

    } else {
        alert(mensagem);
        location = redirect;
    }
}

function empty(mixed_var) {
    var undef, key, i, len;
    var emptyValues = [undef, null, false, 0, '', '0'];

    for (i = 0, len = emptyValues.length; i < len; i++) {
        if (mixed_var === emptyValues[i]) {
            return true;
        }
    }

    if (typeof mixed_var === 'object') {
        for (key in mixed_var) {
            return false;
        }
        return true;
    }

    return false;
}