@extends('client.layouts.app')

@section('head')

@append

@section('content')


    <div class="row row__custom justify-content-between">




            <div class="reviews__item form__list col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <form method="POST" action="{{ urlClient('/registration') }}" id="authorizationForm">
                    {{ csrf_field() }}

                <div class="row row__custom">
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                        <div class="form__field" style="margin-top: 10px;margin-left: 5px;font-size: 18px;font-weight: bold;">
                            Востановления пароля
                        </div>
                    </div>
                </div>


                @if (isset($errors))
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger text-center">
                            <button class="close" data-close="alert"></button>
                            <span> {{ $error }}</span>
                        </div>
                    @endforeach
                @endif

                @if (session('success') && !count($errors))
                    <div class="alert alert-success text-center">
                        <button class="close" data-close="alert"></button>
                        {{ session('success') }}
                    </div>
                @else

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>


                <div class="row row__custom">



                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col__custom form__item">
                        <div class="form__field">
                            <input type="text" class="" name="email" value="{{old('email')}}" required>
                            <div class="form__label">Email <span class="required">*</span></div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col__custom form__item">
                        <div class="form__field">
                            <div class="select__wrap">
                                {{Form::select("product_id", $products->pluck('title', 'id'), old('product_id'), ['class' => '']) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col__custom form__item">
                        <div class="form__field">
                            <input type="text" class="" name="bso_title" value="{{old('bso_title')}}" required>
                            <div class="form__label">Номер договора <span class="required">*</span></div>
                        </div>
                    </div>

                </div>

                <div class="row row__custom">

                    <input type="hidden" value="0" name="doc_type"/>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col__custom form__item">
                        <div class="form__field">
                            <input type="text" class="" name="doc_serie" value="{{old('doc_serie')}}" required>
                            <div class="form__label">Паспорт серия <span class="required">*</span></div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col__custom form__item">
                        <div class="form__field">
                            <input type="text" class="" name="doc_number" value="{{old('doc_number')}}" required>
                            <div class="form__label">Паспорт номер <span class="required">*</span></div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col__custom form__item">
                        <div class="form__field">
                            <input type="text" class="format-date date" name="doc_date" value="{{old('doc_date')}}" required>
                            <div class="form__label">Дата выдачи <span class="required">*</span></div>
                        </div>
                    </div>

                </div>
                <br/><br/>
                <div class="row row__custom">
                    <div class="row__custom col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                        <input type="submit" value="Востановить пароль" class="btn__round d-flex align-items-center justify-content-center"/>

                    </div>
                </div>

                @endif
                </form>
            </div>




    </div>



@endsection

@section('js')

    <script>
        $(function () {

            activeInputForms()


        });

    </script>

@endsection
