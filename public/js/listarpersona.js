urlgeeneral=$("#url_raiz_proyecto").val();
window.addEventListener("load", function (event) {

   
    datos();
    $(".loader").fadeOut("slow"); 

   
  })

  function datos(){

    $.get(urlgeeneral+"/viculo-familiar/persona",function(data){

        llenartabla(data);



    });

  }

  function llenartabla(data){

      var contenido = "";
      for (var i = 0; i < data.length; i++) {

        contenido += "<tr>";
        contenido += "<td style='padding:1px;text-align:center;'>" +  data[i].id_per+ "</td>";

        contenido += "<td style='padding:1px;text-align:center'>" + data[i].pate_per + "</td>";

        contenido += "<td style='padding:1px;text-align:center'>" + data[i].mate_per + "</td>";
        contenido += "<td style='padding:1px;text-align:center'>" + data[i].nomb_per + "</td>";
        contenido += "<td style='padding:1px;text-align:center'>" + data[i].dni_per + "</td>";
        contenido += "<td style='padding:1px;text-align:center'>" + data[i].dire_per + "</td>";
        contenido += "<td style='padding:1px;text-align:center'>" + data[i].ruc_per + "</td>";
        contenido += "<td style='padding:1px;text-align:center'>" + data[i].nomb_sec + "</td>";
        contenido += "<td style='padding:1px;text-align:center'>" + data[i].esta_per + "</td>";
 

        contenido +="<td>";

        contenido +='<a href="mantenimiento-socio/'+data[i].id_per+'" type="button" class="btn btn-success "><i class="fab fa-searchengin"></i> </a>';
        contenido +='<a href="mantenimiento-socio/'+data[i].id_per+'/edit" type="button" class="btn btn-info "><i class="fas fa-edit"></i> </a>';
        contenido +='<button type="button" onclick="eliminarvinculo('+ data[i].id_per +')" class="btn btn-danger  eliminar"><i class="fa fa-trash eliminar" aria-hidden="true"></i> </button>'
        contenido  +="</td>";


        contenido += "</tr>";


      }

      document.getElementById("listadopersonas").innerHTML = contenido;
      $("#datatable").DataTable();



  }

  //FUNCION FILTRO POR COMITES 
  $("#id_sector").on("change",function(){

      

       
  });