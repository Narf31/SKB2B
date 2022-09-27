/** TOP MENU **/
fixTopMenu();
var windowWidth = $(window).width();
var menuResizeInProccess = 0;
$(window).resize(function () {
    menuResizeInProccess = menuResizeInProccess + 1;
    if (windowWidth > $(window).width()) {
        setTimeout(function () {
            menuResizeInProccess = menuResizeInProccess - 1;
            if (menuResizeInProccess == 0) {
                fixTopMenu();
            }
        }, 100);
    } else {
        menuResizeInProccess = menuResizeInProccess - 1;
        fixTopMenu();
    }
});

function init_doc_btn(){

    $('.doc_export_btn').off().attr('title', 'Shift + click для перехода на страницу документации');
    $(document).on('click', '.doc_export_btn', function(e) {
        if(e.shiftKey){
            var href = $(this).attr('href');

            var addr = "/" + trim(href, '/');
            var search = (addr.indexOf('?') === -1 ? "?" : "&") + "documentation=1";

            location.href =  "/" + trim(href, '/') + search;
            return false;

        }
    });
}

$(document).ready(function () {

    init_doc_btn();


    $.ajaxSetup({
        'complete' : function(){
            loaderHide();
            init_doc_btn();
        }
    });

    $(document).on('focus', 'input', function(){
       $(this).attr('autocomplete','off');
    });

    ///загадочное исчезновение алертов
    $.each($('.alert'), function(k,v){
        setTimeout(function () {
            $(v).animate({
                opacity: 0.1,
                //top: "-=250"
            }, 800, function () {
                $(this).remove();
            });
        }, 4000);
    });


    // Проверяем поддерживает ли браузер FormData. Если нет -  алертами.
    if (typeof window.FormData === 'undefined') {
        alert('Ваш браузер устарел, пожалуйста, обновите браузер!');
    }


    $(document).on('click', '[data-notification]', function(){
        var notification = $(this).data('notification');
        var THIS = $(this);
        $.post('/users/notification/'+notification+'/read', {}, function(res){
            if(res.status === 'ok'){
                THIS.closest('.notification_body').remove()
            }
        });
        return false;
    });


    $(document).on('click', '.block-sub-collapser', function(){
        var THIS = $(this);
        var prev = $(this).prev();
        var collapsed = $(this).hasClass('collapsed');
        var title = $(this).data('title');

        if(prev.hasClass('block-sub')){
            if(collapsed){
                $(this).siblings('[data-colapse-title]').remove();
                prev.animate({opacity: 1, height: "toggle"}, 300, 'linear', function(){
                    THIS.removeClass('collapsed');
                });
            }else{
                prev.animate({opacity: 0, height: "toggle"}, 300, 'linear', function(){
                    THIS.addClass('collapsed');
                });
                prev.before($('<h3 class="inline-h1" data-colapse-title style="margin-left: 10px; margin-top: 2px;">'+title+'</h3>'));
            }
        }
    })

});


function fixTopMenu() {
    $('#bs-example-navbar-collapse-1').attr('style', 'display: block !important;position:absolute;margin-top:-9999px'); //у невидимых элементов нельзя узнать ширину...

    var containerWidth = $('#header .navbar').width();
    var headerTopRightWidth = $('.header-top-right').width();
    var menuLeftOffset = $('.logo').offset().left + $('.logo').width() + 30;

    var menuWidth = 0;
    $('#bs-example-navbar-collapse-1 > ul > li').each(function (index, value) {
        menuWidth = menuWidth + $(this).width() + containerWidth / 100 * 1.7; // TODO: добавить в результаты margin-right
    });
    $('#bs-example-navbar-collapse-1').removeAttr('style');
    if ((containerWidth - headerTopRightWidth - menuWidth - menuLeftOffset) > 0) {
        $('#sidebarMainMenu').addClass('hidden');
        $('#bs-example-navbar-collapse-1').hide().removeClass('collapse').show();
    } else {
        $('#sidebarMainMenu').removeClass('hidden');
        if (!$('#bs-example-navbar-collapse-1').hasClass('collapse')) {
            $('#bs-example-navbar-collapse-1').addClass('collapse');
        }
    }

    $('.user-data-container').width($('.user-data').width()); //ширина окна с информацией по пользователю
}

