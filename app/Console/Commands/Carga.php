<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
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
use App\Promo_cabecera;
use Carbon\Carbon;
use App\Promocion;
use App\almacenes;
use App\supervisores;
use App\motivosrechazo;
use App\stocktime;

class Carga extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carga:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SICRONIZACIÓN CUBO';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //draft, confirmes, planned, shipping, delivered, cancelled,

        $curl = curl_init();
        $datos=$this->token();
        $token=json_decode($datos,true);
        session(['key' =>$token]);

        $periodo=Date('Ym');



        $cabecera=pedido_cab::where('periodo','=',$periodo)->get();
        //where('nro_order','=','S26417')->get();//all();

        foreach($cabecera as $cab){

           $estado=$this->canal_id($cab->nro_order);
            if(!empty($estado)){
                $pedido=pedido_cab::where('id_odoo','=',$cab->id_odoo)->first();
                //$pedido->fecha_modi=Date('Y-m-d');
                $pedido->id_canal=$estado->canal_id;
                $pedido->canal=$estado->name;
                //return $estado;
                $pedido->save();
 
             }

        }
        
        foreach($cabecera as $cab){

            $estado=$this->actualizar_estados_sunat($cab->nro_order);
            if(!empty($estado)){
                $pedido=pedido_cab::where('id_odoo','=',$cab->id_odoo)->first();
                $pedido->fecha_modi=Date('Y-m-d');
                $pedido->estado_sunat=$estado->sunat_status;
                $pedido->estado_caja=$estado->payment_state;
                //return $estado;
                $pedido->save();
 
             }
        }
        

        foreach($cabecera as $cab){
           
            $estado=$this->anular_pedidos($cab->id_odoo);
            if(!empty($estado)){
               $pedido=pedido_cab::where('id_odoo','=',$cab->id_odoo)->first();
               $pedido->fecha_modi=Date('Y-m-d');
               $pedido->reason_reject=$estado->reason_return;
               $pedido->state='Rechazado';
               //return $estado;
               $pedido->save();

            }
             
        }

        

        foreach($cabecera as $cab){
           
            $estado=$this->modificarestado($cab->id_odoo);
            
            if(!empty($estado)){
               $pedido=pedido_cab::where('id_odoo','=',$cab->id_odoo)->first();
               $pedido->fecha_modi=Date('Y-m-d');
               $pedido->state='Entregado';
               $pedido->numero_comprobante=$estado->serie.' '.$estado->numero;
               $pedido->tipo_documento=$estado->tipo_documento;
                $pedido->driver_id=$estado->id_conductor;
                $pedido->driver=$estado->conductor;
                $pedido->reason_reject='';
               //return $estado;
                 $pedido->save();

            }
            
        }

            

        $this->productos();
        $this->stocdos();
        $this->compras();
        $this->vendedor();
        $this->jefes();
        $this->supervisor();
        $this->proveedores();
        $this->rechazos();
        //$this->tarifas();
        $this->promociones();
        $this->almacenes();
        $this->sic_proveedores();

        //CATEGORIAS,SUBCATEGORIAS,LINEAS,MARCAS,GIROS,stoktime
        return 'EJECUTADO CORRECTAMENTE';

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

            $fabricante=fabricante::where('cod_odoo','=',$ind_desp["data"][$i]["partner_id"])->first();
            if($fabricante==null){
                $fabricante=new fabricante;
            }
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

    public function stocdos(){

        $respuesta=$this->eliminarstock();

        if($respuesta=='OK'){

        $curl = curl_init();
        $datos=$this->token();
        $token=session('key');


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
            'Authorization: Bearer '.$token["result"]["data"]["token"],
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

    }}


    public function eliminarstock(){

        $stock=DB::table('stocktimes')->delete();
         return 'OK';
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




    //METOD PARA ACTUALIZAR LOS ESTADOS DE LA FACTURA
    public function actualizar_estados_sunat($codigo){

        $pedido_odoo=DB::connection('pgsql2')->table('account_move as acc')
        ->join('sale_order as sl','sl.id','=','acc.sale_order_id')
        ->select('acc.sunat_status','acc.payment_state')
        ->where('sl.name','=',$codigo)
        ->where('acc.payment_state','=','paid')
        ->where('acc.move_type','=','out_invoice')
        ->first();

        if(!empty($pedido_odoo)){

            return $pedido_odoo;
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



      ///METODO PARA CAMBIAR EL ESTADO
   public function modificarestado($id_odoo){

    $pedido_odoo=DB::connection('pgsql2')->table('sf_delivery_app as af')
    ->join('account_move as acc','acc.id','=','af.account_id')
    ->join('res_users as ru','ru.id','=','af.user_delivery')
    ->join('res_partner as cli','cli.id','=','ru.partner_id')
    ->join('account_move as av','av.id','=','af.account_id')
    ->join('sunat_series as sr','sr.id','=','av.sunat_serie')
    ->join('sunat_document_type as st','st.id','=','acc.document_type_id')
    ->select('af.id','af.name','af.customer_delivery_status','cli.name as conductor','sr.name as serie',
             'av.sunat_number as numero','st.name as tipo_documento','af.user_delivery as id_conductor')
    ->where('customer_delivery_status','=','delivered')
    ->whereNotNull('account_id')
    ->where('af.order_id','=',$id_odoo)
    ->first();

    if(!empty($pedido_odoo)){

        return $pedido_odoo;

    }

    
}
//METODO PARA ACTUALIZAR EL CANAL Y EL VENDEDOR
public function canal_id($id_odoo){

    $canal=DB::connection('pgsql2')->table('sale_order  as sa')
    ->join('crm_team_sale_canal as crm','crm.id','=','sa.canal_id')
    ->join('res_users as ru','ru.id','=','sa.supervisor_id')
    ->join('res_partner as cli','cli.id','=','ru.partner_id')
    ->select('sa.canal_id','crm.name')
    ->where('sa.name','=',$id_odoo)
    ->first();

    if(!empty($canal)){

        return  $canal;

    }

     
}
//METODO PARA ACTUALIZAR VENDEDORES

/*public function editarvendedor($id_odoo){


    $curl = curl_init();
    $datos=$this->token();
    $token=json_decode($datos,true);

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://economysa.grupoquanam.com/api/customer/history?order_id='.$id_odoo,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token["result"]["data"]["token"],
        'Cookie: session_id=c18603966f4b2ae883f0795a7e1668f963b3b675'
      ),
    ));
    
    $response = curl_exec($curl);
    
        curl_close($curl);
        $pedidos=json_decode($response,true);

        for ($i=0; $i <count($pedidos["data"]) ; $i++) {

            $pedido=pedido_cab::where('id_odoo','=',$pedidos["data"][$i]["id"])->first();
        
            if( $pedido==null){
                $pedido=new pedido_cab;
            }
            
            if(!empty($pedidos["data"][$i]["seller_id"])){
                
                $pedido->seller_id=$pedidos["data"][$i]["seller_id"]["id"];
                $pedido->seller_name=$pedidos["data"][$i]["seller_id"]["name"];
             }

             $pedido->save();
               
        }

        return 'OK';

}*/

//PROMOCIONES
public function promociones(){

    $promociones=DB::connection('pgsql2')->table('rt_promotion as rt')
    ->join('res_partner as rp','rp.id','=','rt.manufacter_id')
    ->leftJoin('product_product as pp','pp.id','=','rt.product_bonf_z')
    ->leftJoin('product_template as pt','pt.id','=','pp.product_tmpl_id')
    ->leftJoin('discount_multi_products as dis','dis.multi_product_dis_rel_id','=','rt.id')
    ->select('rt.id','rt.active','rt.promotion_code','rt.description','rt.promotion_type','rt.manufacter_id','rp.name',
     'rt.company_supplier','rt.from_date','rt.to_date','rt.isztype','rt.is_chatbot','rt.rule_products_domain_x_condition',
     'rt.qty_products_x_factor','rt.qty_products_x_condition','rt.qty_products_x_condition_max','rt.qty_products_z',
     'rt.qty_max_products_z','rt.product_bonf_z','pt.name as producto','dis.discount_line_product_id','dis.minimum_quantity_discount',
     'dis.maximun_quantity_discount','dis.products_discount','dis.isztype as isztype_two')
    ->get();

    //print_r(count($promociones));exit();
    foreach($promociones as $p){

        $promo=Promocion::where('id_odoo','=',$p->id)->first();
        if($promo==null){
            $promo=new Promocion;
        }

        $promo->id_odoo=$p->id;
        $promo->active=$p->active;
        $promo->promotion_code=$p->promotion_code;
        $promo->description=$p->description;
        if($p->promotion_type=='get_y_on_combination_products'){
            $p->promotion_type='BONIFICACIÓN';
        }else{
            $p->promotion_type='DESCUENTOS';

        }
        $promo->promotion_type=$p->promotion_type;
        $promo->manufacter_id=$p->manufacter_id;
        $promo->fabricante_promo=$p->name;
        if($p->company_supplier=='supplier'){
            $promo->company_supplier='PROVEEDOR';
        }else{
            $promo->company_supplier='ECONOMYSA';
        }
        //$promo->company_supplier=$p->company_supplier;
        $promo->from_date=$p->from_date;
        $promo->to_date=$p->to_date;
        if($p->isztype=='qty'){
            $promo->isztype='CANTIDAD';
        }else{
            $promo->isztype='SOLES';

        }

        $promo->is_chatbot=$p->is_chatbot;
       // $promo->rule_products_domain_x_condition=$p->rule_products_domain_x_condition;
        $promo->qty_products_x_factor=$p->qty_products_x_factor;
        $promo->qty_products_x_condition=$p->qty_products_x_condition;
        $promo->qty_products_x_condition_max=$p->qty_products_x_condition_max;
        $promo->qty_products_z=$p->qty_products_z;
        $promo->discount_line_product_id=$p->discount_line_product_id;
        $promo->minimum_quantity_discount=$p->minimum_quantity_discount;
        $promo->maximun_quantity_discount=$p->maximun_quantity_discount;
        $promo->products_discount=$p->products_discount;
        $promo->product_bonf_z=$p->product_bonf_z;
        $promo->producto=$p->producto;

        if($p->isztype_two=='qty'){

            $promo->isztype='CANTIDAD';
        }else{
            $promo->isztype='SOLES';

        }
        $promo->save();
        //$promo->isztype_two=$p->isztype_two;
        //return response()->json($promo);




    }
    //print_r('OK');exit();

}

    //FUNCION PARA CARGAR EL VENDEDOR
    public function vendedor(){

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




    }
    //funcion supervisory
    public function supervisory($codigo){


        $jefes=DB::connection('pgsql2')->table('crm_team_sale_canal as cr')
        ->join('res_users as rs','cr.supervisor_id','=','rs.id')
        ->join('res_partner as res','res.id','=','rs.partner_id')
        ->select('cr.supervisor_id','res.name')
        ->where('cr.supervisor_id','=',$codigo)
        ->get();

        return $jefes[0]->name;

    }


    //FUNCION PARA SICRONIZAR LOS DATOS DE SUPERVISOR
    public function jefes(){

        $jefes=DB::connection('pgsql2')->table('crm_team as crt')
        ->join('res_users as ur','crt.user_id','=','ur.id')
        ->join('res_partner as cli','cli.id','=','ur.partner_id')
        ->select('crt.id','cli.name as jefedes','crt.name as equipo')
        ->get();

        for ($i=0; $i <count($jefes) ; $i++) {

            $jefe=jefeventas::where('cod_jefeventas','=',$jefes[$i]->id)->first();

            if($jefe==null){

                $jefe=new jefeventas;
            }

            $jefe->cod_jefeventas=$jefes[$i]->id;
            $jefe->nombre=$jefes[$i]->jefedes;
            $jefe->equipo=$jefes[$i]->equipo;
            $jefe->save();

      }




    }
    //FUNCION PARA CARGAR LOS SUPERVISORS
    public function supervisor(){
        
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



    }
    //FUNCION PARA PODER REALIZAR LA SICRONIZACIÓN DE PROVEEDORES
    public function proveedores(){


        $curl = curl_init();

        $datos=$this->token();
        $token=session('key');

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

            $fabricante=fabricante::where('cod_odoo','=',$ind_desp["data"][$i]["partner_id"])->first();

            if($fabricante==null){

                $fabricante=new fabricante;

            }


            $fabricante->cod_odoo=$ind_desp["data"][$i]["partner_id"];
            $fabricante->cod_eco=$ind_desp["data"][$i]["code"];
            $fabricante->tipo_documento=$ind_desp["data"][$i]["l10n_latam_identification_type_id"]["name"];
            $fabricante->documento=$ind_desp["data"][$i]["vat"];
            $fabricante->proveedor=$ind_desp["data"][$i]["full_name"];
            $fabricante->cod_compania=$ind_desp["data"][$i]["company_id"]["id"];
            $fabricante->compania=$ind_desp["data"][$i]["company_id"]["name"];
            $fabricante->save();

        }




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
    
              //print_r('ok');
    

    
}


    //FUNCION MOTIVO DE RECHAZO ID
   /* public function motivo($motivos){

        $curl = curl_init();

        //$token=session('key');
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





    }*/
    //FUNCION TARIFA
  



     public function compras(){

        $curl = curl_init();

        $token=session('key');

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

               $detalle=Detalle_compras::where('id_compra','=',$compras["data"][$i]["id"])->first();

               if($detalle==null){
                $detalle=new Detalle_compras;
               }
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


           }



        }





    }



    public function productos(){

        $curl = curl_init();
        $token=session('key');
        
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
              'Authorization: Bearer '.$token["result"]["data"]["token"],
              'Cookie: session_id=a946b4c343cf52f88b1fd0ffff68956c2f1f8b24'
            ),
          ));

          $response = curl_exec($curl);


          curl_close($curl);
          $productos=json_decode($response,true);

          for ($i=0; $i < count($productos["data"]); $i++) {

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
             $stock->idcategoria=$productos["data"][$i]["categ_id"]["id"];
             $stock->categoria=$productos["data"][$i]["categ_id"]["name"];
             $stock->idlinea=$productos["data"][$i]["brand_line_id"]["id"];
             $stock->linea=$productos["data"][$i]["brand_line_id"]["name"];
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

             if(!empty($productos["data"][$i]["seller"])){

                $stock->preciocompra=$productos["data"][$i]["seller"][0]["price"];


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
             $stock->conver_master_unidad=$productos["data"][$i]["conver_master_unidad"];
             $stock->save();
             //return response()->json($stock);
             //print_r('bien');exit();


          }



    }



    public function stock(){



        $curl = curl_init();
        $token=session('key');


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
            'Authorization: Bearer '.$token["result"]["data"]["token"],
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
                $stock=Stock::where('almacen_id','=',$productos["data"][$i]["qty_available"][$j]["id"])
                ->where('cod_producto','=',$productos["data"][$i]["default_code"])
                ->first();


                if($stock==null){
                    $stock=new Stock;
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
                $stock->masterStockAmount=$productos["data"][$i]["masterStockAmount"];
                $stock->save();




             }




        }

        //print_r('ok');exit();






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
                    "company_id":1,
                    "login":"sistemas@economysa.pe",
                    "password":"Eco123."
                }
            }
            ',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE2NjAzMjIwNzUsInVzZXJfaWQiOjQyLCJ1c2VyX25hbWUiOiJURVJFU0EiLCJjb21wYW55X2lkIjoxLCJjb21wYW55X25hbWUiOiJFQ09OT01ZU0EgU0FDIiwidXNlcl90eiI6IkV1cm9wZS9NYWRyaWQiLCJjaGFubmVsX2lkIjoxMTYsImNoYW5uZWxfbmFtZSI6IkNBTkFMIERJR0lUQUwiLCJzYWxlX3RlYW1faWQiOjMsInNhbGVfdGVhbV9uYW1lIjoiRS1DT01NRVJDRSIsInJvdXRlX3BlcmlvZF9pZCI6MTExMiwicm91dGVfbWFzdGVyX2lkIjo0MzUsImFycmF5X21vZHVsb3MiOlsiMjA2MyIsIjMzMDQ5IiwiMzMwNjkiLCIzMzEwMCIsIjMzMTcxIiwiMzMyMTEiLCIzMzIyNCIsIjMzMjI1IiwiMzMyMzAiLCIzMzI3MiIsIjMzMzA4IiwiMzMzNTkiLCIzMzM3MSIsIjMzMzc0IiwiNDMwMDciLCI0MzA4MSIsIjQzMTQwIiwiNDMxNzUiLCI0MzIyOSIsIjIwODMiLCI5OTk5OSIsIjA2MTU0IiwiMDYxNTYiLCIwNjE2MSIsIjA2MTcxIiwiMDYxNzMiLCIwNjIwMiIsIjA2MjQxIiwiMTAwMjAiLCIxMDAyMiIsIjEwMDI3IiwiMTAwNjkiLCIxMDA5MCIsIjEwMTU1IiwiMTAxNjMiLCIxMDE3MiIsIjEwMTc0IiwiMTAxODAiLCIxMDE4MyIsIjEwMjAxIiwiMTAyMjUiLCIxMDIyNyIsIjEwMjMxIiwiMTAyMzIiLCIxMDIzOCIsIjEwMjc1IiwiMTAzNDQiLCIxMDQzMCIsIjEwNDMyIiwiMTA0NDMiLCIxMDUwNCIsIjEwNTEwIiwiMjA3MyIsIjMwMDE5IiwiMTUwMDkiLCIxNTAxMiIsIjE1MDMzIiwiMTUwNDIiLCIxNTA0OCIsIjE1MDc1IiwiMTUwOTQiLCIxNTEzMSIsIjIwNTMiLCIyMjA0NSIsIjMwMDI2IiwiMzQwMTAiLCI0MDAwMyIsIjQwMDE3IiwiNDAwODgiLCI0MDA5MCIsIjQwMTA4IiwiNDEwNDciLCI0MTA1OCIsIjQxMDY1Il0sInR5cGVfdXNlciI6IiJ9.EEnQZu6nZNrPlXMxfGflpiyPpew6oYw7KdKy8VKJ1Wk',
                'Content-Type: application/json',
                'Cookie: session_id=08661fe10a6f86229808fc5873da6454c8b530b2'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
           
          return $response;



    }









}
