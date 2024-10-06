<section id="contenedor_ppal_login" name="contenedor_ppal_login">
    <div class="contendor-login" id="contenedor_login" name="contenedor_login">
        <div id="contenedor_form" name="contenedor_form">
            <div class="contenedor-logo" id="contenedor_logo" name="contenedor_logo">
                <article id="cont_logo" name="cont_logo">
                    <figure id="logo">
                        <img src="<?php echo APP_URL_BASE; ?>app/views/img/isotipo-chiquique-blanco.png" alt="">
                    </figure>
                </article>
            </div>
            <form action="" method="post"  name="form_acceso" id="form_acceso"  enctype="application/x-www-form-urlencoded">
                <?php
                    if (isset($_POST['num_id_usuario'])) {
                            $insLogin->iniciarSesionControlador();
                    }

                ?>
                <label for="num_id_usuario">TU CEDULA</label>
                <img src="app/views/img/icon-usuario.svg" id="icon_usuario" alt="">
                <input type="text" class="campo" name="num_id_usuario" id="num_id_usuario" inputmode="numeric" pattern="[0-9]{6,15}" maxlength="15" minlength="6" title="Debes digitar solo numeros y minimo 6 digitos y maximo 15 digitos" placeholder="# Identificacion" required tabindex="1">

                <button type="submit" id="btn_inicio_sesion">CONTINUAR</button>
                <a href="#" id="enlace_no_cuenta">No tienes una cuenta?</a>
            </form>
        </div>
        
    </div>
</section>