/**
 * LEFT SIDEBAR
 */
$(document).ready(function () {
    $("#sidebar").niceScroll({
        cursorcolor: '#df4421',
        cursorwidth: 4,
        cursorborder: 'none'
    });

    $('#dismiss, .overlay').on('click', function () {
        $('#sidebar').removeClass('active');
        $('.sidebar-header').animate({height: "76px"}, 500);
        $('.overlay').fadeOut();
        //fix nicescroll position
        $("#sidebar").getNiceScroll().hide();
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').addClass('active');
        $('.sidebar-header').animate({height: "53px"}, 500);
        //fix nicescroll position
        setTimeout(function () {
            $("#sidebar").getNiceScroll().show();
            $("#sidebar").getNiceScroll().resize();
        }, 350);
        $('.overlay').fadeIn();
        //$('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    $(".user-list-container").niceScroll({
        cursorcolor: '#df4421',
        cursorwidth: 4,
        cursorborder: 'none'
    });

});

/** HEADER USER MESSAGES **/
$(document).ready(function () {
    // $(".user-messages-container").niceScroll({
    //     cursorcolor: '#df4421',
    //     cursorwidth: 4,
    //     cursorborder: 'none'
    // });
    //
    // $(".user-description .role-description").niceScroll({
    //     cursorcolor: '#df4421',
    //     cursorwidth: 4,
    //     cursorborder: 'none'
    // });

    $('.user-messages').on('mouseenter', function () {
        // $('.user-messages .user-messages-container').show();
        // $('.user-messages .user-list-container').show();
    });
    $('.user-messages').on('mouseleave', function () {
        // $('.user-messages .user-messages-container').hide();
        // $('.user-messages .user-list-container').hide();
    });
});

// Tables
//
$(document).ready(function() {



    $('.tov-table').DataTable({
        autoWidth: true,
        searching: false,
        info: false,
        paging: false,

    });


    $('.tov-table-no-sort').DataTable({
        autoWidth: true,
        searching: false,
        info: false,
        paging: false,
        ordering: false
    });




    $('.defaultTable').DataTable({
        scrollX: true,
        scrollCollapse: true,


        columnDefs: [{
            width: '10%',
            targets: '_all'
        }],

        searching: false,
        info: false,
        paging: false,
    });

    $('.selectDriverTable').DataTable({
        scrollX: true,
        scrollCollapse: true,
        autoWidth: true,

        columns: [
            { width: '5%' },
            { width: '5%' },
            { width: '55%' },
            { width: '35%' }
        ],

        searching: true,
        info: false,
        paging: false,
    });

    $('.noScrollTable').DataTable({
        autoWidth: true,
        searching: false,
        info: false,
        paging: false,
    });

    $('.routeStatusTable').DataTable({
        scrollX: true,
        scrollY: '500px',
        scrollCollapse: true,

        columnDefs: [{
            width: '10%',
            targets: '_all'
        }],

        searching: false,
        info: false,
        paging: false,
    });

    $('.orderStatusTable').DataTable({
        scrollX: true,
        scrollY: '400px',
        scrollCollapse: true,

        columnDefs: [{
            width: '10%',
            targets: '_all'
        }],

        searching: false,
        info: false,
        paging: false,
    });

    $('.fixedTable').DataTable({
        destroy: true,
        scrollY: "400px",
        scrollCollapse: true,
        searching: false,
        info: false,
        paging: false,
    });

    $('.largeTable').DataTable({
        destroy: true,
    });
});

//fancybox
//
$('body').on('click', '.fancybox\\.iframe', function () {
    $.fancybox.open({
        type: 'iframe',
        href: $(this).attr('href'),
    });
});

