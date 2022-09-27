@extends('layouts.app')



@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">Аналитика - Итоговая</h1>
    </div>
    <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="filter-group" id="filters">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <h3>Оборот</h3>
            <canvas id="turn" width="100%" height="45px"></canvas>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <h3>Итог</h3>
            <div id="result_table"></div>
        </div>
    </div>




@endsection



@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
    <script>
        var Charts = {};


        $(function(){

            Chart.pluginService.register({
                beforeDraw: function (chart) {
                    if (chart.config.options.elements.center) {
                        var ctx = chart.chart.ctx;
                        var centerConfig = chart.config.options.elements.center;
                        var fontStyle = centerConfig.fontStyle || 'Arial';
                        var txt = centerConfig.text;
                        var color = centerConfig.color || '#000';
                        var sidePadding = centerConfig.sidePadding || 20;
                        var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2)
                        ctx.font = "30px " + fontStyle;
                        var stringWidth = ctx.measureText(txt).width;
                        var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;
                        var widthRatio = elementWidth / stringWidth;
                        var newFontSize = Math.floor(30 * widthRatio);
                        var elementHeight = (chart.innerRadius * 2);
                        var fontSizeToUse = Math.min(newFontSize, elementHeight);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
                        var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
                        ctx.font = fontSizeToUse + "px " + fontStyle;
                        ctx.fillStyle = color;
                        ctx.fillText(txt, centerX, centerY);
                    }
                }
            });


            loadItems();


        });


        function getData(){
            return {
                period:$('[name="period"]').val(),
                year:$('[name="year"]').val(),
                month:$('[name="month"]').val(),
                from:$('[name="from"]').val(),
                to:$('[name="to"]').val(),
            }
        }


        function loadItems(){
            loaderShow();
            $.post('/analitics/total/get_filters', getData(), function(res){
                $('#filters').html(res);
                $('.select2-ws').select2("destroy").select2({
                    width: '100%',
                    dropdownCssClass: "bigdrop",
                    dropdownAutoWidth: true,
                    minimumResultsForSearch: -1
                });
                loadCharts();
            });

        }

        function loadCharts(){



            $.each(Charts, function(k,v){ v.destroy(); });
            $('#result_table').html('');
            $.post('/analitics/total/get_charts', getData(), function(res){


                $.each(res.charts, function(k,v){
                    Charts[k] = new Chart($("#" + k), {
                        type: v.type,
                        data: v.data,
                        options: v.options
                    });
                });


                var result_table = $('<table class="table table-bordered contracts-total"><tbody></tbody></table>');
                $.each(res.result, function(k,v){
                    var row = $('<tr class="removable"><th>'+v.title+'</th><td>'+format_price(v.total)+'</td><td style="background-color: '+v.color+'"></td></tr>');
                    result_table.find('tbody').append(row)
                });
                $('#result_table').append(result_table);

            }).always(function(){
                loaderHide();
            });
        }

    </script>
@endsection