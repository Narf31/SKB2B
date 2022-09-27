<div class="account-info">
    <a id="account-info-dropdown-trigger">
        @if(auth()->user()->smallImage()->exists())
            <img src="{{ auth()->user()->smallImage->url }}" alt="">
        @else
            <img src="{{ url("/assets/img/user_photo.jpg") }}" alt="">
        @endif
    </a>
    <div class="account-info-dropdown" id="account-info-dropdown" data-status="close">

        <div class="account-picture">

            <a data-toggle="modal" data-target="#account-picture-modal" href="{{ url("/users/users/" . auth()->id() . "/edit") }}">

                @if(auth()->user()->smallImage()->exists())
                    <img src="{{ auth()->user()->smallImage->url }}" alt="">
                @else
                    <img src="{{ url("/assets/img/user_photo.jpg") }}" alt="">
                @endif

                <div class="edit-photo edit-account-photo">Изменить</div>
            </a>

            <div class="dropzone-container hidden">
                {{ Form::open(['files' => true, 'method' => 'post']) }}
                {{ Form::close() }}
            </div>

        </div>
        <div class="account-body">
            <span class="account-title">{{ auth()->user()->name }}</span>
            <span class="account-company">{{ auth()->user()->organization()->exists() ? auth()->user()->organization->title : ''}}</span>
            <span class="account-email">{{ auth()->user()->email }}</span>
            <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="account-logout">Выход</a>
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</div>


@section('js')
    <script>
        $(function () {

            $('.edit-account-photo').click(function () {
                $('.dropzone-container').click();
            });

            $(".dropzone-container").dropzone({
                url: "{{ url("/account/photo") }}",
                paramName: "image",
                maxFilesize: 1000,
                sending: function (file, xhr, formData) {
                    formData.append("_token", $('meta[name="csrf-token"]').attr('content')); // Laravel expect the token post value to be named _token by default
                },
                success: function (file) {
                    this.removeFile(file);
                    reload();
                }
            });
        });


    </script>
@append