$('.fancybox').fancybox();

$('.fancybox-custom').fancybox({
    type: 'iframe',
    href: $(this).attr('href'),
    height: $(this).data('height'),
    width: $(this).data('width'),
    autoDimensions: false,
    autoSize: false
});

$('.fancybox-parent').click(function () {
    var href = $(this).attr('href');
    parent.$.fancybox({
        type: 'iframe',
        href: href,
        height: '360px',
        fitToView: false,
        autoSize: false
    });
    return false;
});

function openFancyBoxFrame(href) {
    $('.fancybox').fancybox(
        parent.$.fancybox({
            type: 'iframe',
            href: href,
            fitToView: false,
            autoSize: true,
        })
    );
}

function openFancyBoxFrameScroll(href) {
    $('.fancybox').fancybox(
        parent.$.fancybox({
            type: 'iframe',
            href: href,
            autoSize: true,
        })
    );
}

$(".clickable-row").click( function(){
    if ($(this).attr('data-href')) {
        location.href = $(this).attr('data-href')
    }
});

$(".clickable-row-blank").click( function(){
    if ($(this).attr('data-href')) {
        window.open($(this).attr('data-href'), '_blank');
    }
});


/** APPLICATION **/

/** Функция отправки данных ajax-ом **/
$.ajaxPost = function (url, data, successCallback, timeout) {

    if (timeout == undefined || !($.isNumeric(timeout))) {
        timeout = 120000;
    }

    //Если пришел объект с данными не являющимся экземпляром класса FormData - превращаем объект/массив в FormData
    if (!(data instanceof FormData)) {
        if (data instanceof Object || data instanceof Array) {
            var cachedData = data;
            data = new FormData();
            for (var key in cachedData) {
                if (cachedData[key] instanceof Object || cachedData[key] instanceof Array) {
                    for (var keyCached in cachedData[key]) {
                        data.append(key + '[' + keyCached + ']', cachedData[key][keyCached]);
                    }
                } else {
                    data.append(key, cachedData[key]);
                }
            }
        } else {
            //console.log('Неправильный формат данных для запроса по адресу ' + url);
            return false;
        }
    }

    if (!empty($('meta[name="csrf-token"]').attr("content"))) {
        data.append('_csrf', $('meta[name="csrf-token"]').attr("content")); //FormData.get() не работает в Safari + IE :(
    }

    var settings = {
        type: "POST",
        processData: false,
        contentType: false,
        'url': url,
        'data': data,
        'success': function (responseData) {
            try {
                if (isJsonString(responseData)) {
                    responseData = $.parseJSON(responseData);
                }
                if (responseData.hasOwnProperty('errors') && !empty(responseData.errors.description) && !empty(responseData.errors.code)) {
                    errorModal(['Ошибка действия. ' + responseData.errors.description]);
                    unBlockScreen();
                    return false;
                }
            } catch (e) {
                errorModal(['Ошибка действия.' + e]);
                unBlockScreen();
                return false;
            }

            //Запуск callback функции
            if (!empty(successCallback) && isFunction(successCallback)) {
                window[successCallback](responseData, cachedData);
            } else if ($.isFunction(successCallback)) {
                successCallback(responseData, cachedData);
            } else {
                //console.log('Функция ' + successCallback + ' не найдена.');
            }
        },
        'error': function (jqXHR, exception, message) {
            if (jqXHR.status == 302) {
                if (!empty(jqXHR.getResponseHeader("X-Redirect"))) {
                    window.location.href = jqXHR.getResponseHeader("X-Redirect");
                }
            } else if (jqXHR.status == 404) {
                errorModal(['Отсутствует запрашиваемая страница.']);
            } else if (jqXHR.status == 500) {
                errorModal(['Возникла ошибка. Пожалуйста, обратитесь в техническую поддержку.']);
            } else if (exception === 'parsererror') {
                errorModal(['Возникла ошибка. Пожалуйста, обратитесь в техническую поддержку.']);
            } else if (exception === 'timeout') {
                errorModal(['Превышено время ожидания запроса. Предположительно проблемы со связью или сервер временно отключен.']);
            } else if (exception === 'abort') {
                errorModal(['Запрос отменен.']);
            } else if (jqXHR.status == 413) {
                errorModal(['Прикреплен слишком большой файл :( Действие не завершено.']);
            } else if (jqXHR.status == 0) {
                return;
            } else {
                errorModal([message + ' Ошибка действия. Пожалуйста, попробуйте позднее.']);
            }
            unBlockScreen();
        },
        'timeout': timeout
    };

    $.ajax(settings);
};

