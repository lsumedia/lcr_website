<!doctype html>
<html>
    <head>
       <?php 
            //Page path name
            $pagename = "index";
            //Include config page
            require_once("config.php");
            //Include header
            require("components/header.php"); 
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
            pages.loadPage('video&play=80');
            //player.load(-1);
        </script>
        
        <?php //require("components/footer.php"); ?>
        
    </body>
</html>