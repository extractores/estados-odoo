@extends('layouts.admin')
@section('contenidos')

@section('title')

        Cliente

@endsection

@section('style')
<!-- Sweet Alert-->
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="{{asset('css/stylos.css')}}">

@endsection



<div class="page-content">

<div class="container-fluid">

<!-- start page title -->
                 <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Cliente</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                    <li class="breadcrumb-item active">DataTables</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="{{url('clientes/create')}}" type="button" class="btn btn-success waves-effect btn-label waves-light" ><i class="bx bx-plus label-icon"></i> Agregar</a>

                                </p>
                            </div>

                            <div class="card-body">

                            <!-- Static Backdrop modal Button -->
                            <div class="col-lg-12">
                                <button onclick="clientes();">Enviar info</button>
                                <a class="btn btn-warning" href="{{ route('export') }}">Export User Data</a>

                                   <div class="table-responsive">

                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>companyId</th>
                                        <th>paternalSurname</th>
                                        <th>maternalSurname</th>
                                        <th>name</th>
                                        <th>documentType</th>
                                        <th>documentNumber</th>

                                        <th>address</th>
                                        <th>latitude</th>
                                        <th>longitude</th>
                                        <th>routeId</th>
                                        <th>Codigo_economysa</th>
                                    </tr>
                                    </thead>


                                    <tbody id="listadecolores">


                                    </tbody>
                            </table>


                            </div>

                                            </div>

                            </div>
                        </div>
                    </div>
                </div>

</div>

</div>








@endsection
@section('script')

<!-- Sweet Alerts js -->
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<!-- Required datatable js -->
<script src="{{asset('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

     <script src="js/cliente.js">
     </script>


@endsection
