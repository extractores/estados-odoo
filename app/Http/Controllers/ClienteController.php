<?php

namespace App\Http\Controllers;

use App\cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Exports\ClienteExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Promo_cabecera;
use Illuminate\Support\Facades\Storage;
use App\Productos;
use App\Stock;
use App\fabricante;
use App\pedido_cab;
use App\pedido_det;
use App\Vendedores;
use App\jefeventas;
use App\supervisor;
use App\Compras;
use App\Detalle_compras;
use DB;
use App\Clientes;
use Carbon\Carbon;
use App\stocktime;
use App\giro;
use App\motivosrechazo;
use App\Categorias;
use App\subcategoria;
use App\lineas;
use App\supervisores;
use App\Almacenes;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     ///json_decode(=$this->token(),true)
     //$datos=$this->token();
     //$r=json_decode($datos,true);


     protected $toke=0;

    public function index()
    {
        //$stock=DB::connection('pgsql2')->table('crm_team_sale_canal as ca')->get();

        //DATOS DE

        $date = Carbon::now();
        $periodo=Date('Ym');

        //return response()->json($date->toTimeString());

        print_r($periodo);exit();

        $vendedores=DB::connection('pgsql2')->table('crm_team_sale_canal as ca')
        ->join('crm_team as crt','crt.id','=','ca.team_id')
        ->join('crm_team_salesman as v','ca.id','=','v.canal_id')
        ->join('res_users as ur','v.user_id','=','ur.id')
        ->join('res_partner as cli','cli.id','=','ur.partner_id')
        //->join('res_users as rs ','','')
        ->select('v.user_id','ca.supervisor_id','ca.name as supervisor','cli.name as vendedor','ca.id as id_canal','ca.name as canal',
                 'crt.id as id_equipo','crt.name as fuerza')
        ->where('cli.type','=','contact')
        ->get();

        //print_r($vendedores[0]->vendedor);exit();

        for ($i=0; $i <count($vendedores) ; $i++) {

            $vendedor=Vendedores::where('cod_vendedor','=',$vendedores[$i]->user_id)->first();

            if($vendedor==null){
                $vendedor=new Vendedores;
            }

            $vendedor->cod_vendedor=$vendedores[$i]->user_id;
            $vendedor->cod_supervisor=$vendedores[$i]->supervisor_id;
            $vendedor->supervisor=$this->supervisory($vendedores[$i]->supervisor_id);
            $vendedor->vendedor=$vendedores[$i]->vendedor;
            $vendedor->id_equipo=$vendedores[$i]->id_equipo;
            $vendedor->equipo=$vendedores[$i]->fuerza;
            $vendedor->id_canal=$vendedores[$i]->id_canal;
            $vendedor->canal=$vendedores[$i]->canal;
            $vendedor->save();

            //return response()->json($vendedor);
            //print_r('');exit();

        }

        print_r('ok');exit();



        /*$jefes=DB::connection('pgsql2')->table('crm_team as crt')
        ->join('res_users as ur','crt.user_id','=','ur.id')
        ->join('res_partner as cli','cli.id','=','ur.partner_id')
        ->select('crt.id','cli.name as jefedes','crt.name as equipo')
        ->get();

        //print_r($jefes[0]->id);exit();

        for ($i=0; $i <count($jefes) ; $i++) {

              $jefe=new jefeventas;
              $jefe->cod_jefeventas=$jefes[$i]->id;
              $jefe->nombre=$jefes[$i]->jefedes;
              $jefe->equipo=$jefes[$i]->equipo;
              $jefe->save();
              //print_r($jefe);exit();
        }*/

        /*$supervisores=DB::connection('pgsql2')->table('crm_team_sale_canal as ca')
        ->join('crm_team as crt','crt.id','=','ca.team_id')
        ->join('res_users as ur','ur.id','=','ca.supervisor_id')
        ->join('res_partner as cli','cli.id','=','ur.partner_id')
        ->select('ca.supervisor_id','cli.name as supervisor','ca.id as id_canal','ca.name as canal',
                 'crt.id as id_equipo','crt.name as equipo')
        ->get();

        //print_r($supervisores[0]->supervisor);exit();

        foreach($supervisores as $s){

             $r=new supervisor;
             $r->cod_supervisor=$s->supervisor_id;
             $r->supervisor=$s->supervisor;
             $r->cod_canal=$s->id_canal;
             $r->canal=$s->canal;
             $r->cod_equipo=$s->id_equipo;
             $r->equipo=$s->equipo;
             $r->save();

        }
        //print_r($supervisores);exit();


        print_r('ok');exit();*/
        /*$clientes=cliente::where('clientCode','=',"null")
        //->select('companyId','paternalSurname','maternalSurname','name','documentType','documentNumber','address','latitude','longitude','routeId','clientCode')
        ->first();

        $postInput = [
            'companyId' => '03',
            'paternalSurname' => 'PRUEBA',
            'maternalSurname' => 'PRUEBA',
            'name' => 'PRUEBA',
            'documentType' => 'DNI',
            'documentNumber' => '563214577',
            'phone' => '',
            'address' => 'CAL. PRUEBA 456',
            'latitude' => '-12.8124568',
            'longitude' => '-11.8124568',
            'routeId' => '6653',
        ];

        return response()->json($clientes);

        printf("");exit();


        /*$apiURL = 'http://190.187.237.70:8083/client/create';


        $headers = [
            'X-header' => 'value'
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        //dd($responseBody);
         return response()->json($responseBody["clientCode"]);*/

       //print_r($clietes);*/

            /*$curl = curl_init();

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://190.187.237.70:8083/promotions',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS =>'{
                        "officeId": "002",
                        "storageId": "120",

                        "priceListCode":"025"
                    }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    echo $response;*/

                    /*$curl = curl_init();

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://economysa-test.grupoquanam.com/api/login',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "params": {
                            "login":"teresa@economysa.pe",
                            "password":"12345"
                        }
                    }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Cookie: session_id=3ab0c0cf2d97ad044ad889c7dace789a41a9efa1'
                    ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    echo $response;*/











    }
    //funcion para devolver le nombre del supervisor
    public function supervisory($codigo){


        $jefes=DB::connection('pgsql2')->table('crm_team_sale_canal as cr')
        ->join('res_users as rs','cr.supervisor_id','=','rs.id')
        ->join('res_partner as res','res.id','=','rs.partner_id')
        ->select('cr.supervisor_id','res.name')
        ->where('cr.supervisor_id','=',$codigo)
        ->get();

        return $jefes[0]->name;

    }

    public function supervisores(){

        $supervisores=DB::connection('pgsql2')->table('crm_team_sale_canal as cr')
        ->join('res_users as rs','cr.supervisor_id','=','rs.id')
        ->join('res_partner as res','res.id','=','rs.partner_id')
        ->select(DB::raw('DISTINCT(cr.supervisor_id)'),'res.name')
        ->get();

        


        foreach($supervisores as $s){

            $r=supervisores::where('id_odoo_sup','=',$s->supervisor_id)->first();

            

            if($r==null){
                $r=new supervisores;
            }

            $r->id_odoo_sup=$s->supervisor_id;
            $r->name=$s->name;

           // print_r($r);exit();
            
            $r->save();

       }
       //;


        print_r('ok');


    }

    //funcion cargar productos
    public function productos(){

    $curl = curl_init();
    $datos=$this->token();
    $r=json_decode($datos,true);


        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://economysa.grupoquanam.com/api/product?active=True',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$r["result"]["data"]["token"],
            'Cookie: session_id=a946b4c343cf52f88b1fd0ffff68956c2f1f8b24'
            ),
        ));

        $response = curl_exec($curl);


        curl_close($curl);
  //echo($response);


    $productos=json_decode($response,true);
    //print_r('');exit();

    /*if(!empty($productos["data"][2]["taxes_id"])){

        echo 'vacio';
    }

    print_r($productos["data"][2]["taxes_id"]);exit();*/
    /*if(!isset($productos["data"][2]["taxes_id"])){

        echo "exite";
    }else{

        echo "no existe";
    }
    print_r($productos["data"][2]["taxes_id"]);exit(); */

    //$this->stock($productos);

   for ($i=0; $i < count($productos["data"]); $i++) {
    # code...

    $stock=Productos::where('cod_odoo','=',$productos["data"][$i]["product_id"])->first();

    if($stock==null){

        $stock=new Productos;
    }



     $stock->tipoproducto=$productos["data"][$i]["type"];
     //$stock->emp_id='';
    if(!empty($productos["data"][$i]["manufacter_id"])){

        $stock->prov_id=$productos["data"][$i]["manufacter_id"]["id"];
        $stock->proveedor=$productos["data"][$i]["manufacter_id"]["name"];


    }


     $stock->codart=$productos["data"][$i]["default_code"];
     $stock->producto=$productos["data"][$i]["name"];
     $stock->nombrecorto=$productos["data"][$i]["shortName"];
     if(!empty($productos["data"][$i]["category_id"])){

        $cat=Categorias::where('id_odoo_cat','=',$productos["data"][$i]["category_id"]["id"])->first();

        if($cat==null){

            $this->categorias($productos["data"][$i]["category_id"]["id"],$productos["data"][$i]["category_id"]["name"]);

        }



        $stock->idcategoria=$productos["data"][$i]["category_id"]["id"];
        $stock->categoria=$productos["data"][$i]["category_id"]["name"];

     }

     if(!empty($productos["data"][$i]["subcategory_id"])){

        $sub=subcategoria::where('id_odoo_sub_cat','=',$productos["data"][$i]["subcategory_id"]["id"])->first();
        if($sub==null){

            $this->subcategorias($productos["data"][$i]["subcategory_id"]["id"],$productos["data"][$i]["subcategory_id"]["name"]);

        }

        $stock->idsubcategoria=$productos["data"][$i]["subcategory_id"]["id"];
        $stock->subcategoria=$productos["data"][$i]["subcategory_id"]["name"];

     }





     $stock->idlinea=$productos["data"][$i]["brand_line_id"]["id"];
     $stock->linea=$productos["data"][$i]["brand_line_id"]["name"];
      $lin=lineas::where('id_odoo_lineas','=',$productos["data"][$i]["brand_line_id"]["id"])->first();
      if($lin==null){
        $this->lineas($productos["data"][$i]["brand_line_id"]["id"],$productos["data"][$i]["brand_line_id"]["name"]);
      }
     //$stock->idsublinea='';
     //$stock->sublinea='';
     $stock->idmarca=$productos["data"][$i]["brand_id"]["id"];
     $stock->marca=$productos["data"][$i]["brand_id"]["name"];
     //$stock->idsubmarca='';
     //$stock->submarca='';
     //$stock->codori='';
     $stock->empaquecompra=$productos["data"][$i]["uom_po_id"]["name"];
     //$stock->upreoriginal='';
     $stock->empaquevta=$productos["data"][$i]["purchase_ackaging"]["name"];
     $stock->undpresenta=$productos["data"][$i]["uom_id"]["name"];
     $stock->estado_articulo=$productos["data"][$i]["status_product"];
     $stock->estado_compra=$productos["data"][$i]["status_product"];
     $stock->estado_venta=$productos["data"][$i]["status_product"];
     $stock->peso=$productos["data"][$i]["weight_product"];
     //$stock->art_unimed='';
     $stock->volumen=$productos["data"][$i]["volume_product"];
     $stock->preciovta=$productos["data"][$i]["list_price"];

     if(!empty($productos["data"][$i]["qty_available"])){

        $stock->preciocompra=$productos["data"][$i]["qty_available"][0]["cost_promedio_producto"];

     }

     if(!empty($productos["data"][$i]["taxes_id"])){

      $stock->tipoigv=$productos["data"][$i]["taxes_id"][0]["name"];
      $stock->igv=$productos["data"][$i]["taxes_id"][0]["amount"];

     }

     $stock->unidad_sunat=$productos["data"][$i]["unidad_sunat"];
     $stock->codigo_compra_proveedor=$productos["data"][$i]["codigo_compra_proveedor"];
     $stock->codigo_barras=$productos["data"][$i]["barcode"];
     $stock->create_date=$productos["data"][$i]["create_date"];
     $stock->write_date=$productos["data"][$i]["write_date"];
     $stock->write_uid=$productos["data"][$i]["write_uid"];
     $stock->cod_odoo=$productos["data"][$i]["product_id"];
     $stock->save();
     //return response()->json($stock);
     //print_r('bien');exit();


    }



    print('ok');




    }

    //PRODUCTOSTS
    public function productosdos(){

          $productos=DB::connection('pgsql2')->table('product_product as p')
          ->join('product_template as pp','p.product_tmpl_id','=','pp.id')
          ->leftjoin('product_category as ca','ca.id','=','pp.categ_id')
            ->leftjoin('product_category as res','res.id','=','ca.parent_id')
            ->leftjoin('res_partner as fa','fa.id','=','pp.manufacter_id')
            ->leftjoin('sf_brand_product as ma','ma.id','=','pp.brand_id')
            ->leftjoin('sf_brand_product_line as li','li.id','=','pp.brand_line_id')
            ->leftjoin('uom_uom as uni','uni.id','=','pp.uom_id')
            ->leftjoin('uom_uom as mast','mast.id','=','pp.uom_mast_id')
            ->leftjoin('sunat_type_existence as st','st.id','=','pp.type_existence_id')
            ->leftjoin('sunat_stock_catalog as catl','catl.id','=','pp.catalog_id')
            ->select('p.id as id_producto','pp.name as producto','pp.short_name','pp.sale_ok','pp.purchase_ok','pp.type','pp.type_product_promotion_mix','ca.parent_id as id_categoria',
            'res.name as categoria','pp.categ_id as idsubcategaria','ca.name as subcategoria','pp.minimum_quantity','pp.default_code','pp.external_code','pp.existence_code','pp.type_existence_id',
            'st.name as tipo_existencia','pp.catalog_id','catl.name as catalogo_stock','pp.sunat_product_id','pp.manufacter_id','fa.name as fabricante','pp.brand_id as id_marca','ma.name as marca',
            'pp.brand_line_id as id_linea','li.name as linea','pp.uom_id','uni.name as unidad','pp.uom_mast_id','mast.name as unidad_master','pp.qty_mast as conversion',
             'pp.fraction_indicator','pp.available_chatbot','pp.uom_po_id','pp.weight','pp.volume','pp.list_price','p.barcode')
          //->where('p.id','=',28197)
          ->where('pp.sale_ok','=',true)
          ->where('pp.type','<>','consu')
          ->where('pp.type','<>','service')
          ->get();



        foreach($productos as $p){

            $stock=Productos::where('cod_odoo','=',$p->id_producto)->first();
            if($stock==null){

                $stock=new Productos;
            }

           $stock->tipoproducto=$p->type;
           $stock->prov_id=$p->manufacter_id;
           $stock->proveedor=$p->fabricante;
           $stock->codart=$p->default_code;
           $stock->producto=$p->producto;
           $stock->nombrecorto=$p->short_name;

           $stock->idcategoria=$p->id_categoria;
           $stock->categoria=$p->categoria;
           $stock->idsubcategoria=$p->idsubcategaria;
           $stock->subcategoria=$p->subcategoria;

           $stock->idlinea=$p->id_linea;
           $stock->linea=$p->linea;

           $stock->idmarca=$p->id_marca;
           $stock->marca=$p->marca;
           $stock->empaquecompra=$p->unidad_master;


            $stock->empaquevta=$p->unidad_master;

           if($p->unidad=="Units"){
           $stock->undpresenta='Unidades';
           }
           $stock->estado_articulo=1;

           if($p->purchase_ok){
            $stock->estado_compra=1;

           }else{
            $stock->estado_compra=0;
           }

           if($p->sale_ok){
            $stock->estado_venta=1;
           }else{
            $stock->estado_venta=0;
           }

           $stock->peso=$p->weight;
           //$stock->art_unimed='';
           $stock->volumen=$p->volume;
           $stock->preciovta=$p->list_price;

          //$stock->preciocompra=$productos["data"][$i]["seller"][0]["price"];
           //$stock->tipoigv=$productos["data"][$i]["taxes_id"][0]["name"];
           //$stock->igv=$productos["data"][$i]["taxes_id"][0]["amount"];
           //$stock->unidad_sunat=$productos["data"][$i]["unidad_sunat"];
           $stock->codigo_compra_proveedor=$p->external_code;
           $stock->codigo_barras=$p->barcode;
           //$stock->create_date=$productos["data"][$i]["create_date"];
           //$stock->write_date=$productos["data"][$i]["write_date"];
           //$stock->write_uid=$productos["data"][$i]["write_uid"];
           $stock->cod_odoo=$p->id_producto;
           $stock->save();

           //print_r('OK');exit();




        }


        print_r('OK');exit();




    }


    //FUNCION PARA SICRONIZAR CADA 30 MINUTOS
    public function stocdos(){

        $respuesta=$this->eliminarstock();

        if($respuesta='OK'){

        $curl = curl_init();
        $datos=$this->token();
        $r=json_decode($datos,true);


        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://economysa.grupoquanam.com/api/product?active=True',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$r["result"]["data"]["token"],
            'Cookie: session_id=a946b4c343cf52f88b1fd0ffff68956c2f1f8b24'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        //echo($response);
        $productos=json_decode($response,true);
        $date = Carbon::now();

        for ($i=0; $i < count($productos["data"]); $i++) {


            for($j=0;$j<count($productos["data"][$i]["qty_available"]);$j++){

               //print_r($productos["data"][$i]["qty_available"][$j]);exit();
               $stock=stocktime::where('almacen_id','=',$productos["data"][$i]["qty_available"][$j]["id"])
               ->where('cod_producto','=',$productos["data"][$i]["default_code"])
               ->first();


               if($stock==null){

                   $stock=new stocktime;
               }
               //$stock=new Stock;
               $stock->cod_producto=$productos["data"][$i]["default_code"];
               $stock->almacen_id=$productos["data"][$i]["qty_available"][$j]["id"];
               $stock->stock_diponible=$productos["data"][$i]["qty_available"][$j]["stock_diponible"];
               $stock->stock_seguridad=$productos["data"][$i]["qty_available"][$j]["stock_seguridad"];
               $stock->cantidad_mano=$productos["data"][$i]["qty_available"][$j]["cantidad_mano"];
               $stock->total_soles=round($productos["data"][$i]["qty_available"][$j]["total_costo"],2);
               $stock->fecha=date('Y-m-d');
               $stock->periodo=date('Ym');
               $stock->hora=$date->toTimeString();
               $stock->stockmaster=$productos["data"][$i]["qty_available"][$j]["cantidad_mano"]/$this->product_factor($productos["data"][$i]["default_code"]);
               $stock->save();




            }




       }
     
    }


       print_r('ok');exit();








    }
    //FUNCION PARA ENVIAR LOS DATOS DEL PRODUCTO
    public function product_factor($id_producto){

        $producto=Productos::where('codart','=',$id_producto)->first();
        $factor=1;
        if($producto==null){
            $factor=1;
        }else{
            $factor=$producto->conver_master_unidad;
        }

        return $factor;
    }

    //FUNCION PARA SICRONIZAR EL STOCK DE LOS PRODUCTOS
    public function stock(){


        $respuesta=$this->eliminarstock();

        //print_r($respuesta);exit();

        if($respuesta=='OK'){


        $curl = curl_init();
        $datos=$this->token();
        $r=json_decode($datos,true);
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://economysa.grupoquanam.com/api/product?active=True',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$r["result"]["data"]["token"],
            'Cookie: session_id=a946b4c343cf52f88b1fd0ffff68956c2f1f8b24'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        //echo($response);
          $productos=json_decode($response,true);

          //print_r($productos);exit();

        for ($i=0; $i < count($productos["data"]); $i++) {


             for($j=0;$j<count($productos["data"][$i]["qty_available"]);$j++){

                //print_r($productos["data"][$i]["qty_available"][$j]);exit();
                $stock=Stock::where('almacen_id','=',$productos["data"][$i]["qty_available"][$j]["id"])
                ->where('cod_producto','=',$productos["data"][$i]["default_code"])
                ->first();

                ///print_r($stock);exit()

                $stock=new Stock;
                $stock->cod_producto=$productos["data"][$i]["default_code"];
                $stock->almacen_id=$productos["data"][$i]["qty_available"][$j]["id"];
                $stock->stock_diponible=$productos["data"][$i]["qty_available"][$j]["stock_diponible"];
                $stock->stock_seguridad=$productos["data"][$i]["qty_available"][$j]["stock_seguridad"];
                $stock->cantidad_mano=$productos["data"][$i]["qty_available"][$j]["cantidad_mano"];
                $stock->total_soles=round($productos["data"][$i]["qty_available"][$j]["total_costo"],2);
                $stock->fecha=date('Y-m-d');
                $stock->periodo=date('Ym');
                $stock->masterStockAmount=$productos["data"][$i]["masterStockAmount"];
                $stock->save();

             }




        }
    }

        






    }

    //METODO PARA ELIMINAR LOS STOCK
    public function eliminarstock(){

        $stock=DB::table('stocktimes')->delete();
         return 'OK';


    }
    //FUNCION PARA OBETNER LOS GIROS
    public function giros(){

        $curl = curl_init();
        $datos=$this->token();
        $token=json_decode($datos,true);

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://economysa.grupoquanam.com/api/linebusiness',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'Authorization: Bearer '.$token["result"]["data"]["token"],
              'Cookie: session_id=08661fe10a6f86229808fc5873da6454c8b530b2'
            ),
          ));

          $response = curl_exec($curl);

          curl_close($curl);
          $giros=json_decode($response,true);

          for ($i=0; $i <count($giros["data"]) ; $i++) {
            # code...
            $giro=giro::where('id_odoo','=',$giros["data"][$i]["id"])->first();

            if($giro==null){
                $giro=new giro;
            }

            $giro->id_odoo=$giros["data"][$i]["id"];
            $giro->name=$giros["data"][$i]["name"];
            $giro->save();

          }

          print_r('ok');




    }
    //FUNCIONES DE LAS CATEGORIAS
    public function categorias($id,$name){

        $categorias=new Categorias;
        $categorias->id_odoo_cat=$id;
        $categorias->name=$name;
        $categorias->save();


    }

    public function subcategorias($id,$name){

        $categorias=new subcategoria;
        $categorias->id_odoo_sub_cat=$id;
        $categorias->name=$name;
        $categorias->save();



    }
    //FUNCION DE LIENAS

    public function lineas($id,$name){
        $lienas=new lineas;
        $lienas->id_odoo_lineas=$id;
        $lienas->name=$name;
        $lienas->save();


    }
    //FUNCION PARA SICRONIZAR MOTIVOS DE RECHAZOS
    public function rechazos(){


        $curl = curl_init();
        $datos=$this->token();
        $token=json_decode($datos,true);

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://economysa.grupoquanam.com/api/reason/reject',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token["result"]["data"]["token"],
            'Cookie: session_id=08661fe10a6f86229808fc5873da6454c8b530b2'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $motivos=json_decode($response,true);



          for ($i=0; $i <count($motivos["data"]) ; $i++) {
            # code...
            $motivo=motivosrechazo::where('cod_oddo','=',$motivos["data"][$i]["reason_id"])->first();

            if($motivo==null){

                $motivo=new motivosrechazo;
            }

            $motivo->cod_oddo=$motivos["data"][$i]["reason_id"];
            $motivo->motivo=$motivos["data"][$i]["name"];
            $motivo->save();

            //print_r('ok');

          }

          print_r('ok');





    }
    public function estadopedidos(){

        $cabecera=pedido_cab::all();//where('nro_order','=','S32968')->get();//all();

        

        

        foreach($cabecera as $cab){
           
            $estado=$this->anular_pedidos($cab->id_odoo);
            //print_r($estado);exit();

            //return  $estado;
            
            if(!empty($estado)){
               $pedido=pedido_cab::where('id_odoo','=',$cab->id_odoo)->first();
               $pedido->fecha_modi=Date('Y-m-d');
               $pedido->reason_reject=$estado->reason_return;
               $pedido->state='Rechazados';
               //return $estado;
               $pedido->save();

            }
             
        }

        
       
       return 'OK';
       



         

   }
   ///METODO PARA CAMBIAR EL ESTADO
   public function modificarestado($id_odoo){

       $pedido_odoo=DB::connection('pgsql2')->table('sf_delivery_app as af')
       ->join('account_move as acc','acc.id','=','af.account_id')
       ->select('af.id','af.name','af.customer_delivery_status')
       ->where('customer_delivery_status','=','delivered')
       ->whereNotNull('account_id')
       ->where('af.order_id','=',$id_odoo)
       ->first();

       if(!empty($pedido_odoo)){

           return $pedido_odoo->customer_delivery_status;

       }

       



         
   }

   public function anular_pedidos($id_odoo){

    $pedido_odoo=DB::connection('pgsql2')->table('sf_delivery_app as af')
       //->join('account_move as acc','acc.id','=','af.account_id')
       //->select('af.id','af.name','af.customer_delivery_status')
       ->where('customer_delivery_status','=','observed')
       //->whereNotNull('account_id')
       ->where('af.order_id','=',$id_odoo)
       ->first();

       if(!empty($pedido_odoo)){

        return $pedido_odoo;

    }




   }


    //FUNCION PARA TRAER PEDIDOS Y DETALLE DE PEDIDOS
    public function pedidos(){

      //draft, confirmes, planned, shipping, delivered, cancelled, rejected
         $curl = curl_init();

         $datos=$this->token();
         $token=json_decode($datos,true);

         session(['key' =>$token]);


         //return response()->json($token["result"]["data"]["token"]);

         //print_r($v);exit();

         $fechauno='2022/08/08';
         $fechados='2022/08/08';



        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://economysa.grupoquanam.com/api/customer/history?dateRange='.$fechauno.'-'.$fechados,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token["result"]["data"]["token"],
            'Cookie: session_id=7b5f30fe09286bef1815f1a4aa16def6d0a6d38f'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $pedidos=json_decode($response,true);





        //print_r($pedidos["data"][0]["lines"][0]["product_id"]["name"]);exit();

        return response()->json(count($pedidos["data"]));

              print_r('');exit();


             for ($i=0; $i <count($pedidos["data"]) ; $i++) {

                $pedido=pedido_cab::where('id_odoo','=',$pedidos["data"][$i]["id"])->first();
        
                if( $pedido==null){
                    $pedido=new pedido_cab;
                }
        
        
                $pedido->id_odoo=$pedidos["data"][$i]["id"];
                $pedido->nro_order=$pedidos["data"][$i]["nro_order"];
                $pedido->date_order=$pedidos["data"][$i]["date_order"];
                $pedido->date_emision=$pedidos["data"][$i]["date_emision"];
                $pedido->company_id=$pedidos["data"][$i]["company_id"];
                $pedido->ruta_master=$pedidos["data"][$i]["ruta_master"];
                $pedido->ruta_dia=$pedidos["data"][$i]["ruta_dia"];
                $pedido->date_delivery=$pedidos["data"][$i]["date_delivery"];
        
                if(!empty($pedidos["data"][$i]["status"])){
        
        
                    $valor=count($pedidos["data"][$i]["status"]);
         
                    if($valor==1){
         
                       $pedido->state =$pedidos["data"][$i]["status"][0]["status_economysa"];
                    }else{
         
                        $dato=$valor -1;
                        $pedido->state=$pedidos["data"][$i]["status"][$dato]["status_economysa"];
         
                    }
         
         
         
                 }
        
        
                 $pedido->tipo_documento=$pedidos["data"][$i]["tipo_documento"];
                 $pedido->numero_comprobante=$pedidos["data"][$i]["numero_comprobante"];
                 $pedido->fecha_liquidacion=$pedidos["data"][$i]["fecha_liquidacion"];
                 $pedido->total_amt=$pedidos["data"][$i]["total_amt"];
                 $pedido->fecha_cobranza=$pedidos["data"][$i]["fecha_cobranza"];
                 $pedido->total_amt_inv=$pedidos["data"][$i]["total_amt_inv"];
                 $pedido->total_discount=$pedidos["data"][$i]["total_discount"];
                 $pedido->id_almacen=$pedidos["data"][$i]["id_almacen"];
         
                 if(!empty($pedidos["data"][$i]["reason_reject"])){
         
                     $pedido->id_mot_r=$pedidos["data"][$i]["reason_reject"]["id"];
                     $pedido->reason_reject=$pedidos["data"][$i]["reason_reject"]["name"];
         
         
                 }
        
        
        
                 $pedido->forma_pago=$pedidos["data"][$i]["forma_pago"];
        
                 $pedido->codigo_dir=$pedidos["data"][$i]["direccion_entrega"]["id"];
         
                 $pedido->dir_entrega=$pedidos["data"][$i]["direccion_entrega"]["name"];
         
                 if(!empty($pedidos["data"][$i]["price_list"])){
         
                     $pedido->id_list_price=$pedidos["data"][$i]["price_list"]["id"];
                     $pedido->price_list=$pedidos["data"][$i]["price_list"]["name"];
         
         
                 }
         
         
                 $pedido->idcliente_odoo=$pedidos["data"][$i]["partner_id"]["id"];
         
                
        
        
                 $direciox=$this->dir_cliente($pedidos["data"][$i]["partner_id"]["id"],$pedidos["data"][$i]["direccion_entrega"]["id"]);
                 //$pedido->modulo=$direciox[0]["zone_id"]["name"];
                
                 if(!empty($direciox[0]["canal_ids"])){
        
                    $pedido->id_canal=$direciox[0]["canal_ids"][0]["id"];
                    $pedido->canal=$direciox[0]["canal_ids"][0]["name"];
         
                 }
         
         
                 if(!empty($direciox[0]["longitude"])){
         
                    $pedido->logx=$direciox[0]["longitude"];
                 }
         
                 if(!empty($direciox[0]["latitude"])){
         
                    $pedido->laty=$direciox[0]["latitude"];
                 }
         
                 if(!empty($direciox[0]["Schedule"])){
         
                     $pedido->schedule=$direciox[0]["Schedule"];
         
                  }
         
                  if(!empty($direciox[0]["zone_id"])){
         
                     $pedido->modulo=$direciox[0]["zone_id"]["name"];
                  }
        
                  if(!empty($direciox[0]["officeId"])){
        
                    $pedido->officeId=$direciox[0]["officeId"]["id"];
                    $pedido->officeId_name=$direciox[0]["officeId"]["name"];
        
                 }
        
                  
        
                  
                $pedido->idclie=$pedidos["data"][$i]["partner_id"]["code"];
                $pedido->razon_social=$pedidos["data"][$i]["partner_id"]["name"];
        
                if(!empty($pedidos["data"][$i]["partner_id"]["l10n_pe_district"])){
        
        
        
                   $pedido->cod_distrito=$pedidos["data"][$i]["partner_id"]["l10n_pe_district"]["id"];
        
        
                   $pedido->distrito=$pedidos["data"][$i]["partner_id"]["l10n_pe_district"]["name"];
                   $pedido->ubigeo=$pedidos["data"][$i]["partner_id"]["l10n_pe_district"]["ubigeo"];
        
                }
        
                if(!empty($pedidos["data"][$i]["seller_id"])){
        
                   $pedido->seller_id=$pedidos["data"][$i]["seller_id"]["id"];
                   $pedido->seller_name=$pedidos["data"][$i]["seller_id"]["name"];
        
                }
        
              if(!empty($pedidos["data"][$i]["user_delivery"])){
        
               $pedido->driver_id=$pedidos["data"][$i]["user_delivery"]["id"];
               $pedido->driver=$pedidos["data"][$i]["user_delivery"]["name"];
        
              }
        
              if(!empty($pedidos["data"][$i]["partner_id"]["giro_id"])){
        
                $pedido->giro_id=$pedidos["data"][$i]["partner_id"]["giro_id"]["id"];
                $pedido->giro=$pedidos["data"][$i]["partner_id"]["giro_id"]["name"];
         
         
               }
         
                 $infx=Carbon::parse($pedidos["data"][$i]["date_order"]);
                 $pedido->periodo=$infx->format('Ym');
                 $diax=Carbon::parse($pedidos["data"][$i]["date_order"]);
                 $pedido->dis_visita=$infx->format('d');
                 $pedido->idmone=1;
                 $pedido->tipocambio=1;
                 $this->clientes($pedidos["data"][$i]["partner_id"]["id"]);
         
         
                  $pedido->save();
        
                  for ($j=0; $j <count($pedidos["data"][$i]["lines"]) ; $j++) {
        
                    $detalle=pedido_det::where('id_linea_odo','=',$pedidos["data"][$i]["lines"][$j]["id"])->first();
        
                    if($detalle==null){
        
                        $detalle=new pedido_det;
                    }
        
                   $detalle->id_linea_odo=$pedidos["data"][$i]["lines"][$j]["id"];
                   $detalle->id_ordder=$pedidos["data"][$i]["id"];
                   $detalle->product_id=$pedidos["data"][$i]["lines"][$j]["product_id"]["id"];
                   $detalle->product_name=$pedidos["data"][$i]["lines"][$j]["product_id"]["name"];
                   $detalle->tipo_producto=$pedidos["data"][$i]["lines"][$j]["product_id"]["tipo_producto"];
                   $detalle->unidad_medida=$pedidos["data"][$i]["lines"][$j]["product_id"]["unidad_medida"];
        
                   if(!empty($pedidos["data"][$i]["lines"][$j]["product_id"]["precio_compra"])){
        
                       $detalle->precio_compra=$pedidos["data"][$i]["lines"][$j]["product_id"]["precio_compra"];
                   }
        
        
        
                   if(!empty($pedidos["data"][$i]["lines"][$j]["product_id"]["codigo_fabricante"])){
        
                    $detalle->codigo_fabricante=$pedidos["data"][$i]["lines"][$j]["product_id"]["codigo_fabricante"];
        
                   }else{
                    $detalle->codigo_fabricante=0;
        
                   }
        
                   $detalle->discount_product=$pedidos["data"][$i]["lines"][$j]["product_id"]["discount_product"];
                   $detalle->unidades_complementarias_faltantes_master=$pedidos["data"][$i]["lines"][$j]["product_id"]["unidades_complementarias_faltantes_master"];
                   $detalle->price_unit=$pedidos["data"][$i]["lines"][$j]["price_unit"];
                   $detalle->precio_sin_igv=$pedidos["data"][$i]["lines"][$j]["precio_sin_igv"];
                   $detalle->product_uom_qty=$pedidos["data"][$i]["lines"][$j]["product_uom_qty"];
        
                   if(!empty($pedidos["data"][$i]["lines"][$j]["tax_id"])){
        
                       $detalle->tax_id=$pedidos["data"][$i]["lines"][$j]["tax_id"][0]["id"];
        
                   }
        
                   $detalle->impuesto=18;//(double)$pedidos["data"][$i]["lines"][$j][0]["tax_id"]["name"];
                   $detalle->monto_impuesto=$pedidos["data"][$i]["lines"][$j]["monto_impuesto"];
                   $detalle->is_reward_line=$pedidos["data"][$i]["lines"][$j]["is_reward_line"];
                   //$detalle->tax_id=$pedidos["data"][$i]["lines"][$j]["tax_id"]["id"];
                   if(!empty($pedidos["data"][$i]["lines"][$j]["cod_promocion"])){
        
                       $detalle->cod_promocion=$pedidos["data"][$i]["lines"][$j]["cod_promocion"]["id"];
                       $detalle->nombre_promo=$pedidos["data"][$i]["lines"][$j]["cod_promocion"]["name"];
        
                   }
        
                   $detalle->save();
        
                   //return response()->json($detalle);
        
                   //print_r('');exit();
        
                   //print_r($detalle);exit();
        
        
                }
        
            }


        echo 'ok';




    }

    //CARGAR ALMACENES
    public function almacenes(){

        $curl = curl_init();

            $datos=$this->token();
            $token=json_decode($datos,true);

        //$token=session('key');

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://economysa.grupoquanam.com/api/warehouse',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'Authorization: Bearer '.$token["result"]["data"]["token"],
              'Cookie: session_id=08661fe10a6f86229808fc5873da6454c8b530b2'
            ),
          ));

          $response = curl_exec($curl);

          curl_close($curl);
          $alamcenes=json_decode($response,true);

          for($i=0;$i<count($alamcenes["data"]);$i++){
            
            $almacen=almacenes::where('id','=',$alamcenes["data"][$i]["id"])->first();

            //print_r($almacen);exit();

            if($almacen==null){
                $almacen=new Almacenes;
            }
        
            $almacen->id=$alamcenes["data"][$i]["id"];
            $almacen->almacen=$alamcenes["data"][$i]["name"];
            $almacen->save();

        }

        print_r('OK');







    }

      //FUNCION MOTIVO DE RECHAZO ID
      public function motivo($motivos){

        $curl = curl_init();

        $token=session('key');

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://economysa.grupoquanam.com/api/reason/reject',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token["result"]["data"]["token"],
            'Cookie: session_id=c85494d02770c8288198cd7f075bbae385eac610'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $motivo=json_decode($response,true);
        $idmotivo=0;

        for ($i=0; $i <count($motivo["data"]) ; $i++) {

               if($motivo["data"][$i]["name"]==$motivos){

                $idmotivo=$motivo["data"][$i]["reason_id"];

               }


        }



        return $idmotivo;





    }


    //FUNCION
    public function clientes($cod_cliente){


                        $curl = curl_init();

                        //$datos=$this->token();
                        //$token=json_decode($datos,true);
                        $token=session('key');


                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://economysa.grupoquanam.com/api/customer?company_id=1&partner_id='.$cod_cliente.'&active=1',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: Bearer '.$token["result"]["data"]["token"],
                                'Cookie: session_id=c85494d02770c8288198cd7f075bbae385eac610'
                            ),
                            ));


                $response = curl_exec($curl);

                curl_close($curl);
                $clientes=json_decode($response,true);

                for ($i=0; $i < count($clientes["data"]); $i++) {

                   $cliente=Clientes::where('idoo','=',$clientes["data"][$i]["partner_id"])->first();

                   if($cliente==null){

                       $cliente=new Clientes;

                    }


                   $cliente->idoo=$clientes["data"][$i]["partner_id"];
                   $cliente->emp_id=$clientes["data"][$i]["company_id"]["id"];
                   $cliente->cli_idclie=$clientes["data"][$i]["code"];
                   if($clientes["data"][$i]["l10n_latam_identification_type_id"]["id"]==5){

                    $cliente->cli_nrodoc=$clientes["data"][$i]["vat"];

                   }else{


                    $cliente->cli_ruc=$clientes["data"][$i]["vat"];


                   }

                   $cliente->cli_razsoc=$clientes["data"][$i]["full_name"];
                   $cliente->cli_domifis=$clientes["data"][$i]["street"];
                   $cliente->idcategoabc=$clientes["data"][$i]["tier"];
                   $cliente->pais=$clientes["data"][$i]["country_id"]["name"];
                   $cliente->departamento=$clientes["data"][$i]["state_id"]["name"];
                   $cliente->provincia=$clientes["data"][$i]["city_id"]["name"];
                   $cliente->distrito=$clientes["data"][$i]["l10n_pe_district"]["name"];
                   $cliente->pricelist_id=$clientes["data"][$i]["pricelist_id"]["id"];
                   $cliente->pricelist=$clientes["data"][$i]["pricelist_id"]["name"];
                   $cliente->estado_cliente=$clientes["data"][$i]["status_cliente"];
                   $cliente->estado_cliente=$clientes["data"][$i]["status_cliente"];
                   $cliente->es_fabricante=$clientes["data"][$i]["fabricante"];
                   $cliente->es_conducto=$clientes["data"][$i]["conductor"];
                   $cliente->validado_sunat=$clientes["data"][$i]["is_validate"];
                    
                    if(!empty($clientes["data"][$i]["deliveryAddresses"][0]["line_business_id"]["id"])){

                        $cliente->id_giro=$clientes["data"][$i]["deliveryAddresses"][0]["line_business_id"]["id"];
                    }
                    if(!empty($clientes["data"][$i]["deliveryAddresses"][0]["l10n_pe_district"]["ubigeo"])){

                        $cliente->ubigeo=$clientes["data"][$i]["deliveryAddresses"][0]["l10n_pe_district"]["ubigeo"];

                    }

                   $cliente->save();







                }

                return 'OK';





    }

    public function tarifas($tarifa){

        $curl = curl_init();

        $token=session('key');

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://economysa.grupoquanam.com/api/pricelist',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token["result"]["data"]["token"],
                'Cookie: session_id=c85494d02770c8288198cd7f075bbae385eac610'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $motivo=json_decode($response,true);
            $idlista=0;

            for ($i=0; $i <count($motivo["data"]) ; $i++) {

                   if($motivo["data"][$i]["name"]==$tarifa){

                    $idlista=$motivo["data"][$i]["id"];

                   }


            }



            return $idlista;


    }


    //FUNCIONES PARA TRAER LAS DIRECCIONES DE ENTREGA


    //FUCION PARA ACTUALIZAR CLIENTE
     public function dir_cliente($cod_cliente,$cod_dir){

        $curl = curl_init();

        //$datos=$this->token();
        //$token=json_decode($datos,true);
        $token=session('key');


        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://economysa.grupoquanam.com/api/customer?company_id=1&partner_id='.$cod_cliente.'&active=1',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token["result"]["data"]["token"],
                'Cookie: session_id=c85494d02770c8288198cd7f075bbae385eac610'
            ),
            ));


            $response = curl_exec($curl);

            curl_close($curl);
            $clientes=json_decode($response,true);
            $info=0;

            for ($i=0; $i < count($clientes["data"]); $i++) {

                 for ($j=0; $j <count($clientes["data"][$i]["deliveryAddresses"]) ; $j++) {

                     if($clientes["data"][$i]["deliveryAddresses"][$j]["id"]==$cod_dir){

                         $info=$clientes["data"][$i]["deliveryAddresses"];
                     }


                 }

            }

            return $info;





     }

    //FUNCION PARA SICRONIZAR LAS COMPRAS
    public function compras(){

        $curl = curl_init();

        $datos=$this->token();
        $token=json_decode($datos,true);

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://economysa.grupoquanam.com/api/purchase?company_id=1&dataRange=2022/07/01-2022/07/30',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token["result"]["data"]["token"],
            'Cookie: session_id=7b5f30fe09286bef1815f1a4aa16def6d0a6d38f'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $compras=json_decode($response,true);

        for ($i=0; $i <count($compras["data"]) ; $i++) {

            $compra=Compras::where('id_odoo','=',$compras["data"][$i]["id"])->first();

            if($compra==null){

                $compra=new Compras;
            }


            $compra->id_odoo=$compras["data"][$i]["id"];
            $compra->company_id=$compras["data"][$i]["company_id"];
            $compra->nro_order=$compras["data"][$i]["nro_order"];
            $compra->estado_orden=$compras["data"][$i]["state"];
            $compra->date_approve=$compras["data"][$i]["date_approve"];
            $compra->date_planned=$compras["data"][$i]["date_planned"];
            $compra->date_planned=$compras["data"][$i]["date_planned"];
            //$compra->numero_documento_proveedor=$compras["data"][$i]["nro_doc_partner"];
            $compra->numero_documento_proveedor=$compras["data"][$i]["nro_doc_partner"];
            $compra->amount_tax=$compras["data"][$i]["amount_tax"];
            $compra->amount_total=$compras["data"][$i]["amount_total"];
            if(!empty($compras["data"][$i]["warehouse"])){

                $compra->warehouse_id=$compras["data"][$i]["warehouse"]["warehouse_id"];
                $compra->warehouse_name=$compras["data"][$i]["warehouse"]["warehouse_name"];

            }

            $compra->save();



           for ($j=0; $j <count($compras["data"][$i]["lines"]) ; $j++) {

               $detalle=new Detalle_compras;
               $detalle->id_compra=$compras["data"][$i]["id"];
               $detalle->product_id=$compras["data"][$i]["lines"][$j]["product_id"]["id"];
               $detalle->producto=$compras["data"][$i]["lines"][$j]["product_id"]["name"];

               if(!empty($compras["data"][$i]["lines"][$j]["product_id"]["uom_id"])){
                $detalle->unidad_venta=$compras["data"][$i]["lines"][$j]["product_id"]["uom_id"]["name"];
               }
               if(!empty($compras["data"][$i]["lines"][$j]["product_id"]["uom_po_id"])){

                $detalle->unidad_medida_master=$compras["data"][$i]["lines"][$j]["product_id"]["uom_po_id"]["name"];

               }
               $detalle->precio_compra=$compras["data"][$i]["lines"][$j]["product_id"]["precio_compra"];

               if(!empty($compras["data"][$i]["lines"][$j]["product_id"]["manufacter_id"])){

                $detalle->cod_proveedor=$compras["data"][$i]["lines"][$j]["product_id"]["manufacter_id"]["id"];
                $detalle->proveedor=$compras["data"][$i]["lines"][$j]["product_id"]["manufacter_id"]["name"];

               }

               $detalle->cantidad_rechazada=$compras["data"][$i]["lines"][$j]["product_id"]["cantidad_rechazada"];
               $detalle->unidad_medida_master=$compras["data"][$i]["lines"][$j]["product_id"]["unidad_medida_master"];
               $detalle->unidad_medida_rechazada=$compras["data"][$i]["lines"][$j]["product_id"]["unidad_medida_rechazada"];
               $detalle->conver_master_unidad=$compras["data"][$i]["lines"][$j]["product_id"]["conver_master_unidad"];
               $detalle->cantidad_master=$compras["data"][$i]["lines"][$j]["product_id"]["cantidad_master"];
               $detalle->cantidad_unidades=$compras["data"][$i]["lines"][$j]["product_id"]["cantidad_unidades"];
               $detalle->costo_master=$compras["data"][$i]["lines"][$j]["product_id"]["costo_master"];
               $detalle->price_unit=$compras["data"][$i]["lines"][$j]["price_unit"];
               $detalle->precio_sin_igv=$compras["data"][$i]["lines"][$j]["precio_sin_igv"];
               $detalle->product_qty=$compras["data"][$i]["lines"][$j]["product_qty"];


               if(!empty($compras["data"][$i]["lines"][$j]["tax_id"])){

                $detalle->tax_id=$compras["data"][$i]["lines"][$j]["tax_id"][0]["id"];
                $detalle->impuesto=$compras["data"][$i]["lines"][$j]["tax_id"][0]["name"];

               }

               $detalle->save();

               //return response()->json($detalle);

               //print_r('');exit();

              //










           }



        }





    }


    //FUNCIÓN PARA PODER SICRONIZAR PROVEEDORES
    public function sic_proveedores(){

        $curl = curl_init();

        $datos=$this->token();
        $token=json_decode($datos,true);

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://economysa.grupoquanam.com/api/customer?company_id=1&active=1&is_manufacter=True',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token["result"]["data"]["token"],
            'Cookie: session_id=a946b4c343cf52f88b1fd0ffff68956c2f1f8b24'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $ind_desp=json_decode($response,true);
        $codigo_proveedor=0;
        //print_r($ind_desp["data"][0]["l10n_latam_identification_type_id"]["id"]);exit();


        for($i=0;$i<count($ind_desp["data"]);$i++){

            $fabricante=new fabricante;
            $fabricante->cod_odoo=$ind_desp["data"][$i]["partner_id"];
            $fabricante->cod_eco=$ind_desp["data"][$i]["code"];
            $fabricante->tipo_documento=$ind_desp["data"][$i]["l10n_latam_identification_type_id"]["name"];
            $fabricante->documento=$ind_desp["data"][$i]["vat"];
            $fabricante->proveedor=$ind_desp["data"][$i]["full_name"];
            $fabricante->cod_compania=$ind_desp["data"][$i]["company_id"]["id"];
            $fabricante->compania=$ind_desp["data"][$i]["company_id"]["name"];
            $fabricante->save();

        }

        //print_r('ok');exit();





    }

    //FUNCION PARA TRAER EL CODIGO DEL ALMACEN

    public function proveedores($nombre){

        $curl = curl_init();

        $datos=$this->token();
        $token=json_decode($datos,true);

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://economysa.grupoquanam.com/api/customer?is_manufacter=True',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token["result"]["data"]["token"],
            'Cookie: session_id=a946b4c343cf52f88b1fd0ffff68956c2f1f8b24'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $ind_desp=json_decode($response,true);
        $codigo_proveedor=0;
        for($i=0;$i<count($ind_desp["data"]);$i++){

            if($ind_desp["data"][$i]["full_name"]==$nombre){

                $codigo_proveedor=$ind_desp["data"][$i]["partner_id"];


            }



        }

        return 'Razon social '.$nombre.' codigo '.$codigo_proveedor;




    }


    public function token(){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://economysa.grupoquanam.com/api/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
              "params":{
                  "login":"sistemas@economysa.pe",
                  "password":"Eco123."
              }
          }
          ',
            CURLOPT_HTTPHEADER => array(
              'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE2NTczNzA4NTYsInVzZXJfaWQiOjc0MSwidXNlcl9uYW1lIjoiQUdVRVJPIEFHVUVSTyBMVUlTIEdVSUxMRVJNTyIsImNvbXBhbnlfaWQiOjEsImNvbXBhbnlfbmFtZSI6IkVDT05PTVlTQSBTQUMiLCJ1c2VyX3R6IjoiQW1lcmljYS9MaW1hIiwic2FsZV90ZWFtX2lkIjowLCJyb3V0ZV9wZXJpb2RfaWQiOjI3NjcsInR5cGVfdXNlciI6IlZlbmRlZG9yIn0.t9O1TpHwlsVTXxVA0j26iag91NEzofrCoLfFgIggCik',
              'Content-Type: application/json',
              'Cookie: session_id=a946b4c343cf52f88b1fd0ffff68956c2f1f8b24'
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          return $response;






    }



    public function listado(){

        /*$promo=Promo_cabecera::with('default_code')
        //->whereIn('id',[8,9])
        ->get();

        //print_r("hola00");exit();

         $text="";


        for($i=0;$i<count($promo);$i++){

           $text .=$promo[$i]["promc_idpromo"].";";
           //$text .='["|","|",';
           //$text .=$promo[$i]["promc_idpromo"].";";
           //$text .=$this->cadena(count($promo[$i]["default_code"]));
           //print($text);
          // print("cantidad ".count($promo[$i]["default_code"])."  xx ");
          $text .='[["default_code","in",[';

            for($j=0;$j<count($promo[$i]["default_code"]);$j++){

                $text .='"'.utf8_decode($promo[$i]["default_code"][$j]["promd_artprov"]).'"' ;
                $text .=",";

            }
            $data = rtrim($text, ',');
            $data .= "]]]";
            print  $data;
            //print_r('');exit();
            Storage::append("DATA.csv",$data);

            $text ="";*/

            $clientes=cliente::all();
            return response()->json($clientes);







        //}

    //metodo para armar las cadenas



        //return response()->json($promo);
    }

   public function cadena($cantidad){

        $cadena='[';
        for($i=0;$i<$cantidad;$i++){

            if($i==0){

                $cadena='[';
            }else{

              $cadena .='"|",';
            }


        }

        return $cadena;
    }


    public function info(){

        $clientes=cliente::where('clientCode','=',"1")
        //->select('companyId','paternalSurname','maternalSurname','name','documentType','documentNumber','address','latitude','longitude','routeId','clientCode')
        ->first();

        return response()->json($clientes);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function crear(Request $request)
    {
        //

        $todo=$request->all();
        //return response()->json($todo["data"]["companyId"]);

        $apiURL = 'http://190.187.237.70:8083/client/create';
        $postInput = [
            'companyId' => $todo["data"]["companyId"],
            'paternalSurname' => $todo["data"]["paternalSurname"],
            'maternalSurname' => $todo["data"]["maternalSurname"],
            'name' => $todo["data"]["name"],
            'documentType' => $todo["data"]["documentType"],
            'documentNumber' => $todo["data"]["documentNumber"],
            'phone' => $todo["data"]["phone"],
            'address' => $todo["data"]["address"],
            'latitude' => $todo["data"]["latitude"],
            'longitude' => $todo["data"]["longitude"],
            'routeId' => $todo["data"]["routeId"],
        ];


        //return response()->json($postInput);
        //print_r("");exit();

        $headers = [
            'X-header' => 'value'
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        $clientes=cliente::where('id','=',$todo["data"]["id"])->first();
        $clientes->clientCode=$responseBody["clientCode"];
        $clientes->save();

        return response()->json($responseBody);





    }

    public function export()
    {
        return Excel::download(new ClienteExport, 'clientes.xlsx');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(cliente $cliente)
    {
        //
    }
}
