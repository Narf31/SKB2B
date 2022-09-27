<style>

    .user-settings-block{
        padding: 15px;
        padding-right: 0px;
        margin:0 auto;
        bottom:0;
        position: {{auth()->user()->settings['menu_section'] ? 'relative' : 'absolute'}};
    }

</style>



<div class="user-settings-block">
    <form id="user-settings">
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <label class="control-label">
                <input type="checkbox" name="menu" {{auth()->user()->settings['menu'] ? 'checked' : ''}}>
                Меню всегда открыто
            </label>
        </div>
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <label class="control-label">
                <input type="checkbox"  name="menu_section" {{auth()->user()->settings['menu_section'] ? 'checked' : ''}}>
                Разделы меню открыты
            </label>
        </div>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a href="javascript:void(0);" onclick="updateUserSettings();" class="btn-sm btn-primary pull-right">Применить</a>
        </div>
    </form>
</div>

<script>

    function updateUserSettings() {
        var form = $('#user-settings');
        loaderShow();
        $.post("{{url('menu-settings')}}", form.serialize(), function (response) {
            loaderHide();
        }).done(function () {
            reload();
            //loaderShow();
        }).fail(function () {
            loaderHide();
        }).always(function () {
            loaderHide();
        });
    }

</script>