/** Повесить прелоадер на всё окно **/
function blockScreen(text) {

    if (empty(text)) {
        var loadingText = 'Загрузка...';
    } else {
        var loadingText = text;
    }

    $('input').blur();
    $('select').blur();
    $('textarea').blur();
    $('form').blur();
    $('.btn').blur();
    $('a').blur();

    isLoading({text: loadingText}).loading();
}

/** Убрать прелоадер **/
function unBlockScreen() {
    //TODO: гадит ошибку когда пытаешься убрать не активированный isLoading. https://github.com/hekigan/is-loading/issues/20
    $('.is-loading-text-wrapper').each(function () {
        isLoading().remove();
    });
}

/** Ошибки в модальном окне **/
function errorModal(messages) {
    $('#messageModal .modal-body').empty();
    $('#messageModal').removeClass('success-modal').addClass('error-modal');
    if ($.isArray(messages)) {
        $(messages).each(function (index, value) {
            $('#messageModal .modal-body').append('<p class="message"><span class="glyphicon glyphicon-exclamation-sign"></span> ' + value + '</p>');
        });
    } else {
        $('#messageModal .modal-body').append('<p class="message"><span class="glyphicon glyphicon-exclamation-sign"></span> ' + messages + '</p>');
    }
    $('#messageModal').modal('show');
}

/** Успешное выполнение действий в модальном окне **/
function successModal(messages) {

    var stringConstructor = "test".constructor;
    var arrayConstructor = [].constructor;
    var objectConstructor = {}.constructor;

    $('#messageModal .modal-body').empty();
    $('#messageModal').removeClass('error-modal').addClass('success-modal');

    if (messages === null) {
        return false;
    } else if (messages === undefined) {
        return false;
    } else if (messages.constructor === stringConstructor) {
        $('#messageModal .modal-body').append('<p class="message"><span class="glyphicon glyphicon-exclamation-sign"></span> ' + messages + '</p>');
    } else if ($.isNumeric(messages)) {
        $('#messageModal .modal-body').append('<p class="message"><span class="glyphicon glyphicon-exclamation-sign"></span> ' + messages + '</p>');
    } else if (messages.constructor === arrayConstructor) {
        $(messages).each(function (index, value) {
            $('#messageModal .modal-body').append('<p class="message"><span class="glyphicon glyphicon-exclamation-sign"></span> ' + value + '</p>');
        });
    } else if (messages.constructor === objectConstructor) {
        $.each(messages, function (index, value) {
            $('#messageModal .modal-body').append('<p class="message"><span class="glyphicon glyphicon-exclamation-sign"></span> ' + value + '</p>');
        });
    } else {
        return false;
    }

    $('#messageModal').modal('show');
    return true;

}

/**
 * Показать сообщение под хедером
 * types = 'warning', 'success', 'danger', 'info'
 **/
function flashHeaderMessage(message, type) {

    if (type === undefined) {
        type = 'alert-warning';
    } else {
        type = 'alert-' + type;
    }

    var errorDiv = document.createElement('div');
    errorDiv.innerHTML = message + '<div class="close-button" title="Скрыть сообщение"><span class="glyphicon glyphicon-remove"></span></div>';
    $(errorDiv).addClass('header-message').addClass('alert').addClass(type).appendTo('#header');
    $(errorDiv).find('.close-button').on('click', function () {
        $(this).parent().fadeOut();
    });

    setTimeout(function () {
        $(errorDiv).animate({opacity: 0.1}, 800, function () {
            $(this).remove();
        });
    }, 4000);

}

