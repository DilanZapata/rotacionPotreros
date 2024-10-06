<?php

    require_once "./config/app.php";
    require_once "./autoload.php";


    /*---------- Iniciando sesion ----------*/
    require_once "./app/views/inc/session_start.php";

    if(isset($_GET['views'])){
        $url=explode("/", $_GET['views']);
    }else{
        $url=["login"];
    }
    use app\controllers\loginController;
    use app\controllers\viewsController;
    $viewsController= new viewsController();

    
    $insLogin = new loginController();
    $vista=$viewsController->obtenerVistasControlador($url[0]);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once "./app/views/inc/head.php"; ?>
</head>
<body>
    <?php
  
        if($vista=="login" || $vista=="404"){
            require_once "./app/views/content/".$vista."-view.php";
        }else{
    ?>
    <main class="page-container">
    <?php
            # Cerrar sesion #
            if((!isset($_SESSION['num_identificacion_user']) || $_SESSION['num_identificacion_user']=="") ){
                $insLogin->cerrarSesionControlador();
                exit();
            }
    ?>      
        <section class="full-width pageContent scroll" id="pageContent">
            
            <?php
            
   
                require_once $vista;
            ?>
        </section>
    </main>
    <?php
        }

    ?>
    
    <script src="<?php echo APP_URL_BASE; ?>app/views/js/alerta-formularios.js"></script>
</body>
</html>