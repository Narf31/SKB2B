<style>
    .row-model {
        border-top: 1px solid #e4e4e4;
        padding: 5px;
        cursor: pointer;
    }
    .row-model:hover {
        background-color: #eeeeed;
    }
</style>

<div style="padding: 10px; display: flex; justify-content: space-between">
    <h4>Марки</h4>
    @if(sizeof($categories))
    <div>
        <button type="submit" class="btn btn-primary btn-save new-model">Добавить</button>
    </div>
    @endif
</div>

<div style="display: flex; padding-bottom: 10px; flex-wrap: wrap;">

    @if(sizeof($categories))
        <div>
            <label class="control-label">Категория <span class="required">*</span></label>
            {{Form::select("category", $categories->pluck('title', 'isn'), $categories->first()->isn, ['class' => 'form-control select2-all category-selector'])}}
        </div>

        @if(sizeof($marks))
            <div style="margin-left: 10px;">
                <label class="control-label">Марка <span class="required">*</span></label>
                <select class="marks-selector form-control select2-all" name="marks">

                </select>
            </div>
        @else
            <div style="width: 100%; padding-top: 10px;">Сначала нужно добавить марку</div>
        @endif

    @else
        <div>Сначала нужно добавить категорию</div>
    @endif

</div>

<div class="table table-models">

</div>

<script>

    @if(sizeof($categories) && sizeof($marks))

        document.body.querySelector('.new-model').addEventListener('click', () => {
            let url = "{{route('model-create-page')}}";
            openFancyBoxFrame(url);
        });

        getMarks(document.body.querySelector('.category-selector').value);

        document.body.querySelector('.category-selector').addEventListener('change', (event) => {
            let categoryIsn = event.target.value;
            getMarks(categoryIsn);
        });

        function getMarks(categoryIsn) {

            loaderShow();

            document.body.querySelector('.new-model').style.display = 'none';

            $.get("{{route('category-marks')}}", {categoryIsn: categoryIsn}, function (response) {

                let marks = response;

                let marksHtml = '';
                let i = 1;
                marks.forEach((mark) => {
                    let selected = i === 1 ? 'selected' : '';
                    marksHtml += '<option value="' + mark.isn + '" ' + selected + '>' + mark.title + '</option>';
                    i++;
                });
                if (marksHtml === '') {
                    marksHtml = '<option value="0">Нет марок в категории!</option>';
                    $(".marks-selector").html(marksHtml);
                } else {
                    document.body.querySelector('.new-model').style.display = '';
                    $(".marks-selector").html(marksHtml);
                    listenChangeMarksSelector();
                    getModels(document.body.querySelector('.marks-selector').value);
                }

            }).always(function() {
                loaderHide();
            });
        }

        function listenChangeMarksSelector() {
            document.body.querySelector('.marks-selector').addEventListener('change', (event) => {
                let markIsn = event.target.value;
                getModels(markIsn);
            });
        }

        function getModels(markIsn) {

            loaderShow();

            $.get("{{route('mark-models')}}", {markIsn: markIsn}, function (response) {
                let models = response;

                let modelsHtml = '';
                models.forEach((model) => {
                    modelsHtml += '<div class="row-model" data-model-id="' + model.id + '" data-model-isn="' + model.isn + '">' + model.title + '</div>';
                });
                if (modelsHtml === '') {
                    $(".table-models").html('Нет моделей в марке!');
                } else {
                    $(".table-models").html(modelsHtml);
                    listenClickModels();
                }

            }).always(function() {
                loaderHide();
            });
        }

        function listenClickModels() {
            document.body.querySelectorAll('.row-model').forEach((model) => {
                model.addEventListener('click', (event) => {
                    let model = event.target;
                    let modelId = model.dataset.modelId;
                    let url = "{{route('model-edit-page')}}/" + modelId;
                    openFancyBoxFrame(url);
                });
            });
        }

    @endif

</script>