/** Проверка является ли переменная функцией **/
function isFunction(functionToCheck) {
    if (typeof window[functionToCheck] == 'function') {
        return true;
    } else {
        return false;
    }
}

/**
 * Пустая ли строка
 **/
function empty(str) {
    if (typeof str == 'undefined'
        || !str
        || str.length === 0
        || str === ""
        || !/[^\s]/.test(str)
        || /^\s*$/.test(str)) {
        return true;
    } else {
        return false;
    }
}

/** Дефолтный обработчик отправки формы **/
function submitForm(callback, formId, callbackAdditionalData) {
    if (empty(formId)) {
        var form = 'form';
    } else {
        var form = '#' + formId;
    }

    blockScreen();
    var formData = new FormData($(form)[0]);
    $.ajaxPost($(form).attr('action'), formData, function (data) {
        try {
            if (data.hasOwnProperty('errors')) {
                ajaxFormErrorsHandler($('form'), data.errors);
            }

            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                unBlockScreen();
            }

            if (data.hasOwnProperty('messages')) {
                messagesHandler(data.messages);
            }
        } catch (e) {
            unBlockScreen();
            errorModal(["Произошла ошибка." + e]);
        }

        if (!empty(callback) && isFunction(callback)) {
            window[callback](data, callbackAdditionalData);
        } else {
            //console.log('Функция ' + callback + ' не найдена.');
        }

    });

    return false;
}

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

/**
 * sb@
 * Кастомный обработчик ошибок форм по Id формы
 **/
function customFormErrosHandler(form, errors) {
    var form_id = $(form).attr("id");
    switch (form_id) {
        case "authorizationForm" :
            if (errors[0] === undefined) return false;
            var msg_block = "<span class='help-block validate-remove' style='color: red; font-size: 12px'>"+errors[0]+"</span>";
            $('input[name="login"]').css({"border": "2px solid red"});
            $('input[name="password"]').css({"border": "2px solid red"});
            $('span.validate-remove').remove();
            $('input[name="password"]').parent().append($(msg_block));
            return true;
            break;
    }
}


/**
 * Универсальный обработчик ошибок форм
 **/
