@extends('layouts.frame')

@section('title')

    Сменить пароль

    <span style="color: red;" id="erors"></span>

@stop

@section('content')

    <div class="form-horizontal">

        <div class="form-group">
            <label class="col-sm-4 control-label">Пароль</label>
            <div class="col-sm-8">
                {{ Form::password('password', ['class' => 'form-control password', 'required']) }}
            </div>
        </div>
    
        <div class="form-group">
            <label class="col-sm-4 control-label">Новый пароль</label>
            <div class="col-sm-8">
                {{ Form::password('new_password', ['class' => 'form-control new_password', 'required']) }}
            </div>
        </div>
    
        <div class="form-group">
            <label class="col-sm-4 control-label">Повторите пароль</label>
            <div class="col-sm-8">
                {{ Form::password('check_password', ['class' => 'form-control check_password', 'required']) }}
            </div>
        </div>

    </div>
    
@endsection

@section('footer')
    

    <button onclick="changePassword()" type="submit" class="btn btn-primary">Применить</button>

@stop


@section('js')
    <script>
        
        
        function changePassword() {
            $("#erors").html('');

            password = $(".password").val();
            new_password = $(".new_password").val();
            check_password = $(".check_password").val();

            if(new_password != check_password){
                $("#erors").html('Пароли не совпадают!');
                return false;
            }

            if(new_password.length < 3){
                $("#erors").html('Пароль слишком короткий!');
                return false;
            }

            loaderShow();
            $.post("/account/password", {password:password, new_password:new_password}, function (response)  {
                loaderHide();

                if(response.state == 1){
                    closeFancyBoxFrame();
                }else{
                    $("#erors").html(response.msg);
                    return false;
                }



            })  .done(function() {
                loaderShow();
            })
                .fail(function() {
                    loaderHide();
                })
                .always(function() {
                    loaderHide();
                });


        }
        
    </script>

@stop