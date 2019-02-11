function controler(xurl, xdata, xventana, xsuccess) {
    $('#'+ xventana).load(xurl, xdata);
}
function controlerBack(xurl, xdata, xventana, xsuccess) {
    $.ajax({
        async: true
        , url: xurl
        , type: 'POST'
        , data: xdata
        , success: function (html) {
            $('#'+xventana).html(html);
            xsuccess;
        }
    });
}
function controlerConfirm(xurl, xdata, xventana, xmensaje,xsuccess) {
    $.confirm({
        title: 'Confirmación!'
        , content: xmensaje
        , buttons: {
            confirmar: function () {
                $('#'+ xventana).load(xurl, xdata);
            }
            , cancelar: function () {
                if(xsuccess==""){
                    $.alert('Cancelado!');
                } else {
                    xsuccess;
                }
            }
        }
    });
}
var cargando = '<div class="modal Ventana-modal" style="display: flex;"><img style="margin: auto;height:40px;width:80px;" src="../assets/img/cargando.gif" border="0"> </div>';
