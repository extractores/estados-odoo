urlgeeneral=$("#url_raiz_proyecto").val();


window.addEventListener("load", function (event) {


    datos();
    //$(".loader").fadeOut("slow");
    //clientes();


  });


  function datos(){

    $.get(urlgeeneral+"/clientes/listado",function(data){

        llenartabla(data);



    });

  }

  function llenartabla(data){

    var contenido = "";
    for (var i = 0; i < data.length; i++) {

      contenido += "<tr>";


      contenido += "<td style='padding:1px;text-align:center'>" + data[i].companyId + "</td>";

      contenido += "<td style='padding:1px;text-align:center'>" + data[i].paternalSurname + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].maternalSurname + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].name + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].documentType + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].documentNumber + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].address + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].latitude + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].longitude + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].routeId + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].clientCode + "</td>";





      contenido += "</tr>";


    }

    document.getElementById("listadecolores").innerHTML = contenido;
    $("#datatable").DataTable();



}


function clientes(){

    $.get(urlgeeneral+"/clientes/info",function(data){

        //console.log(data);

        var csrf = document.querySelector('meta[name="csrf-token"]').content;
            $.ajax({
                type: "PUT",
                url: urlgeeneral+"/clientes/crear",
                data: {"data":data, '_token': csrf},
                dataType: 'json',
                success: function (datas) {

                console.log(datas);
                }

            });

    });
}

//SICRONIZAR CONCEPTOS
function sicronizarconceptos(){

    clientes();

  }

  var intervalorecibos=6000;
  //setInterval(sicronizarconceptos, intervalorecibos);
