var TAB_INDEX = 0;
var TAB_SELECT_INDEX = 0;
var TAB_URL = [];
var TAB_SAVE_CONTRACT = 0;

$(function () {


    /*
    $('#tt').tabs({
        plain: true,
        pill: false,
        onBeforeClose: function(title, index){

            TAB_URL.splice(index, 1);

            if(parseInt(index) == 0){
                $('#main_container').html('');
            }

        },
        onSelect: function(title, index){
            return selectTab(index);
        }


    });
    */

    //loadItemsController('/main', 'Стартовый экран');

    //loadItemsController('/contracts/products/192/edit', 'ОСАГО');




});


function addPanel(name){



    $('#tt').tabs('add',{
        title: name,
        content: getContent(),
        closable: true,

    });





}



function selectTab(tab_id){


    if(TAB_URL[tab_id] && TAB_URL[tab_id].length>0){
        if(TAB_SAVE_CONTRACT == 0){
            if(parseInt(CONTRACT_ID)>0){
                savePolicy();
            }

            loadItemsController(TAB_URL[tab_id], '', tab_id);
        }



        TAB_SAVE_CONTRACT = 0;
    }



    //alert(tab_id+"|"+TAB_URL[tab_id]);

}

function getContent(){
    //alert("OK");
    return "";
}

function loadItemsAndCloseController(url){

    $.get(url, {}, function (response)  {
        loaderHide();

        if(response == 200){
            removePanel();
        }else{
            $('#main_container').html(response);
        }



    })  .done(function() {
        loaderShow();
    })
        .fail(function() {
            loaderHide();
        })
        .always(function() {
            loaderHide();
        });


}

function loadItemsController(url, name, tab_id) {

    loaderShow();
    SELECT_MENU = '';
    TAB_SAVE_CONTRACT = 1;

    $.get(url, {}, function (response)  {
        loaderHide();


        $('#main_container').html(response);



        if(name.length > 0){
            setName = name;
            if(parseInt(CONTRACT_ID)>0){
                setName = name+" № "+CONTRACT_ID;
            }

            if(parseInt(tab_id) > 0 ){
                TAB_URL[tab_id] = CONTENT_URL;
            }else{
                addPanel(setName);
                TAB_URL.push(CONTENT_URL);
                tab_id = (TAB_URL.length-1);
                //TAB_URL[(TAB_URL.length-1)] = CONTENT_URL;
            }


        }else{

            TAB_URL[tab_id] = CONTENT_URL;

        }


        TAB_SELECT_INDEX = tab_id;

        $('.c-sidebar__nav-item').removeClass('is-active');

        if(SELECT_MENU){
            $('.'+SELECT_MENU).addClass('is-active');
        }

        //alert('loadItemsController');
        mainInitValData();
        windowsScrollUp();





    })  .done(function() {
        loaderShow();
    })
        .fail(function() {
            loaderHide();
            //window.location.reload();
        })
        .always(function() {
            loaderHide();
        });

}