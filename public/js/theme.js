$(function () {
    $('.dropdown-toggle').click(function () {
        if ($(this).closest('li.dropdown').length) {
            $(this).closest('li.dropdown').toggleClass('open');
        }
    });

    $('.dropdown-menu li').click(function () {
        $(this).closest('ul').find('li').removeClass('active');
        $(this).addClass('active');
        $('.dropdown-toggle').closest('div').removeClass('open');
    });
});

function loaderShow() {
    $('.loader').removeClass('hidden');
}
function loaderHide() {
    $('.loader').addClass('hidden');
}