<?php

    namespace app\controllers;
    use app\models\mainModel;
	
	class rotacionController extends mainModel{

		/*---------- Controlador de rotaciones ----------*/
		public function registrarRotacionDeLotes(){

        

            /*---------- Validaccion de fecha y hora --------------*/
            if (!isset($_POST['fecha_rotacion'],
            $_POST['num_lote'],
            $_POST['num_potrero'])
            ||
            $_POST['fecha_rotacion'] == "" ||
            $_POST['num_lote'] == "" ) {
                $mensaje=[
                    "titulo"=>"Error",
                    "mensaje"=>"Lo sentimos, a ocurrido un error con alguno de los datos, intentalo de nuevo mas tarde.",
                    "icono"=> "error",
                    "tipoMensaje"=>"normal"
                ];
                return json_encode($mensaje);
                exit();
            }else {

                $fecha_actual = date('Y-m-d');
                $hora_actual = date('H:i:s');
                $fecha_antes_3 = strtotime('-3 days', strtotime($fecha_actual));
                $fecha_minima = date('Y-m-d', $fecha_antes_3);

                if (strlen($_POST['fecha_rotacion']) < 10 || strlen($_POST['fecha_rotacion']) > 10 || strtotime($_POST['fecha_rotacion']) < strtotime($fecha_minima)) {
                    $mensaje=[
                        "titulo"=>"Campos incompletos",
                        "mensaje"=>"Lo sentimos, la FECHA DE ROTACION no cumplen con el formato solicitado.",
                        "icono"=> "error",
                        "tipoMensaje"=>"normal"
                    ];
                    return json_encode($mensaje);
                    exit();
                }else {
                    $fecha_rotacion = $this->limpiarDatos($_POST['fecha_rotacion']);
                    unset($_POST['fecha_rotacion']);
                }
                $num_lote = $this->limpiarDatos($_POST['num_lote']) ;
                $potrero_entrante = $this->limpiarDatos($_POST['num_potrero']);
                unset($_POST['num_potrero'],$_POST['num_lote']);
                

                $consulta = "SELECT potrero_actual FROM `lotes` WHERE `estado_lote` = 'ACTIVO' AND `nombre_lote` = '$num_lote'";
                $buscar_lote = $this->ejecutarConsulta($consulta);
                unset($consulta);


                if (!$buscar_lote) {
                    $mensaje=[
                        "titulo"=>"Error de Conexion",
                        "mensaje"=>"Lo sentimos, algo salio mal con la conexion por favor intentalo de nuevo mas tarde.",
                        "icono"=> "error",
                        "tipoMensaje"=>"normal"
                    ];
                    return json_encode($mensaje);
                    exit();
                }else {
                    if ($buscar_lote->num_rows < 1) {
                        $mensaje=[
                            "titulo"=>"Error al registrar",
                            "mensaje"=>"Lo sentimos, algo salio mal con el registro tal parece que el lote de vacas que seleccionaste no existe en nuestra base de datos.",
                            "icono"=> "error",
                            "tipoMensaje"=>"normal"
                        ];
                        return json_encode($mensaje);
                        exit();
                    }else {
                        $datos_lote = $buscar_lote->fetch_assoc();
                        $buscar_lote->free();
                        unset($buscar_lote);

                        $potrero_saliente = $datos_lote['potrero_actual'];
                        unset($datos_lote);
                        $fecha_hora = $fecha_rotacion.' '.$hora_actual;

                        /*---------- Acutualizar estado potrero actual a desocupado-------------*/
                        $sentencia_ptr_salida= "UPDATE `potreros` SET `fecha_hora_ultm_sal`='$fecha_hora',`estado_potrero`='Libre',`lote_vacas`='$num_lote' WHERE `num_potrero` = '$potrero_saliente'";
                        $actualizar_potrero_actual = $this->ejecutarConsulta($sentencia_ptr_salida);
                        unset($sentencia_ptr_salida);

                        if (!$actualizar_potrero_actual) {
                            $mensaje=[
                                "titulo"=>"Error de Conexion",
                                "mensaje"=>"Lo sentimos, algo salio mal con la conexion al actualizar el estado de el potrero saliente por favor intentalo de nuevo mas tarde.",
                                "icono"=> "error",
                                "tipoMensaje"=>"normal"
                            ];
                            return json_encode($mensaje);
                            exit();
                        }else {
                            unset($actualizar_potrero_actual);

                            /*---------- Acutualizar estado potrero entrante a ocupado-------------*/
                            $sentencia_ptr_entrada= "UPDATE `potreros` SET `fehca_hora_ultm_ent`='$fecha_hora',`estado_potrero`='Ocupado',`lote_vacas`='$num_lote' WHERE `num_potrero` = '$potrero_entrante'";
                            $actualizar_potrero_ingreso = $this->ejecutarConsulta($sentencia_ptr_entrada);
                            unset($sentencia_ptr_entrada);

                            if (!$actualizar_potrero_ingreso) {
                                $mensaje=[
                                    "titulo"=>"Error de Conexion",
                                    "mensaje"=>"Lo sentimos, algo salio mal con la conexion al actualizar el estado de el potrero ingreso por favor intentalo de nuevo mas tarde.",
                                    "icono"=> "error",
                                    "tipoMensaje"=>"normal"
                                ];
                                return json_encode($mensaje);
                                exit();
                            }else {
                                unset($actualizar_potrero_ingreso); 

                                /*---------- Acutualizar potrero actual de el lote colocaldo el potrero de ingreso\entrante-------------*/
                                $sentencia_lte_entrada= "UPDATE `lotes` SET `potrero_actual`='$potrero_entrante' WHERE `nombre_lote` = '$num_lote'";
                                
                        
                                $actualizar_lote_rotado = $this->ejecutarConsulta($sentencia_lte_entrada);
                                unset($sentencia_lte_entrada);
                                if (!$actualizar_lote_rotado ) {
                                    $mensaje=[
                                        "titulo"=>"Error de Conexion",
                                        "mensaje"=>"Lo sentimos, algo salio mal con la conexion al actualizar el potrero ingreso por favor intentalo de nuevo mas tarde.",
                                        "icono"=> "error",
                                        "tipoMensaje"=>"normal"
                                    ];
                                    return json_encode($mensaje);
                                    exit();
                                }else {
                                    unset($sentencia_lte_entrada);
                                    /*------- consultar registros de potrero salida ----------*/
                                    if ($potrero_saliente == '000') {
                                        $potrero_saliente = 'Ninguno';
                                        $fecha_hora_ultm_sal_ps = '0000-00-00 00:00:00';
                                        $fecha_hora_ultm_ent_ps = '0000-00-00 00:00:00';
                                    }else{
                                        $sentencia_potrero_salida = "SELECT `fecha_hora_ultm_sal`, `fehca_hora_ultm_ent` FROM `potreros` WHERE `num_potrero` = '$potrero_saliente'";
                                        
                                        $potrero_salida = $this->ejecutarConsulta($sentencia_potrero_salida);
                                        
                                        unset($sentencia_potrero_salida);
                                        if (!$potrero_salida) {
                                            $mensaje=[
                                                "titulo"=>"Error de Conexion",
                                                "mensaje"=>"Lo sentimos, algo salio mal con la conexion al intentar recuperar los datos del potrero saliente.",
                                                "icono"=> "error",
                                                "tipoMensaje"=>"normal"
                                            ];
                                            return json_encode($mensaje);
                                            exit();
                                        }else {
                                            if ($potrero_salida->num_rows < 1) {
                                                $mensaje=[
                                                    "titulo"=>"Error",
                                                    "mensaje"=>"Lo sentimos, algo salio mal con el potrero saliente tal parece que no existe en nuestra base de datos.",
                                                    "icono"=> "error",
                                                    "tipoMensaje"=>"normal"
                                                ];
                                                return json_encode($mensaje);
                                                exit();
                                            }else {
                                                $datos_potrero_salida = $potrero_salida->fetch_row();
                                                
                                                unset($potrero_salida);         
                        
                                                $fecha_hora_ultm_sal_ps = $datos_potrero_salida[0];
                        
                                                $fecha_hora_ultm_ent_ps = $datos_potrero_salida[1];
                                                
                                            }
                                            
    
                                            
                                        }
                                        /*------- final consultar registros de potrero salida ----------*/

                                    }

                                
                                    /*------- consultar registros de potrero entrada ----------*/
                                    $sentencia_potrero_entrada = "SELECT `fecha_hora_ultm_sal`, `fehca_hora_ultm_ent` FROM `potreros` WHERE `num_potrero` = '$potrero_entrante'";
                                    
                                    $potrero_entrada = $this->ejecutarConsulta($sentencia_potrero_entrada);
                                    
                                    unset($sentencia_potrero_entrada);
                                    if (!$potrero_entrada) {
                                        $mensaje=[
                                            "titulo"=>"Error de Conexion",
                                            "mensaje"=>"Lo sentimos, algo salio mal con la conexion al intentar recuperar los datos del potrero entrante.",
                                            "icono"=> "error",
                                            "tipoMensaje"=>"normal"
                                        ];
                                        return json_encode($mensaje);
                                        exit();
                                    }else {
                                        if ($potrero_entrada->num_rows < 1) {
                                            $mensaje=[
                                                "titulo"=>"Error",
                                                "mensaje"=>"Lo sentimos, algo salio mal con el potrero saliente tal parece que no existe en nuestra base de datos.",
                                                "icono"=> "error",
                                                "tipoMensaje"=>"normal"
                                            ];
                                            return json_encode($mensaje);
                                            exit();
                                        }else {
                                            $datos_potrero_entrada = $potrero_entrada->fetch_row();
                                            
                                            unset($potrero_entrada);         
                    
                                            $fecha_hora_ultm_sal_pe = $datos_potrero_entrada[0];
                    
                                            $fecha_hora_ultm_ent_pe = $datos_potrero_entrada[1];
                                            
                                        }
                                    }
                                    /*------- final consultar registros de potrero entrada ----------*/

                                    $fechaHoraActual = date('Y-m-d H:i:s');
                                    /*Registro de reporte de salida*/
                                    $sentencia_reg_sld = "INSERT INTO `registro`( `tipo_registro`, `fecha_hora_registro`, `num_potrero`, `fecha_hora_ultm_sal`, `fecha_hora_ultm_ent`, `estado_potrero`, `lote_vacas`, `empleado`) VALUES ('SALIDA','$fechaHoraActual','$potrero_saliente','$fecha_hora_ultm_sal_ps','$fecha_hora_ultm_ent_ps','Dias de Ocupacion','$num_lote', '".$_SESSION['num_identificacion_user']."')";
                                    
                                    $registro_sld = $this->ejecutarConsulta($sentencia_reg_sld);
        
                                    if (!$registro_sld) {
                                        $mensaje=[
                                            "titulo"=>"Error de Conexion",
                                            "mensaje"=>"Lo sentimos, algo salio mal con la conexion al intentar registrar la rotacion.",
                                            "icono"=> "error",
                                            "tipoMensaje"=>"normal"
                                        ];
                                        return json_encode($mensaje);
                                        exit();
                                    }else {
                                        $sentencia_reg_sld = "INSERT INTO `registro`( `tipo_registro`, `fecha_hora_registro`, `num_potrero`, `fecha_hora_ultm_sal`, `fecha_hora_ultm_ent`, `estado_potrero`, `lote_vacas`, `empleado`) VALUES ('ENTRADA','$fechaHoraActual','$potrero_entrante','$fecha_hora_ultm_sal_pe','$fecha_hora_ultm_ent_pe','Dias de Descanso','$num_lote', '".$_SESSION['num_identificacion_user']."')";
                                    
                                        $registro_ent = $this->ejecutarConsulta($sentencia_reg_sld);
        
                                        if (!$registro_ent) {
                                            $mensaje=[
                                                "titulo"=>"Error de Conexion",
                                                "mensaje"=>"Lo sentimos, algo salio mal con la conexion al intentar registrar la rotacion.",
                                                "icono"=> "error",
                                                "tipoMensaje"=>"normal"
                                            ];
                                            return json_encode($mensaje);
                                            exit();
                                        }else {
                                            $mensaje=[
                                                "titulo"=>"Rotacion exitosa",
                                                "mensaje"=>"Te lo agradecesos, tu informacion es muy valisa para nosotros.",
                                                "icono"=> "success",
                                                "url"=> "".APP_URL_BASE."registrar-rotacion/",
                                                "tipoMensaje"=>"redireccionar"
                                            ];
                                            return json_encode($mensaje);
                                            exit();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
	    }

        public function listarOpcionesLotes(){
            
            $selec_lotes = $this->ejecutarConsulta("SELECT * FROM `lotes` WHERE `estado_lote` = 'ACTIVO'");
            if (!$selec_lotes) {
                $mensaje=[
                    "titulo"=>"Error de Conexion",
                    "mensaje"=>"Lo sentimos, algo salio mal con la conexion al intentar mostrar los lotes de vacas.",
                    "icono"=> "error",
                    "tipoMensaje"=>"normal"
                ];
                return json_encode($mensaje);
                exit();
            }else {
                return $selec_lotes;
            }

        }

        public function listarOpcionesPotreros(){
            $selec_potreros = $this->ejecutarConsulta("SELECT * FROM `potreros` WHERE `estado_potrero` = 'Libre'");
            if (!$selec_potreros) {
                $mensaje=[
                    "titulo"=>"Error de Conexion",
                    "mensaje"=>"Lo sentimos, algo salio mal con la conexion al intentar mostrar los lotes de vacas.",
                    "icono"=> "error",
                    "tipoMensaje"=>"normal"
                ];
                return json_encode($mensaje);
                exit();
            }else {
                return $selec_potreros;
            }
        }
    }