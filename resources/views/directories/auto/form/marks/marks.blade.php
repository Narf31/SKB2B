<style>
    .row-mark {
        border-top: 1px solid #e4e4e4;
        padding: 5px;
        cursor: pointer;
    }
    .row-mark:hover {
        background-color: #eeeeed;
    }
</style>

<div style="padding: 10px; display: flex; justify-content: space-between">
    <h4>Марки</h4>
    @if(sizeof($categories))
        <div>
            <button type="submit" class="btn btn-primary btn-save new-mark">Добавить</button>
        </div>
    @endif

</div>

<div style="display: flex; padding-bottom: 10px;">

    @if(sizeof($categories))
        <div>
            <label class="control-label">Категория <span class="required">*</span></label>
            {{Form::select("category", $categories->pluck('title', 'isn'), $categories->first()->isn, ['class' => 'form-control select2-all category-selector'])}}
        </div>
    @else
        <div> Сначала нужно добавить категорию</div>
    @endif



</div>

<div class="table table-marks">

</div>

<script>

    @if(sizeof($categories))

        document.body.querySelector('.new-mark').addEventListener('click', () => {
            let url = "{{route('mark-create-page')}}";
            openFancyBoxFrame(url);
        });

        getMarks(document.body.querySelector('.category-selector').value);

        document.body.querySelector('.category-selector').addEventListener('change', (event) => {
            let categoryIsn = event.target.value;
            getMarks(categoryIsn);
        });

        function getMarks(categoryIsn) {

            loaderShow();

            $.get("{{route('category-marks')}}", {categoryIsn: categoryIsn}, function (response) {
                let marks = response;

                let marksHtml = '';
                marks.forEach((mark) => {
                    marksHtml += '<div class="row-mark" data-mark-id="' + mark.id + '" data-mark-isn="' + mark.isn + '">' + mark.title + '</div>';
                });
                if (marksHtml === '') {
                    $(".table-marks").html('Нет марок в категории!');
                } else {
                    $(".table-marks").html(marksHtml);
                    listenClickMarks();
                }

            }).always(function() {
                loaderHide();
            });
        }

        function listenClickMarks() {
            document.body.querySelectorAll('.row-mark').forEach((mark) => {
                mark.addEventListener('click', (event) => {
                    let mark = event.target;
                    let markId = mark.dataset.markId;
                    let url = "{{route('mark-edit-page')}}/" + markId;
                    openFancyBoxFrame(url);
                });
            });
        }

    @endif

</script>