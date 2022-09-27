<style>
    .row-color {
        padding: 5px;
        border-top: 1px solid #e4e4e4;
        cursor: pointer;
    }
    .row-color:hover {
        background-color: #eeeeed;
    }
</style>

<div style="padding: 10px; display: flex; justify-content: space-between">
    <h4>Цвета</h4>
    <div>
        <button type="submit" class="btn btn-primary btn-save new-color">Добавить</button>
    </div>
</div>

<div class="table">

    @if(sizeof($colors))

        @foreach($colors as $key => $color)

            <div class="row-color" data-id="{{$color->id}}">{{$color->title}}</div>

        @endforeach

    @else

        <h4>Нет цветов</h4>

    @endif

</div>

<script>
    document.body.querySelectorAll('.row-color').forEach((color) => {
        color.addEventListener('click', (event) => {
            let color = event.target;
            let colorId = color.dataset.id;
            let url = "{{route('color-edit-page')}}/" + colorId;
            openFancyBoxFrame(url);
        });
    });

    document.body.querySelector('.new-color').addEventListener('click', () => {
        let url = "{{route('color-create-page')}}";
        openFancyBoxFrame(url);
    });
</script>