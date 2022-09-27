<style>
    .row-category {
        padding: 5px;
        border-top: 1px solid #e4e4e4;
        cursor: pointer;
    }
    .row-category:hover {
        background-color: #eeeeed;
    }
</style>

<div style="padding: 10px; display: flex; justify-content: space-between">
    <h4>Категории</h4>
    <div>
        <button type="submit" class="btn btn-primary btn-save new-category">Добавить</button>
    </div>
</div>

<div class="table">

    @if(sizeof($categories))

        @foreach($categories as $key => $category)

            <div class="row-category" data-id="{{$category->id}}">{{$category->title}}</div>

        @endforeach

    @else

        <h4>Нет категорий</h4>

    @endif
</div>

<script>
    document.body.querySelectorAll('.row-category').forEach((category) => {
        category.addEventListener('click', (event) => {
            let category = event.target;
            let categoryId = category.dataset.id;
            let url = "{{route('category-edit-page')}}/" + categoryId;
            openFancyBoxFrame(url);
        });
    });

    document.body.querySelector('.new-category').addEventListener('click', () => {
        let url = "{{route('category-create-page')}}";
        openFancyBoxFrame(url);
    });
</script>