function ajaxFormErrorsHandler(form, errors, fieldNameModificator, isHoldErrors) {

    // sb@
    var handled = customFormErrosHandler(form, errors)
    if (handled) return false;
    // sb@

    if (empty(isHoldErrors)) {
        form.find('.validate-remove').remove();
        form.find('.has-error').removeClass('has-error');
    }

    var stringConstructor = "test".constructor;
    var arrayConstructor = [].constructor;
    var objectConstructor = {}.constructor;

    var handledErrorsCounter = 0;
    if (errors === null) {
        return false;
    } else if (errors === undefined) {
        return false;
    } else if (errors.constructor === stringConstructor) {
        errorModal([errors]);
    } else if ($.isNumeric(errors)) {
        errorModal([errors]);
    } else if (errors.constructor === arrayConstructor) {
        $(errors).each(function (index, value) {
            if (!empty(fieldNameModificator)) {
                //console.log(index + ' (' + value + ')');
                var fieldName = '[name="' + fieldNameModificator + '[' + index + ']"]';
            } else {
                var fieldName = '[name="' + index + '"]';
            }

            if (form.find(fieldName).length) {
                form.find(fieldName).parent().parent().addClass('has-error');
                form.find(fieldName).parent().append('<span class="help-block validate-remove">' + value + '</span>');
                if (handledErrorsCounter == 0) {
                    $("html, body").animate({scrollTop: $('form').find(fieldName).offset().top - 150});
                }
                handledErrorsCounter = handledErrorsCounter + 1;
                delete errors[index];
            } else if (form.find('.form-error-helper-' + index).length) {
                form.find('.form-error-helper-' + index).parent().parent().addClass('has-error');
                form.find('.form-error-helper-' + index).parent().append('<span class="help-block validate-remove">' + value + '</span>');
                if (handledErrorsCounter == 0) {
                    $("html, body").animate({scrollTop: $('form').find('.form-error-helper-' + index).offset().top - 150});
                }
                handledErrorsCounter = handledErrorsCounter + 1;
                delete errors[index];
            }
        });
        //для необработаных ошибок
        if (errors.length > 0) {
            errorModal(errors);
        }
    } else if (errors.constructor === objectConstructor) {

        if (!empty(errors.code)) {
            if (errors.code == 400) {
                errorModal(["Действие отменено. Данные сессии устарели. В целях безопасности страница будет автоматически перезагружена."]);
                setTimeout(function () {
                    location.reload();
                }, 4000);
                return false;
            }
        }

        $.each(errors, function (index, value) {
            //console.log(index + ' (' + value + ')');

            if (!empty(fieldNameModificator)) {
                var fieldName = '[name="' + fieldNameModificator + '[' + index + ']"]';
            } else {
                var fieldName = '[name="' + index + '"]';
            }

            if (form.find(fieldName).length) {
                form.find(fieldName).parent().parent().addClass('has-error');
                form.find(fieldName).parent().append('<span class="help-block validate-remove">' + value + '</span>');
                if (handledErrorsCounter == 0) {
                    $("html, body").animate({scrollTop: $('form').find(fieldName).offset().top - 150});
                }
                handledErrorsCounter = handledErrorsCounter + 1;
                delete errors[index];
            } else if (form.find('.form-error-helper-' + index).length) {
                form.find('.form-error-helper-' + index).parent().parent().addClass('has-error');
                form.find('.form-error-helper-' + index).parent().append('<span class="help-block validate-remove">' + value + '</span>');
                if (handledErrorsCounter == 0) {
                    $("html, body").animate({scrollTop: $('form').find('.form-error-helper-' + index).offset().top - 150});
                }
                handledErrorsCounter = handledErrorsCounter + 1;
                delete errors[index];
            }
        });
        //для необработаных ошибок
        var errArray = $.map(errors, function (value, index) {
            return [value];
        });
        if (errArray.length > 0) {
            errorModal(errArray);
        }
    } else {
        errorModal(["Произошла ошибка."]);
        return false;
    }

    if (handledErrorsCounter > 0) {
        flashHeaderMessage('Есть ошибки в форме!', 'warning');
    }

    return true;
}

/**
 * Универсальный обработчик сообщений
 * **/
function messagesHandler(messages) {
    var stringConstructor = "test".constructor;
    var arrayConstructor = [].constructor;
    var objectConstructor = {}.constructor;

    if (messages === null) {
        return false;
    } else if (messages === undefined) {
        return false;
    } else if (messages.constructor === stringConstructor) {
        flashHeaderMessage([messages]);
    } else if ($.isNumeric(messages)) {
        flashHeaderMessage([messages]);
    } else if (messages.constructor === arrayConstructor) {
        $(messages).each(function (index, value) {
            //console.log('array: ' + index + ' (' + value + ')');
            if (index == 'success') {
                flashHeaderMessage(value, 'success');
            } else {
                flashHeaderMessage(value);
            }
        });
    } else if (messages.constructor === objectConstructor) {
        $.each(messages, function (index, value) {
            //console.log(index + ' (' + value + ')');
            if (index == 'success') {
                flashHeaderMessage(value, 'success');
            } else {
                flashHeaderMessage(value);
            }
        });
    } else {
        return false;
    }

    return true;
}

$('.search-container .search').click(function () {
    $(this).parent().find('.search-fields').fadeToggle();
});


/**
 * Форматирование телефонного номера
 **/
