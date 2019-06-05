


<!DOCTYPE html>
<html lang="es">



<body>
    <script>
    /*var tem_fun = function download(filename, text) {
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
        element.setAttribute('download', filename);

        element.style.display = 'none';
        document.body.appendChild(element);

        element.click();

        document.body.removeChild(element);
    }*/

    var tem_fun = function download(nombreArchivo, text) {
        var contenidoEnBlob =  new Blob([text], { type: 'text/plain' }),
            reader = new FileReader();

        reader.onload = function(event) {
            var save = document.createElement('a');
            save.href = event.target.result;
            save.target = '_blank';
            save.download = nombreArchivo || 'factura.txt';
            var clicEvent = new MouseEvent('click', {
                'view': window,
                'bubbles': true,
                'cancelable': true
            });
            save.dispatchEvent(clicEvent);
            (window.URL || window.webkitURL).revokeObjectURL(save.href);
        };
        reader.readAsDataURL(contenidoEnBlob);
    }


    var data_txt = <?=json_encode($data_txt)?>;

    tem_fun("<?=$name?>", data_txt);
    location.href = "<?=site_url("sales")?>";
    </script>
</body>
</html>