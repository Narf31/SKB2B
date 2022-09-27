<div class="row form-horizontal" >
    <h2 class="inline-h1">{{$subject_title}} - @if($subject->type == 0 || $subject->type == 2) ФЛ @else ЮЛ @endif</h2>
    <br/><br/>
    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
        @include("contracts.default.subject.partials.view.{$subject->type}", [
            'subject_title' => $subject_title,
            'subject_name' => $subject_name,
            'subject_original' => ($subject),
            'subject' => ($subject->get_info())
        ])
    </div>
</div>
