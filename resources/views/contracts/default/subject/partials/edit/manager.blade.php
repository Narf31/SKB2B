
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 27px;" >
    <h2 class="inline-h1">Контактное лицо</h2>
    <div class="clear"></div>
</div>


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
    <label class="control-label">Должность</label>
    {{ Form::text("contract[{$subject_name}][manager][position]", $manager->manager_position, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_manager_position", 'data-key'=>"{$subject_name}", 'placeholder' => 'Генеральный директор']) }}
</div>

<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" >
    <label class="control-label">ФИО <span class="required">*</span></label>
    {{ Form::text("contract[{$subject_name}][manager][fio]", $manager->manager_fio, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_manager_fio", 'data-key'=>"{$subject_name}", 'placeholder' => 'Иванов Иван Иванович']) }}
</div>


<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
    <label class="control-label">Дата рождения <span class="required">*</span></label>
    {{ Form::text("contract[{$subject_name}][manager][birthdate]", setDateTimeFormatRu($manager->manager_birthdate, 1), ['class' => 'form-control not_valid format-date ', 'id'=>"{$subject_name}_birthdate", 'placeholder' => '18.05.1976']) }}
    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
</div>


<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
    <label class="control-label">Моб. телефон</label>
    {{ Form::text("contract[{$subject_name}][manager][phone]", $manager->manager_phone, ['class' => 'form-control phone not_valid', 'placeholder' => '+7 (451) 653-13-54']) }}
</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
    <label class="control-label">Email</label>
    {{ Form::text("contract[{$subject_name}][manager][email]", $manager->manager_email, ['class' => 'form-control not_valid', 'placeholder' => 'test@mail.ru']) }}
</div>



