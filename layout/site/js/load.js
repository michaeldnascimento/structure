/**
 * Classe Load
 *
 * Construct of Load setting the attibs
 */
function Load() {
    //this.atributo;
}

Load.prototype = {
    constructor: Load,
    exibir: function () {
        $("<div>")
                .attr("class", "modal fade div-modal-load")
                .attr("id", "div-modal-load")
                .attr("tabindex", "-1")
                .attr("role", "dialog")
                .attr("aria-labelledby", "myModalLabel")
                .attr("aria-hidden", "true")
                .append($("<div>")
                        .attr("class", "modal-dialog")
                        .append($("<div>")
                                .attr("class", "modal-content")
                                .css({
                                    "width": "300px",
                                    "margin-left": "50%",
                                    "margin-top": "50%",
                                    "transform": "translate(-50%,-50%)"
                                })
                                .append($("<div>")
                                        .attr("class", "modal-body text-center")
                                        .append("<span class='fa fa-spinner fa-pulse fa-5x fa-fw margin-bottom'></span><h4 class='modal-title'>Carregando ...</h4>")
                                        )
                                )
                        )
                .appendTo("body")
                .modal({
                    backdrop: "static",
                    keyboard: false
                });
    },
    remover: function () {
        setTimeout(function () {
            $(".div-modal-load").modal("hide").on('hidden.bs.modal', function () {
                $(".div-modal-load").remove();
            });
        }, 600);
    }
};