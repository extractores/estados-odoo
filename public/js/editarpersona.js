urlgeeneral=$("#url_raiz_proyecto").val();
window.addEventListener("load", function (event) {

   
    //datos();
    $(".loader").fadeOut("slow"); 

   
  });


    $(".es_socio").on("change",function(){


          if ($(this).is(':checked')) {
              console.log($(this).val() + ' Si chequeado');
              $("#cantidad").show();

          } else {
              console.log($(this).val() + ' No chequeado');
              $("#cantidad").hide();
          }


  });



  $("#guardar").on("click",function(){

    if (datosobligatorio() == true) {

                var frm = new FormData();
                

                let id_per=$("#id_per").val();
                let pate_per=$("#pate_per").val();
                let mate_per=$("#mate_per").val();
                let nomb_per=$("#nomb_per").val();
                var selecttipo_doc=document.getElementById("tipo_doc"); /*Obtener el SELECT */
                var tipo_doc = selecttipo_doc.options[selecttipo_doc.selectedIndex].value; 
                let ruc_per=$("#ruc_per").val();
                let dni_per=$("#dni_per").val();
                let dire_per=$("#dire_per").val();
                var selectid_sec=document.getElementById("id_sec"); /*Obtener el SELECT */
                var id_sec = selectid_sec.options[selectid_sec.selectedIndex].value; 
                let telefono=$("#telefono").val();
                let es_socio=$(".es_socio").val();
                let selectesta_per=document.getElementById("esta_per");
                let esta_per=selectesta_per.options[selectesta_per.selectedIndex].value;

                alert(es_socio);

               /* frm.append("id_per", id_per);
                frm.append("nomb_per", nomb_per);
                frm.append("pate_per", pate_per);
                frm.append("mate_per", mate_per);
                frm.append("dni_per", dni_per);
                frm.append("dire_per", dire_per);
                frm.append("ruc_per", ruc_per);
                frm.append("esta_per", esta_per);
                frm.append("tipo_doc", tipo_doc);
                frm.append("telefono", telefono);
                frm.append("id_sec", id_sec);
                frm.append("es_socio", es_socio);
       

                $.ajax({
                  type: "POST",
                  url: urlgeeneral+"/mantenimiento-socio/modificar",
                  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                  data: frm,
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  success: function (data) {
  
                      console.log(data);
                        Swal.fire({
                            icon: 'success',
                            title: 'Oops...',
                            text: 'Cliente Modificado Correctamente',
                            footer: ''
                        })

  
                  }
              });*/

        

    }

        
  });













  function datosobligatorio() {
    var bien = true;
  
    var obligarotio = document.getElementsByClassName("obligatorio");
    var ncontroles = obligarotio.length;
  
    for (var i = 0; i < ncontroles; i++) {
        if (obligarotio[i].value == "") {
            bien = false;
            //alert("vacios");
            //obligarotio[i].parentNode.classList.add("form-control error");
            //swal("Here's a message!")
            //swal("Error!", "Los Campos Son Obligatorios!", "error")
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El campo Color es Obligatorio!',
                footer: ''
              })
            //alert("Campos Obligatorios");
            //swal("Error!", "Los Campos Marcados de Rojo son requeridos!", "error")
            //alert("Los datos son Obliatorios");
  
  
        } else {
            obligarotio[i].parentNode.classList.remove("error")
        }
    }
    return bien;
  
    }