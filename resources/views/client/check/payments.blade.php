@extends('layouts.client_view')

@section('head')

@append

@section('content')



    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >

        @if($result == true)
            <h1><center>Договор оплачен!</center></h1>
        @else
            <h1><center>Договор не оплачен!</center></h1>
            <h2 style="color: red;"><center>{{$error}}</center></h2>
        @endif



    </div>





@append