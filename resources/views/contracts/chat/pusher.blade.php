<script>
    var channel = pusher.subscribe('contracts');

    channel.bind('contract.created', function (data) {
        playSound();
        reloadTable(function(){
            highlightRow(data.contract.id);
        });
    });
    
    function highlightRow(contractId) {
        dataTable.rows().eq( 0 ).filter( function (rowIdx) {
            if(dataTable.cell( rowIdx, 0 ).data() == contractId){
                dataTable.row(rowIdx).nodes().to$().addClass('green');
            }
        } );
    }

    function playSound() {
        new Audio('{{ url("/sounds/announcement.mp3") }}').play();
    }
</script>