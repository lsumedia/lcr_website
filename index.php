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
        
        <main id="dynamic-main">
            
        </main>
        
        <noscript>
        <h4>Error: JavaScript not enabled</h4>
        </noscript>
        
        <div id="player-container"></div>
        
        <div id="iframe-container"></div>
        
        <script>
            var player = new lcrPlayer();
            player.load(54);
        </script>
        
        <?php //require("components/footer.php"); ?>
        
    </body>
</html>