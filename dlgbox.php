<!DOCTYPE html>
<?php
    include_once './include/config.inc.php';
    include_once './include/message.inc.php';
    include_once './libs/app.widgets/bs/TBootstrapAlerts.class.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>::<?php echo APPTITLE . " - " . APPSUBTITLE; ?>::</title>
        <script src="https://maps.google.com/maps/api/js?key=AIzaSyAx6ljuc3jhDPSJ1qLI4JUGF3BZx0UPfR8&signed_in=true"></script>
        
        <?php 
            echo $cHead["css"]["bootstrap"]["default"];
            echo $cHead["js"]["jquery"];
            echo $cHead["js"]["bootstrap"];
            echo $cHead["css"]["font"];
            
            if (isset($_GET["libs"])){
                $lbs = filter_input(INPUT_GET, "libs");
                switch ($lbs) {
                    case "dtables":
                        echo $cHead["css"]["datatables"]["bootstrap"];
                        echo $cHead["js"]["datatables"]["jquery"];
                        echo $cHead["js"]["datatables"]["bootstrap"];
                        echo $cHead["js"]["maskedit"];
                        break;
                    default:
                        break;
                }
            }

        ?>
    </head>
    <body>
        <div id="app" class="container-fluid">
        <?php
            session_start();
            if (!isset($_SESSION["status"])){
                if (isset($_GET["v"])){
                    $view = filter_input(INPUT_GET, "v");
                    
                    try {
                        if (file_exists("./view/{$view}.php")){
                            include_once "./view/{$view}.php";
                        }
                        else {
                            echo (new TBootStrapAlerts)->show("<p><strong>Erro!</strong>&nbsp;Não foi possível carregar o formulário informado.</p>", 
                                                              "warning", "<h4>Erro</h4>");
                        }
                    } catch (Exception $e) {
                        echo  (new TBootStrapAlerts)->show("<p><strong>Erro!</strong>&nbsp;Não foi possível carregar o formulário informado.<br>" . 
                                                           "Mensagem: {$e->getMessage()}</p>", 
                                                           "warning", "<h4>Erro</h4>");
                    }
                }
                else {
                    echo (new TBootStrapAlerts)->show("<p><strong>Erro!</strong>&nbsp;Não foi possível carregar o formulário corretamente.</p>", 
                                                      "warning", "<h4>Erro</h4>");
                }
            }
            else {
                echo (new TBootStrapAlerts)->show("<p><strong>Erro!</strong>&nbsp;Login não foi executado. Efetue o login e tente novamente.</p>", 
                                                  "warning", "<h4>Erro</h4>");
            }
        ?>
        </div>
    </body>
</html>
