<?php

	namespace app\controllers;
	use app\models\mainModel;

	class loginController extends mainModel{

		/*----------  Controlador iniciar sesion  ----------*/
		public function iniciarSesionControlador(){
			if ($_SERVER['REQUEST_METHOD'] != 'POST' ) {
				header("location:".APP_URL_BASE);
				exit;
			}else {
				if (!isset($_POST['num_id_usuario']) || $_POST['num_id_usuario'] == "" || strlen($_POST['num_id_usuario']) < 6 || strlen($_POST['num_id_usuario']) > 15) {
					$mensaje=[
						"titulo"=>"Campos incompletos",
						"mensaje"=>"Lo sentimos, tal parece que TU CEDULA no cumple con el formato solicitado.",
						"icono"=> "error",
						"tipoMensaje"=>"normal"
					];
					return json_encode($mensaje);
					exit();
				}else {
					if ($this->verificarDatos("[0-9]{6,15}", $_POST['num_id_usuario'] )) {
						$mensaje=[
							"titulo"=>"Campos incompletos",
							"mensaje"=>"Lo sentimos, tal parece que TU CEDULA no cumple con el formato solicitado.",
							"icono"=> "error",
							"tipoMensaje"=>"normal"
						];
						return json_encode($mensaje);
						exit();
					}else {
						$num_empleado = $_POST['num_id_usuario'];
						unset($_POST['num_id_usuario']);
						
                                
						$buscar_empleado = $this->ejecutarConsulta("SELECT num_identificacion FROM empleados WHERE num_identificacion ='$num_empleado' AND estado = 'ACTIVO'");
						
						if (!$buscar_empleado) {
							$mensaje=[
								"titulo"=>"Error de Conexion",
								"mensaje"=>"Lo sentimos, algo salio mal con la conexion a la base de datos por favor comunicate con la oficina.",
								"icono"=> "error",
								"tipoMensaje"=>"normal"
							];
							return json_encode($mensaje);
							exit();
						}
						if ($buscar_empleado->num_rows < 1) {
							$mensaje=[
								"titulo"=>"Cedula incorrecta",
								"mensaje"=>"Lo sentimos, algo salio mal tal parece que no existes en nuestra base de datos como empleado si estas seguro de que ya fuiste registrado comunicate con la oficina.",
								"icono"=> "error",
								"tipoMensaje"=>"normal"
							];
							return json_encode($mensaje);
							exit();
						}else {
							$datos_empleado = $buscar_empleado->fetch_assoc();
							$buscar_empleado->free();
							unset($buscar_empleado, $conexion);
							$_SESSION['num_identificacion_user'] = $datos_empleado['num_identificacion'];
							
							if(headers_sent()){
								echo "<script> window.location.href='".APP_URL_BASE."registrar-rotacion/'; </script>";
							}else{
								header("Location: ".APP_URL_BASE."registrar-rotacion/");
							}
						}
					}
				}
			}
			
		}


		/*----------  Controlador cerrar sesion  ----------*/
		public function cerrarSesionControlador(){

			session_destroy();

		    if(headers_sent()){
                echo "<script> window.location.href='".APP_URL_BASE."login/'; </script>";
            }else{
                header("Location: ".APP_URL_BASE."login/");
            }
		}

	}