<!doctype html>
<html>
    <head>
       <?php 
            $pagename = "error";
            require_once("config.php");
            $pagetitle = "LSUTV - Error";
            require("components/header.php"); 
        ?>
    </head>
    <body>
        <!-- Navigation section -->
        <?php require("components/nav.php"); ?>
        
        <!-- Content section -->
        <main class="container" id="main-content">
            <h4>404 Error - Page not found</h4>
            <p>The page you requested does not exist. Try <a href="./search">searching</a> the site.</p>
        </main>
        
        <?php require("components/footer.php"); ?>
        
    </body>
</html>