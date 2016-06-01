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
            require("components/header.php"); 

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
        
        <div id="player-container"></div>
        
        <div id="iframe-container"></div>
        
        <script>
            var player = new audioPlayer();
            
            var pages = new dynamicPages('dynamic-main', player);
            pages.loadPage('<?= $action ?>');
            //player.load(-1);
        </script>
        
        <?php //require("components/footer.php"); ?>
        
    </body>
</html>