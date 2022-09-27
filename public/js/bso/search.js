
//ПОИСК БСО чистого
function activSearchBso(object_id, key, type)
{

    $('#'+object_id+key).suggestions({
        serviceUrl: "/bso/actions/get_bso/",
        type: "PARTY",
        params:{type_bso:type, bso_supplier_id:$('#bso_supplier_id'+key).val(), bso_agent_id:$('#agent_id'+key).val()},
        count: 5,
        minChars: 3,
        formatResult: function (e, t, n, i) {
            var s = this;
            var title = n.value;
            var bso_type = n.data.bso_type;
            var bso_sk = n.data.bso_sk;
            var agent_name = n.data.agent_name;

            var view_res = title;
            //view_res += '<div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">СК</span>' + bso_sk + "</div>";
            //view_res += '<div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">Тип</span>' + bso_type + "</div>";
            view_res += '<div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">Агент</span>' + agent_name + "</div>";

            return view_res;
        },
        onSelect: function (suggestion) {


            selectBso(object_id, key, type, suggestion);

        }
    });
}


//Поиск договора
function activSearchBsoContract(object_id, type)
{

    //type = 0 все договора 1 действующий

    $('#'+object_id).suggestions({
        serviceUrl: "/bso/actions/get_bso_contracts/",
        type: "PARTY",
        params:{type_contract:type},
        count: 5,
        minChars: 3,
        formatResult: function (e, t, n, i) {
            var s = this;
            var title = n.value;
            var bso_type = n.data.bso_type;
            var bso_sk = n.data.bso_sk;
            var insurer = n.data.insurer;

            var view_res = '<div class="pull-left"><span style="color: #000;font-size: 15px;">'+title+'</span> - ' +bso_type+ '</div><br/><br/>';
            view_res += '<div><span class="' + s.classes.subtext_inline + '">Страхователь - ' + insurer + "</span></div>";

            return view_res;
        },
        onSelect: function (suggestion) {


            selectBsoContract(object_id, type, suggestion);

        }
    });
}