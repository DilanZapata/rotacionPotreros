<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\rotacionController;
    
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("location:".APP_URL_BASE);
        exit;
        
    }else {
        if(isset($_POST['modulo_rotacion'])){
    
            $insProducto = new rotacionController();
    
            if($_POST['modulo_rotacion']=="registrar"){
                echo $insProducto->registrarRotacionDeLotes();
            }
            
        }else{
            session_destroy();
            header("Location: ".APP_URL."login/");
        }
    }