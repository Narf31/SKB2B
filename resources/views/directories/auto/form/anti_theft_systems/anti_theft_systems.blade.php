<style>
    .row-anti-theft-system {
        padding: 5px;
        border-top: 1px solid #e4e4e4;
        cursor: pointer;
    }
    .row-anti-theft-system:hover {
        background-color: #eeeeed;
    }
</style>

<div style="padding: 10px; display: flex; justify-content: space-between">
    <h2>Названия</h2>
    <div>
        <button type="submit" class="btn btn-primary btn-save new-anti-theft-system">Добавить</button>
    </div>
</div>

<div class="table">
    @if(sizeof($antiTheftSystems))

        @foreach($antiTheftSystems as $key => $antiTheftSystem)

            <div class="row-anti-theft-system" data-id="{{$antiTheftSystem->id}}">{{$antiTheftSystem->title}}</div>

        @endforeach

    @else

        <h4>Нет противоугонных систем</h4>

    @endif
</div>



<script>
    document.body.querySelectorAll('.row-anti-theft-system').forEach((antiTheftSystem) => {
        antiTheftSystem.addEventListener('click', (event) => {
            let antiTheftSystem = event.target;
            let antiTheftSystemId = antiTheftSystem.dataset.id;
            let url = "{{route('anti-theft-system-edit-page')}}/" + antiTheftSystemId;
            openFancyBoxFrame(url);
        });
    });

    document.body.querySelector('.new-anti-theft-system').addEventListener('click', () => {
        let url = "{{route('anti-theft-system-create-page')}}";
        openFancyBoxFrame(url);
    });
</script>