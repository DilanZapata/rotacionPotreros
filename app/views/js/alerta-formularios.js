
const formularios_ajax=document.querySelectorAll(".formulario-fetch");


formularios_ajax.forEach(formularios => {
    formularios.addEventListener("submit",function(e){
       
        e.preventDefault();
        let data = new FormData(this);
        let method=this.getAttribute("method");
        let action=this.getAttribute("action");

        let encabezados= new Headers();

        let config={
            method: method,
            headers: encabezados,
            mode: 'cors',
            cache: 'no-cache',
            body: data
        };

        fetch(action,config)
        .then(respuesta => respuesta.json())
        .then(respuesta =>{ 
            return manejo_de_alertas(respuesta);
        });

    });

});

function manejo_de_alertas(respuesta){
    if(respuesta.tipoMensaje =="normal"){

        Swal.fire({
            icon: respuesta.icono,
            title: respuesta.titulo,
            text: respuesta.mensaje,
            confirmButtonText: 'Aceptar'
        });

    }else if(respuesta.tipoMensaje == "confirmado"){

        Swal.fire({
            icon: respuesta.icono,
            title: respuesta.titulo,
            text: respuesta.mensaje,
            timer: 2000,
            confirmButtonText: 'Aceptar'
        });

    }else if(respuesta.tipoMensaje =="recargar"){
        Swal.fire({
            icon: respuesta.icono,
            title: respuesta.titulo,
            text: respuesta.mensaje,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                location.reload();
            }
        });

    }else if(respuesta.tipoMensaje =="limpiar"){

        Swal.fire({
            icon: respuesta.icono,
            title: respuesta.titulo,
            text: respuesta.mensaje,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                document.querySelector(".Formulario-fetch").reset();
            }
        });

    }else if(respuesta.tipoMensaje =="redireccionar"){

        Swal.fire({
            icon: respuesta.icono,
            title: respuesta.titulo,
            text: respuesta.mensaje,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                    window.location.href=respuesta.url;
            }
        });
    }
}
