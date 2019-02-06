function character_limiter (texto, maxlength=1){
    return texto.slice(0, maxlength);
 }
 function escapeHtml(unsafe) {
    if (unsafe == null || unsafe == undefined) {
        return "";
    }
    else {
        
        return String(unsafe)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
}