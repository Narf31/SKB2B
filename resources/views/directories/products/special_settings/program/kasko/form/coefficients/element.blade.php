@if(isset($group['is_adjacent']) && (int)$group['is_adjacent'] == 1)

    @php
        function getCoefficientVal($coefficient, $type, $name){
            $json = $coefficient->json;
            if(strlen($json) > 0){
                $json = json_decode($json, true);
                if(isset($json[$type]) && isset($json[$type][$name])){
                    return $json[$type][$name];
                }
            }
            return '';
        }
    @endphp

    @foreach($group['control']['data'] as $control)
        <h2>{{$coefficients[$control]['title']}}</h2>

        @if($coefficients[$control]['control']['type'] == 'range')

            <div class="form-group">
                <label class="col-sm-4 control-label">Больше или равно</label>
                <div class="col-sm-8">
                    {{ Form::text("coefficient[{$coefficients[$control]['field']}][value_to]", getCoefficientVal($coefficient, $coefficients[$control]['field'], 'value_to'), ['class' => 'form-control sum']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Меньше или равно</label>
                <div class="col-sm-8">
                    {{ Form::text("coefficient[{$coefficients[$control]['field']}][value_from]", getCoefficientVal($coefficient, $coefficients[$control]['field'], 'value_from'), ['class' => 'form-control sum']) }}
                </div>
            </div>

        @endif


        @if($coefficients[$control]['control']['type'] == 'select')

            <div class="form-group">
                <label class="col-sm-4 control-label">Значения</label>
                <div class="col-sm-8">
                    {{Form::select("coefficient[{$coefficients[$control]['field']}][value]", collect($coefficients[$control]['control']['value']), getCoefficientVal($coefficient, $coefficients[$control]['field'], 'value'), ['class' => 'form-control select2-all'])}}
                </div>
            </div>

        @endif


    @endforeach

@else


    @if($group['control']['type'] == 'select')

        <div class="form-group">
            <label class="col-sm-4 control-label">Значения</label>
            <div class="col-sm-8">
                {{Form::select("value", collect($group['control']['value']), ($coefficient)?$coefficient->value:'', ['class' => 'form-control select2-all'])}}
            </div>
        </div>



    @endif

    @if($group['control']['type'] == 'range')

        <div class="form-group">
            <label class="col-sm-4 control-label">Больше или равно</label>
            <div class="col-sm-8">
                {{ Form::text('value_to', ($coefficient)?$coefficient->value_to:'', ['class' => 'form-control '.$group['control']['to']]) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">Меньше или равно</label>
            <div class="col-sm-8">
                {{ Form::text('value_from', ($coefficient)?$coefficient->value_from:'', ['class' => 'form-control '.$group['control']['from']]) }}
            </div>
        </div>

    @endif

@endif