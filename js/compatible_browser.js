
function validation_indexedDB_browser(){
    if (!window.indexedDB) {
        //window.alert("Su navegador no soporta una versión estable de indexedDB. Tal y como las características no serán validas");
        
        return true;
        
    }else{
        return false;
    }
}
 