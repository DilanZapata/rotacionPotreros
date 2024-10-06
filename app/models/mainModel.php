<?php
	
	namespace app\models;
	use \mysqli;

	if(file_exists(__DIR__."/../../config/server.php")){
		require_once __DIR__."/../../config/server.php";
	}

	class mainModel{

		protected function conectar(){
			
			mysqli_report(MYSQLI_REPORT_OFF);
			
			try {
				$enlace_conexion = new mysqli("localhost", "root", "", "rotar");
			
				if ($enlace_conexion->connect_error) {
					throw new \Exception("No se realizó la conexión: " . $enlace_conexion->connect_error);
				}else {
					return $enlace_conexion;
				} 
			} catch (\Exception $e) {
				unset($enlace_conexion);
				return false; 
			}
		}


		protected function ejecutarConsulta($consulta){
			$conexion = $this->conectar();
			if (!$conexion) {
				return false;
			}else {
				$sql=$conexion->query($consulta);
				$conexion->close();
				return $sql;
			}
		}
		
		
		public function limpiarDatos($dato){

			$palabras=["<script>","</script>","<script src","<script type=","SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO","DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES","<?php","?>","--","^","<",">","==",";","::"];

			$dato=trim($dato);
			$dato=stripslashes($dato);

			foreach($palabras as $palabra){
				$dato=str_ireplace($palabra, "", $dato);
			}

			$dato=trim($dato);
			$dato=stripslashes($dato);

			return $dato;
		}

		/*---------- Funcion verificar datos (expresion regular) ----------*/
		protected function verificarDatos($filtro,$cadena){
			if(preg_match("/^".$filtro."$/", $cadena)){
				return false;
            }else{
                return true;
            }
		}


		
	    
	}