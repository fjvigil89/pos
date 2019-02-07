<!DOCTYPE html>   

<html <?php echo' manifest="'.site_url('appcache').'"'?>>
    <head></head>
    <body>
        <script>
           let appCache = window.applicationCache;
            function updateCache(){
                appCache.update();
            }
            appCache.addEventListener("updateready",function(){
                try {
                    appCache.swapCache();
                    //alert("nueva version");
                }catch(e) {
                    console.log(e.code);
                }
            }, false);
            appCache.oncached = function (e) {};
            appCache.onerror = function (e) {
                console.log(e);
            };
        </script>
    </body>
</html>
