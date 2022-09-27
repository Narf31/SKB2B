$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


});

function activeInputForms() {

    $('.form__field input, .form__field textarea').on('change', function(){
        if($(this).val()){
            $(this).closest('.form__field').addClass('filled');
        }else{
            $(this).closest('.form__field').removeClass('filled');
        }
    });

    $('.form__label, .form__field input, .form__field textarea').on('click', function(){
        $(this).closest('.form__field').addClass('focused');
        $(this).closest('.form__field').find('input, textarea').focus();
    });


    $('.select__wrap select').styler();


    $(document)
        .on('focus', '.format-date', function () {
            $(this).datepicker({
                dateFormat: 'dd.mm.yy',
                lang: 'ru',
                changeMonth: true,
                changeYear: true,
                yearRange: '1930:2030'
            });
            $(this).mask('99.99.9999');
        });


    $('input, textarea').on('click', function(){
        $(this).css('border-color', '');
    });

    $('input, textarea').on('change', function(){
        $(this).css('border-color', '');
    });


    $('.form__field input, .form__field textarea').change();

}


function reload() {
    window.location.reload();
}