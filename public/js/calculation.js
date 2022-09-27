
function formatTime() {
    var configuration = {
        timepicker: true,
        datepicker: false,
        format: 'H:i',
        scrollInput: false
    };
    $.datetimepicker.setLocale('ru');
    $('input.format-time').datetimepicker(configuration).keyup(function (event) {
        if (event.keyCode != 37 && event.keyCode != 39 && event.keyCode != 38 && event.keyCode != 40) {
            var pattern = new RegExp("[0-9:]{5}");
            if (pattern.test($(this).val())) {
                $(this).datetimepicker('hide');
                $(this).datetimepicker('show');
            }
        }
    });
    $('input.format-time').each(function () {
        var im = new Inputmask("99:99", {"oncomplete": function () {}});
        im.mask($(this));
    });
}
function formatTime() {
    var configuration = {
        timepicker: true,
        datepicker: false,
        format: 'H:i',
        scrollInput: false
    };
    $.datetimepicker.setLocale('ru');
    $('input.format-time').datetimepicker(configuration).keyup(function (event) {
        if (event.keyCode != 37 && event.keyCode != 39 && event.keyCode != 38 && event.keyCode != 40) {
            var pattern = new RegExp("[0-9:]{5}");
            if (pattern.test($(this).val())) {
                $(this).datetimepicker('hide');
                $(this).datetimepicker('show');
            }
        }
    });
    $('input.format-time').each(function () {
        var im = new Inputmask("99:99", {"oncomplete": function () {}});
        im.mask($(this));
    });
}


function get_end_dates(start_date) {
    var cur_date_tmp = start_date.split(".");
    var cur_date = new Date(cur_date_tmp[2], cur_date_tmp[1] - 1, cur_date_tmp[0]);
    var new_date = new Date(cur_date.setYear(cur_date.getFullYear() + 1));
    var new_date2 = new Date(new_date.setDate(new_date.getDate() - 1));
    return getFormattedDate(new_date2);
}

function setAllDates(key) {
    sign_date = $("#sign_date_" + key).val();
    $("#begin_date_" + key).val(sign_date);

    return setEndDates(key);
}

function setEndDates(key) {
    begin_date = $("#begin_date_" + key).val();
    $("#end_date_" + key).val(get_end_dates(begin_date));
}


document.addEventListener("DOMContentLoaded", function(event) { 
    formatTime();
    formatDate();
    $("input, select").change(function () {
        $(this).removeClass('form-error');
    });
});


