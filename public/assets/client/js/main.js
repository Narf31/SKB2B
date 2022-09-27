$(document).ready(function() {
  $('.scroll').on('click', function(e) {
    $('html, body').stop().animate({
      scrollTop: $($(this).attr('href')).offset().top - $('.header').outerHeight() + "px"
    }, 850);
    e.preventDefault();
  });

  if($('.event__item-title').length){
    $('.event__item-title').matchHeight();
  }

  if ($('.partners__slider').length) {
    $('.partners__slider').slick({
      dots: false,
      prevArrow: '.partners__box .slide__prev',
      nextArrow: '.partners__box .slide__next',
      slidesToShow: 5,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 992,
          settings: {
              slidesToShow: 4
          }
        },{
          breakpoint: 768,
          settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              dots: true
          }
        },{
              breakpoint: 576,
              settings: {
                  slidesToShow: 2,
                  dots: true,
                  slidesToScroll: 2
              }
          }
      ]
    });
  }

  if ($('.reviews__slider').length) {
    $('.reviews__slider').slick({
      dots: false,
      prevArrow: '.reviews__box .slide__prev',
      nextArrow: '.reviews__box .slide__next',
      speed: 700,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    dots: true
                }
            }
        ]
    });
  }

  $('.form__field input, .form__field textarea').on('change', function(){
    if($(this).val()){
      $(this).closest('.form__field').addClass('filled');
    }else{
      $(this).closest('.form__field').removeClass('filled');
    }
  });

  $('.form__label').on('click', function(){
    $(this).closest('.form__field').addClass('focused');
    $(this).closest('.form__field').find('input, textarea').focus();
  });

  $('.form__field > *').focus(function(){
    $(this).closest('.form__field').addClass('focused');
  });

  $('.form__field > *').blur(function(){
    $(this).closest('.form__field').removeClass('focused');
  });

  if($('.select__wrap select').length){
    $('.select__wrap select').styler();
  }

  $(document).on('change', '.select__wrap select', function () {
    $(this).closest('.form__field').addClass('filled');
  });

  $(document).on('click', '.btn__next', function (e) {
    var parent = $(this).closest('.calc__item');
    var step = parseInt($(this).closest('.calc__step').attr('data-step'));



    if(step !== parseInt($('.calc__step', parent).length) && getStepValid(step) == true){
      $('.calc__step', parent).removeClass('active');
      $('.steps__current', parent).text(step + 1);
      $('.calc__progress .bar', parent).css('width', ((step + 1) / $('.calc__step', parent).length * 100) + '%');
      $('.calc__step[data-step="' + (step + 1) + '"]', parent).addClass('active');

        $('html, body').stop().animate({
            scrollTop: 0 + "px"
        }, 0);
    }
    e.preventDefault();
  });

  $(document).on('click', '.btn__prev', function (e) {
    var parent = $(this).closest('.calc__item');
    var step = parseInt($(this).closest('.calc__step').attr('data-step'));
    if(step !== 1  && getCalcStepValid(step) == true){

      $('.calc__step', parent).removeClass('active');
      $('.steps__current', parent).text(step - 1);
      $('.calc__progress .bar', parent).css('width', ((step - 1) / $('.calc__step', parent).length * 100) + '%');
      $('.calc__step[data-step="' + (step - 1) + '"]', parent).addClass('active');
      e.preventDefault();
    }
  });

  $(document).on('click', '[data-step="7"] button', function (e) {
    $('.calc__thanks').addClass('active');
    e.preventDefault();
  });

  $(document).on('change', '.toggle__check-js input', function () {
    let field = $(this).closest('.toggle__check-js').attr('data-id');
    if(parseInt($(this).val()) === 1){
      $('#' + field).addClass('active');
    }else{
      $('#' + field).removeClass('active');
    }
  });

  $('#add-review input, #add-review textarea').keypress('', function () {
      checkReviewForm();
  });

  $(document).on('change', '#add-review input, #add-review textarea', function () {
      checkReviewForm();
  });

  $(document).on('click', '.load__reviews-js', function (e) {
    let new_reviews = '<div class="reviews__item fadeIn animated">' +
        '<div class="reviews__item-header d-flex align-items-center">' +
          '<i class="icon__norm"></i>' +
        '<div class="reviews__item-name">' +
          'Иван Охлобыстин' +
        '</div>' +
        '</div>' +
        '<div class="reviews__item-date">' +
          '23.09.2019' +
        '</div>' +
        '<div class="reviews__item-text">' +
          'Не сотрудники а твари ,разговаривают как попало гнилые просто люди ,невозможно просто ужасная компания ,там работают мошенники одни .....Надежда и Роза просто ужасные сотрудники я не знаю как таких людей вы взяли на работу !!!обязательно приеду и напишу жалобу на них ,не поленюсь ещё подам на вашу компанию в суд !!' +
        '</div>' +
        '</div>';
    $('.reviews__list').append(new_reviews);
    e.preventDefault();
  });
  if($('.driver__numb').length){
    updateDriverNumbers();
  }

  $(document).on('click', '.delete__driver-js', function (e) {
    $(this).closest('.driver__item').remove();
    updateDriverNumbers();
    e.preventDefault();
  });

  $(document).on('click', '.add__driver-js', function (e) {

    $('.select__wrap select').styler();
    updateDriverNumbers();
    e.preventDefault();
  });

  $(document).on('click', '.nav__bars', function (e) {
    $('body').toggleClass('menu-mode');
    e.preventDefault();
  });

  $(document).on('click', '.get__sms-js', function (e) {
    $(this).closest('.calc__step').find('.btn__next').trigger('click');
    e.preventDefault();
  });

  if($.browser.mobile){
    if($('.reviews__item-text').length){
      $('.reviews__item-text').matchHeight();
    }
  }

  $(document).on('submit', '.form__review form', function () {
    $('.form__review-box').removeClass('active');
    $('#form-review-2').addClass('active');
    return false;
  });

  $(document).on('click', '.back__review-form-js', function (e) {
    $('#form-rw')[0].reset();
    $('.form__review .form__field').removeClass('filled');
    $('.form__review-box').removeClass('active');
    $('#form-review-1').addClass('active');
    e.preventDefault();
  });

  $(document).on('change', '.form__field-autocomplete input', function () {
    let parent = $(this).closest('.form__field-autocomplete');
    $('.autocomplete__list', parent).addClass('active');
  });
});


$(window).on('load', function() {
  let w_h = $(window).height();
});

function checkReviewForm() {
    let name = $('#add-review [name="name"]').val();
    let mark = $('#add-review [name="mark"]');
    let message = $('#add-review [name="message"]').val();
    if(name.length >= 1 && message.length >= 1 && mark.is(":checked")){
        $('#add-review button').attr('disabled', false);
    }else{
        $('#add-review button').attr('disabled', true);
    }
}

function updateDriverNumbers(){
  let i = 1;
  if($('.driver__item').length === 1){
    $('.driver__item').addClass('hide__delete-item');
  }else{
      $('.driver__item').removeClass('hide__delete-item');
  }
  $('.driver__item').each(function () {
    $('.driver__numb', $(this)).text(i);
    i++;
  });
}





(function(a){($.browser=$.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);
