urlgeeneral=$("#url_raiz_proyecto").val();
$("#actualizar").hide();

  //FUNCION LOAD
  window.addEventListener("load", function (event) {

    //listadocategorias();
    listadovenculo();
    $(".loader").fadeOut("slow"); 

   
  });

  $("#save-btn").click(function() { 

   var contenido=['LIMA.01.132233|015456| | | |PTO DE MERCADO|A|0110|01251|LIMA-LIMA-LIMA|2CL|02-Martes    |A-Activo||'];
    var blob = new Blob([contenido], {type: "text/plain;charset=utf-8"});
    saveAs(blob, "testfile1.txt");

  });
  
  function listadovenculo(){

      $.get(urlgeeneral+"/viculo-familiar/listaparentesco",function(data){

            llenarvinculo(data);
      });
  }
  function llenarvinculo(data){

    var contenido = "";
    
    for (var i = 0; i < data.length; i++) {
      
      contenido += "<tr>";
      contenido += "<td style='padding:1px;text-align:center;'>" +  parseInt(i+1,10) + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].nombreparentesco + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].fecha_nacimiento + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].descripcion + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].sexo + "</td>";
      contenido += "<td style='padding:1px;text-align:center'>" + data[i].nivel + "</td>";
      if( data[i].nivellexico=="SI"){
      
        contenido += "<td style='padding:1px;text-align:center'>SABE LEER</td>";
       

      }else{

        contenido += "<td style='padding:1px;text-align:center'>NO SABE LEER</td>";


      }

      contenido +="<td>";

      contenido +='<a href="viculo-familiar/'+data[i].id_per+'" type="button" class="btn btn-success "><i class="fab fa-searchengin"></i> </a>';
      contenido +='<button type="button" onclick="eliminarvinculo('+ data[i].id_per +')" class="btn btn-danger   eliminar"><i class="fa fa-trash eliminar" aria-hidden="true"></i> </button>'
      contenido  +="</td>";
      contenido += "</tr>";

    }

    
    document.getElementById("listadecolores").innerHTML = contenido;
    $("#datatable").DataTable();


  }


  //METODO PARA ELIMINAR DATOS 
  function eliminarvinculo(id){

      const tabla = document.getElementById('datatable');
      tabla.addEventListener('click', (e) => {
        if (e.target.classList.contains('eliminar') || e.target.classList.contains('bx')) {
            Swal.fire({
                title: '¿Desea eliminar el Vinculo Familiar?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.isConfirmed) {
                //Metodo para eleminar
              var csrf = document.querySelector('meta[name="csrf-token"]').content;
                  $.ajax({
                    type: "POST",
                    url: "viculo-familiar/eliminar/"+id,
                    data: {"_method": "delete",'_token': csrf},
                    
                    success: function (data) {

                      listadovenculo();
                      
                      Swal.fire(
                        'Eliminado!',
                        'El servicio ha sido eliminado.',
                        'success'
                      )
                        
                    
                    }

                });


                


                }
              })
        }
    })


  }