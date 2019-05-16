<!DOCTYPE html>
<html lang="es">
<head></head>
<body>
    <script>
    var tem_fun = function download(filename, text) {
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
        element.setAttribute('download', filename);

        element.style.display = 'none';
        document.body.appendChild(element);

        element.click();

        document.body.removeChild(element);
    }
    var data_txt = <?=json_encode($data_txt)?>;
    
    tem_fun("<?=$name?>", data_txt);
    location.href = "<?=site_url("sales")?>";
</script>
</body>