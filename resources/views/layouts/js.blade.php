{{--<script src="/assets/new/lib/jquery/js/jquery.js"></script>--}}
<script src="/plugins/jquery/jquery.min.js"></script>
<script src="/plugins/jquery-ui/jquery-ui.min.js"></script>


<script src="/assets/new/lib/bootstrap/js/bootstrap.js"></script>
<script src="/assets/new/lib/inputmask/js/inputmask.js"></script>
<script src="/assets/new/js/isLoading.js"></script>
<script src="/assets/new/lib/nicescroll/js/jquery.nicescroll.js"></script>
<script src="/assets/js/jquery.dataTables.js"></script>
<script src="/assets/js/dataTables.bootstrap.js"></script>

<script src="/plugins/fancybox/jquery.fancybox.js"></script>
<script src="/js/jquery.easyui.min.js"></script>
<script src="/plugins/jquery-mask/jquery.mask.min.js"></script>
<script src="/plugins/select2/select2.min.js"></script>
<script src="/plugins/select2/select2_locale_ru.js"></script>
<script src="/assets/js/jquery.suggestions.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>


<script src="/plugins/datepicker/jquery.datepicker.js"></script>
<script src="/plugins/moment/moment-with-locales.min.js"></script>
<script src="/plugins/datetimepicker/bootstrap-material-datetimepicker.js"></script>
<script src="/plugins/datepicker/datepicker-ru.js"></script>
<script src="/js/jquery-ui.multidatespicker.js"></script>
<script src="/assets/js/toastr.js"></script>

<script src="/js/theme.js?p={{time()}}"></script>
<script src="/js/custom.js?p={{time()}}"></script>
<script src="/assets/new/js/main.js?p={{time()}}"></script>


<script src="/js/bso/search.js?p={{time()}}"></script>

<script src="/js/intro/intro.js"></script>
<script src="/js/sweetalert2@9"></script>


<script>

    $(function () {
        $(".main-menu-expand").click();
    });

    function customConfirm() {

        return confirm('{{trans('form.are_you_sure')}}');
    }

    function removeFile(fileName) {
        if (!customConfirm()) {
            return false;
        }
        var filesUrl = '{{ url(\App\Models\File::URL) }}';
        var fileUrl = filesUrl + '/' + fileName;
        $.post(fileUrl, {
            _method: 'DELETE'
        }, function () {
            reload();

        });
    }

    function removeFileAndReloadTab(fileName) {
        if (!customConfirm()) {
            return false;
        }
        var filesUrl = '{{ url(\App\Models\File::URL) }}';
        var fileUrl = filesUrl + '/' + fileName;
        $.post(fileUrl, {
            _method: 'DELETE'
        }, function () {
            reloadTab();

        });
    }

    function newAlert2(title, text) {
        Swal.fire({
            title: title,
            text: text,
        });
    }


</script>

@if(session()->has('callback'))
    <script>
        $(function(){
            eval('{!! session('callback') !!}');
        });
    </script>
@endif

