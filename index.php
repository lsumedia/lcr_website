<?php
/* Error display settings */
if(0 || isset($_GET['debug'])){	//Error reporting - disable for production
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
}else{
    ini_set('display_errors', '0');     //don't show any errors...
}
?>
<!doctype html>
<html>
    <head>
       <?php 
            //Page path name
            $pagename = "index";
            //Include config page
            require_once("config.php");
            require_once("components/functions.php");
            //Include header
            require_once("components/header.php"); 
            //OpenGraph Data wrangler
            require_once("components/opengraph.php");

            if(isset($_GET['action'])){
                $action = encode_get_string();
            }else{
                $action = 'home';
            }

        ?>
    </head>
    <body>
        <!-- Navigation section -->
        <?php require("components/nav.php"); ?>
        
        
        <main id="dynamic-main" class="container">
        </main>
        
        <noscript>
        <h4>Error: JavaScript not enabled</h4>
        </noscript>
        
        
        <script>
            var player = new audioPlayer();
            
            var pages = new dynamicPages('dynamic-main', player);
            pages.loadPage('<?= $action ?>');
            
            //Load last page when back button is pressed
            window.onpopstate = function(){
                pages.loadPage(pages.getWindowAction(), 'back');
            }
            
            //Warn user before leaving the site
            window.onbeforeunload = "Leaving the page will stop media playback. Are you sure you want to continue?";
            
        </script>
        
        <?php //require("components/footer.php"); ?>
      
    </body>
</html>