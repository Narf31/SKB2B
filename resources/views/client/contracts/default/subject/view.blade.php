<div class="content__box" style="width: 100%;padding-bottom: 20px;">
    <div class="content__box-title seo__item">
        {{$subject_title}}
    </div>

            @include("client.contracts.default.subject.partials.view.{$subject->type}", [
            'subject_title' => $subject_title,
            'subject_name' => $subject_name,
            'subject_original' => ($subject),
            'subject' => ($subject->get_info())
        ])

</div>
