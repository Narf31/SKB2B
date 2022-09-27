// Main menu expand
//
$(".main-menu-expand").click(function() {
    $(".main-menu").toggleClass("main-menu-extended");
    $(".main-menu-list").toggleClass("main-menu-list-open");
    $(".main-menu-item-title").toggleClass('main-menu-item-title-open');
    $(".main-menu-expand-title").toggleClass('main-menu-expand-title-open');
    $(".main-menu-expand-ico").toggleClass('main-menu-expand-ico-collapse');
});

// Tables
//
$(document).ready(function() {

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

// Clickable table row
//
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        if ($(this).attr('data-href')) {
            window.location = $(this).attr('data-href');
        }
    });
    $(".clickable-row-blank").click(function() {
        if ($(this).attr('data-href')) {
            window.open($(this).attr('data-href'), '_blank');
        }
    });
});


// Dropdowns
dropdownAll = $("#messages-open, .drowdown-panel, #account-info-dropdown, #notification-open");

function shutterOn() {
    $(".shutter").attr('data-status', 'on');
    $(".shutter").fadeIn('500');
};

function shutterOff() {
    $(".shutter").attr('data-status', 'off');
    $(".shutter").delay('100').fadeOut('500');
};

$(".shutter").click(function() {
    dropdownAll.fadeOut('500').attr('data-status', 'close');
    shutterOff();
});

$(".search input").on("focus", function() {
    dropdownAll.fadeOut('500').attr('data-status', 'close');
    shutterOff();
});

$("#messages").click(function() {

    dropdownAll.fadeOut('500');

    if ($("#messages-open").attr('data-status') === 'close') {
        if ($(".shutter").attr('data-status') === 'off') shutterOn();
        $("#messages-open").delay('300').fadeIn('500').attr('data-status', 'open');
    } else {
        shutterOff();
        dropdownAll.fadeOut('500').attr('data-status', 'close');
    }

});

$("#notification").click(function() {

    dropdownAll.fadeOut('500');

    if ($("#notification-open").attr('data-status') === 'close') {
        if ($(".shutter").attr('data-status') === 'off') shutterOn();
        $("#notification-open").delay('300').fadeIn('500').attr('data-status', 'open');
    } else {
        shutterOff();
        dropdownAll.fadeOut('500').attr('data-status', 'close');
    }

});

$("#account-info-dropdown-trigger").click(function() {

    dropdownAll.fadeOut('500');

    if ($("#account-info-dropdown").attr('data-status') === 'close') {
        if ($(".shutter").attr('data-status') === 'off') shutterOn();
        $("#account-info-dropdown").delay('300').fadeIn('500').attr('data-status', 'open');
    } else {
        shutterOff();
        dropdownAll.fadeOut('500').attr('data-status', 'close');
    }
});

$(".main-menu-item").click(function() {


    dropdownAll.fadeOut('500');

    if ($("#" + this.id + "-dropdown").attr('data-status') === 'close') {
        if ($(".shutter").attr('data-status') === 'off') shutterOn();
        $("#" + this.id + "-dropdown").delay('300').fadeIn('500').attr('data-status', 'open');
    } else {
        shutterOff();
        dropdownAll.fadeOut('500').attr('data-status', 'close');
    }
});
// Dropdowns end


// Filter
//
$(".parameters-toggle").click(function() {
    $("#parametersAll").slideToggle('400');
    $(this).toggleClass("parameters-toggle-collapse");
});

// Sorting
//
$(".sorting-toggle").click(function() {
    $(".sorting-toggle").removeClass('sorting-toggle-up sorting-toggle-down');
    if ($(this).attr('data-status') === 'off') {
        $(this).attr('data-status', 'on').addClass('sorting-toggle-up');
    } else {
        $(this).attr('data-status', 'off').addClass('sorting-toggle-down');
    }
});
