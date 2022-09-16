
urlgeeneral=$("#url_raiz_proyecto").val();
window.addEventListener("load", function (event) {

    //listadocategorias();
    $(".loader").fadeOut("slow"); 
   

    });



     //METODO PARA CARGAR LOS CLIENTES
     $.get(urlgeeneral+"/viculo-familiar/persona",function(data){

        var contenido = "";
        for (var i = 0; i < data.length; i++) {

            contenido += "<tr>";
            contenido += "<td style='padding:1px;text-align:center' id='dni"+data[i].id_per+"'>" + data[i].ruc_per + "</td>";
            if(data[i].personeria=="Natural"){

                contenido += "<td style='padding:1px;text-align:center' id='nombres"+data[i].id_per+"'>" + data[i].pate_per +" "+data[i].mate_per+" "+data[i].nomb_per +"</td>";

            }else{
                contenido += "<td style='padding:1px;text-align:center' id='nombre"+data[i].id_per+"'>" +data[i].nomb_per +"</td>";

            }

            contenido += "<td style='padding:1px;text-align:center' id='dni"+data[i].id_per+"'>" + data[i].es_socio + "</td>";

            contenido += "<td style='padding:1px;text-align:center'>";
            contenido +='<a href="#" onclick="seleccionar('+data[i].id_per+')" type="button" class="btn btn-success "><i class="fas fa-hand-pointer"></i> </a>';
            contenido +="</td>"

            contenido += "</tr>";

        }
        document.getElementById("listaclientes").innerHTML = contenido;
        $("#datatablecliente").DataTable();



    });

    //METODO PARA CAPTURAR LOS DETALLES DE LA PERSONA
    function seleccionar(param) {  

        var nombres=$("#nombres"+param).text();
        var nombre=$("#nombre"+param).text();
        var d=$("#id_per").val(param);

        alert(param);

        if(nombre==""){

           $("#nomb_pro").val(nombres);

        }else{

            $("#nomb_pro").val(nombre);

        }  

        $(".bs-example-modal-xl").modal("hide");

            Swal.fire({
                icon: 'success',
                title: 'Oops...',
                text: 'El Cliente Seleccionado Correctamente',
                footer: ''
            })

    }

    ///DATOS TEXTAREA

        CKEDITOR.config.height = 400;
		CKEDITOR.config.width  = 'auto';
		CKEDITOR.replace('descripcion');