function formatInputPhones() {
    $('input.format-phone').each(function () {
        var im = new Inputmask("+7 (9{2,3}) 9{7}", {
            removeMaskOnSubmit: true,
            "onincomplete": function () {
                //$(this).parent().parent().addClass('has-error');
            },
            "oncomplete": function () {
                // $(this).parent().parent().removeClass('has-error');
            }
        });
        im.mask($(this));
    });
}

/**
 * Форматирование ИНН
 **/
function formatINN() {
    $('input.format-inn').each(function () {
        var im = new Inputmask("9{10,12}", {
            removeMaskOnSubmit: true, "onincomplete": function () {
                var pattern = new RegExp("[0-9]*_[0-9]+");
                if (pattern.test($(this).val())) {
                    $(this).val('');
                }
            }, "oncomplete": function () {
            }
        });
        im.mask($(this));
    });
}

/**
 * Форматирование КПП
 **/
function formatKPP() {
    $('input.format-kpp').each(function () {
        var im = new Inputmask("9{9}", {
            removeMaskOnSubmit: true, "onincomplete": function () {
                var pattern = new RegExp("[0-9]*_[0-9]+");
                if (pattern.test($(this).val())) {
                    $(this).val('');
                }
            }, "oncomplete": function () {
            }
        });
        im.mask($(this));
    });
}

/**
 * Форматирование ОГРН/ОГРНИП
 **/
function formatOGRNOGRNIP() {
    $('input.format-ogrn').each(function () {
        var im = new Inputmask("9{13,15}", {
            removeMaskOnSubmit: true, "onincomplete": function () {
                var pattern = new RegExp("[0-9]*_[0-9]+");
                if (pattern.test($(this).val())) {
                    $(this).val('');
                }
            }, "oncomplete": function () {
            }
        });
        im.mask($(this));
    });
}

/**
 * Форматирование БИК
 **/
function formatBIK() {
    $('input.format-bik').each(function () {
        var im = new Inputmask("9{9}", {
            removeMaskOnSubmit: true, "onincomplete": function () {
                var pattern = new RegExp("[0-9]*_[0-9]+");
                if (pattern.test($(this).val())) {
                    $(this).val('');
                }
            }, "oncomplete": function () {
            }
        });
        im.mask($(this));
    });
}

/**
 * Форматирование Р/С (расчетный счет)
 **/
function formatRS() {
    $('input.format-rs').each(function () {
        var im = new Inputmask("9{20}", {
            removeMaskOnSubmit: true, "onincomplete": function () {
                var pattern = new RegExp("[0-9]*_[0-9]+");
                if (pattern.test($(this).val())) {
                    $(this).val('');
                }
            }, "oncomplete": function () {
            }
        });
        im.mask($(this));
    });
}

/**
 * Форматирование К/С (корреспондентский счет)
 **/
function formatKS() {
    $('input.format-ks').each(function () {
        var im = new Inputmask("9{20}", {
            removeMaskOnSubmit: true, "onincomplete": function () {
                var pattern = new RegExp("[0-9]*_[0-9]+");
                if (pattern.test($(this).val())) {
                    $(this).val('');
                }
            }, "oncomplete": function () {
            }
        });
        im.mask($(this));
    });
}

/**
 * Форматирование СНИЛС
 **/
function formatSNILS() {
    $('input.format-snils').each(function () {
        var im = new Inputmask("9{11}", {
            removeMaskOnSubmit: true, "onincomplete": function () {
                var pattern = new RegExp("[0-9]*_[0-9]+");
                if (pattern.test($(this).val())) {
                    $(this).val('');
                }
            }, "oncomplete": function () {
            }
        });
        im.mask($(this));
    });
}

/**
 * Форматирование Серия паспорта
 **/
function formatPassportSeria() {
    $('input.format-passport-seria').each(function () {
        var im = new Inputmask("*{4}");
        im.mask($(this));
    });
}

/**
 * Форматирование Номер паспорта
 **/
