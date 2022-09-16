urlgeeneral=$("#url_raiz_proyecto").val();

 //FUNCION LOAD
 window.addEventListener("load", function (event) {

   
    datos();
    $(".loader").fadeOut("slow"); 

   
  });

  function datos(){

    $.get(urlgeeneral+"/beneficios-social/listabeneficios",function(data){

        llenartabla(data);



    });

  }

  function llenartabla(data){

      var contenido = "";
      for (var i = 0; i < data.length; i++) {

        contenido += "<tr>";
        contenido += "<td style='padding:1px;text-align:center;'>" +  parseInt(i+1,10) + "</td>";

        contenido += "<td style='padding:1px;text-align:center'>" + data[i].nomb_per + "</td>";

        contenido += "<td style='padding:1px;text-align:center'>" + data[i].nomb_sec + "</td>";
        contenido += "<td style='padding:1px;text-align:center'>" + data[i].descripcion + "</td>";
        contenido += "<td style='padding:1px;text-align:center'>" + data[i].estado + "</td>";
        contenido += "<td style='text-align:center'>";

        contenido +='<a href="'+urlgeeneral+'/documento/'+data[i].documento+'" type="button" target="_blank" class="btn btn-success "><i class="fa fa-download" aria-hidden="true"></i></a>';
        
        contenido +="</td>";

        contenido +="<td>";

        contenido +='<a href="beneficios-social/'+data[i].id+'" type="button" class="btn btn-success "><i class="fab fa-searchengin"></i> </a>';
        contenido +='<button type="button" onclick="eliminarvinculo('+ data[i].id +')" class="btn btn-danger  eliminar"><i class="fa fa-trash eliminar" aria-hidden="true"></i> </button>'
        contenido  +="</td>";


        contenido += "</tr>";


      }

      document.getElementById("listadecolores").innerHTML = contenido;
      $("#datatable").DataTable();



  }