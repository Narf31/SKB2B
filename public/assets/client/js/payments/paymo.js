
function openPaymoInvoice()
{

    $.getJSON(CONTRACT_URL +'/payment-info/' + CONTRACT_TOKEN + '/', {}, function (response) {

        var keyCount  = Object.keys(response).length;
        if(parseInt(keyCount) > 1){
            PaymoFrame.set(response);
        }

    });



}