function formatPassportNumber() {
    $('input.format-passport-number').each(function () {
        var im = new Inputmask("*{6}");
        im.mask($(this));
    });
}

/**
 * Форматирование поля с датой
 **/
function formatDate(param = {}) {
    var configuration = {
        allowInputToggle: true,
        timepicker: false,
        format: 'd.m.Y',
        yearStart: 1900,
        scrollInput: false
    };

    var configurationToday = {
        allowInputToggle: true,
        timepicker: false,
        format: 'd.m.Y',
        yearStart: 1900,
        scrollInput: false,
        minDate: 0,
    };

    if (param.maxDate) {
        configuration.maxDate = param.maxDate
    }

    $.datetimepicker.setLocale('ru');
    $('input.format-date').datetimepicker(configuration);
    $('input.format-date-today').datetimepicker(configurationToday);

    $('input.format-date input.format-date-today').keyup(function (event) {
        if (event.keyCode != 37 && event.keyCode != 39 && event.keyCode != 38 && event.keyCode != 40) {
            var pattern = new RegExp("[0-9.]{10}");
            if (pattern.test($(this).val())) {
                $(this).datetimepicker('hide');
                $(this).datetimepicker('show');
            }
        }
    });


    $('input.format-date').each(function () {
        var im = new Inputmask("99.99.9999", {
            "oncomplete": function () {
            }
        });
        im.mask($(this));
    });

    $('input.format-date-today').each(function () {
        var im = new Inputmask("99.99.9999", {
            "oncomplete": function () {
            }
        });
        im.mask($(this));
    });





    $('.glyphicon').click(function (ev) {
        $(this).parent('div').children('input').trigger('focus');
    });

}


/**
 * Search sidebar
 * **/
$(".search-sidebar .search").click(function () {
    $(".search-sidebar").addClass("search-sidebar-in");
});
$(".search-sidebar .close").click(function () {
    $(".search-sidebar").removeClass("search-sidebar-in");
});

$(".search-sidebar .cat a ").click(function () {
    $(this).next('ul').slideToggle();
    $(this).parent('li').toggleClass('active');
    return false;
});

$(document).ready(function () {
    $(".search-form").find("input[type='text'], select").each(function () {
        if ($(this).val() != '') {
            $(this).parent().parent().parent().addClass('active');
        }
    });
    $(".btnExcel").click(function () {
        if ($(this).data('count') > 5000) {
            errorModal(["Слишком большая выборка, уменьшите число выгружаемых документов критериями поиска"]);
        } else {
            $(".search-form").append('<input type="hidden" name="toExcel" value="1">');
            $(".search-form").submit();
            $(".search-form").find("input[name=toExcel]").remove();
        }
        return false;
    });

    $(document).on('mousedown', 'li', function () {
        $('input').blur();
    })



});



function join(object, delimeter) {
    var joined = [];

    for (var key in object) {
        var val = object[key];
        joined.push(val);
    }

    return joined.join(delimeter);
}


function trim ( str, charlist ) {
    charlist = !charlist ? ' \s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
    var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');
    return str.replace(re, '');
}


function to_clipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
}

function switch_letters(str){

    var s = [
        "й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
        "ф","ы","в","а","п","р","о","л","д","ж","э",
        "я","ч","с","м","и","т","ь","б","ю"
    ];

    var r = [
        "q","w","e","r","t","y","u","i","o","p","\\[","\\]",
        "a","s","d","f","g","h","j","k","l",";","'",
        "z","x","c","v","b","n","m",",","\\."
    ];


    var list_from = r.indexOf(str[0]) !== -1 ? r : s;
    var list_to = r.indexOf(str[0]) !== -1 ? s : r;

    for (var i = 0; i < r.length; i++) {
        var reg = new RegExp(list_from[i], 'mig');
        str = str.replace(reg, function (a) {
            return a === a.toLowerCase() ? list_to[i] : list_to[i].toUpperCase();
        });
    }


    return str;
}