<?php

    use app\controllers\rotacionController;

    $datos_ptr_lte = new rotacionController();




?>
<section id="contenedor_ppal_rotar" name="contenedor_ppal_rotar">
    <div class="contendor-rotar" id="contenedor_rotar" name="contenedor_rotar">
        <div id="contenedor_form_rotar" name="contenedor_form_rotar">
            <div class="contenedor-logo-rotar" id="contenedor_logo_rotar" name="contenedor_logo">
                <article id="cont_logo_rotar" name="cont_logo_rotar">
                    <figure id="logo_rotar">
                        <img src="<?php echo APP_URL_BASE; ?>app/views/img/isotipo-chiquique-blanco.png" alt="">
                    </figure>
                </article>
            </div>
            <form class="formulario-fetch" action="<?php echo APP_URL_BASE; ?>app/ajax/rotacionAjax.php" method="post"  name="form_acceso_rotar" id="form_acceso_rotar"  enctype="application/x-www-form-urlencoded">
                
		        <input type="hidden" name="modulo_rotacion" value="registrar">
                <div class="contenedor_campos_fecha">
                    <div class="campo_fecha campo-cont">

                        <?php 
                            $fecha_actual = date('Y-m-d');
                            $fecha_menos_3 = strtotime('-3 days', strtotime($fecha_actual));
                            $fecha_minima= date('Y-m-d', $fecha_menos_3);
                        ?>
                        <label for="fecha_rotacion">FECHA REPORTE:</label>
                        <input type="date" name="fecha_rotacion" id="fecha_rotacion" class="campo-fechas" value="<?php echo $fecha_actual;?>" min="<?php echo $fecha_minima;?>" max="<?php echo $fecha_actual?>" tabindex="1" required>
                    </div>
                </div>
                    
                <label for="">LOTE DE VACAS.</label>
                <select name="num_lote" id="num_lote" class="campo campo-select" title="Debes selecionar el lote de vacas que va a cambiar de potrero" tabindex="2" required>
    
                    <option value="">Selecciona un lote</option>
                    <?php  

                        $selec_lotes = $datos_ptr_lte->listarOpcionesLotes();


                        if ($selec_lotes->num_rows == 0) { // Validación de respuesta vacía o cero
                            echo "<option >No se encontró ningún lote activo</option>";
                        } else {
                            for ($i = 0; $i < $selec_lotes->num_rows; $i++) { 
                                $datos = $selec_lotes->fetch_row(); ?>
                                <option value="<?= $datos[1] ?>"> LOTE VACAS <?= $datos[1] ?></option>
                            <?php } 
                        }
                    ?>
                </select>
                    
                <label for="">POTRERO DE INGRESO.</label>
                <select name="num_potrero" id="num_potrero" class="campo campo-select" title="Debes selecionar el lote de vacas que va a cambiar de potrero" tabindex="3" required>
                    
                    <option value="">Selecciona potrero</option>
                    <?php  
                    
                        $selec_potreros = $datos_ptr_lte->listarOpcionesPotreros();
                        if ($selec_potreros->num_rows == 0) { // Validación de respuesta vacía o cero
                            echo "<option >No se encontró ningún potrero activo</option>";
                        } else {
                            for ($i = 0; $i < $selec_potreros->num_rows; $i++) { 
                                $datos = $selec_potreros->fetch_row(); ?>
                                <option value="<?= $datos[1] ?>">POTRERO <?= $datos[1] ?></option>
                            <?php } 
                        }
                    ?>
                </select>
                <button type="submit" id="btn_inicio_sesion" >ROTAR</button>
            </form>
            <article id="cont_logo_zobyte">
                <figure id="figure_logo_zobyte">
                    <img id="logo_zobyte" src="<?php echo APP_URL_BASE; ?>app/views/img/imagotipo-zobyte-soluction-negro.png" alt="">
                </figure>
            </article>
        </div>
        
    </div>
</section>