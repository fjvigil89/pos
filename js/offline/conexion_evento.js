function handleConnectionChange(event){
    if(event.type == "offline"){
        let accion = function(){
            window.location = SITE_URL + "/sales/offline";
        }
        dialogo_sincronizacion("<div'><span class='jconfirm-icon-c'><i class='fa fa-warning red'></i></span> No hay conexión a internet!</div>",
                "<div> Deseas Activar la versión OffLine? </div>",
                "red","btn-red",accion,"Ok");
        
    }
    if(event.type == "online"){
        let accion = function(){
            sincronizar_ventas();
         }
         if( sessionStorage.getItem("mostrar_dialogo_internet")!=0){
            dialogo_sincronizacion("<div'>Conexion a internet restablecida!</div>",
            "<div> Desea sincronizar las ventas? </div>",
            "green","btn-green",accion,"Si");
         }
         sessionStorage.setItem("mostrar_dialogo_internet",0);

        
       
    }    
    console.log(new Date(event.timeStamp));
}



function dialogo_sincronizacion(title="Error!",content,type="red",btnClass="btn-red",action, text="OK"){
    $.confirm({
        title:title,
        content:content,
        type:type,
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: text,
                btnClass:btnClass,
                action:action
            },
            No: function () {
            }
        }
    });
}

function verificar_conexion_internet(){
    if(navigator.onLine) {
        return true;
    } else {
        return false;
    }
}