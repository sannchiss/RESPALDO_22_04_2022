@extends('layouts.app')
@section('style')
<style type="text/css">
  .card {  color: #212529;}
  .card:hover{
    text-decoration: none;
    color: #212529;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(0, 123, 255, 0.6);
  }

</style>

@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12" >
            <div class="jumbotron" style="background-image: url('{{ asset('img/cartoons/background.png')}}');  background-repeat: no-repeat; background-size: 100%;">
             
              <div class="row" >

                <div class="col-md-9">
                  <div class="text-center"> <img src="{{ asset('img/sigecon.png') }}"></div>
                  <h1 class="display-4 text-center">¡Bienvenidos a Aguila!</h1>
                  <p class="lead text-center">Sistema de gestion y monitoreo de pedidos online.</p>
                </div>
                <div class="col-md-3">
                  <img src="{{ asset('img/cartoons/eagle.png') }}" class="img-fluid">
                </div>

              </div>

              <hr class="my-4">

              <div class="card-deck">
                <a class="card" href="{{ route('monitoring.index')}}">
                   <img src="{{ asset('img/cartoons/localizacion2.png') }}" class="card-img-top" alt="">
                  <div class="card-body">
                    <h5 class="card-title">Localización de Vehiculos</h5>
                    <p class="card-text">Con Aguila hacer seguimiento de la ubicación de nuestros vehiculos es mas facil que nunca.</p>
                  </div>
                </a>
                <a class="card" href="{{ route('pick_delivery.index')}}">
                  <img src="{{ asset('img/cartoons/rutas.jpg') }}" class="card-img-top" alt="">
                  <div class="card-body">
                    <h5 class="card-title">Estado de Rutas</h5>
                    <p class="card-text">Ver tantas rutas a la vez es todo un dolor de cabeza, Aguila facilita la visualizacion del estado general de las rutas y documentos en reparto</p>
                  </div>
                </a>
                <a >
                <a class="card" href="{{ route('home.sales')}}">
                  <img class="card-img-top" src="{{ asset('img/cartoons/worker.jpg') }}"  alt="Card image cap">
                  <div class="card-body">
                    <h5 class="card-title">Consulta Vendedores</h5>
                    <p class="card-text">Prisila y Priamo son nuestros vendedores estrella, ellos utilizan Aguila para consulta los pedidos de sus clientes.</p>
                  </div>
                </a>
              </div>
              {{-- <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
              <a class="btn btn-primary btn-lg" href="#" role="button"> more</a> --}}
            </div>
        </div>
    </div>
</div>
@endsection
