
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    @include('organizations.organizations.partials.scans')
</div>



<script>



    function initTab() {
        $("#addOrgDocForm").dropzone({
        //Dropzone.options.addOrgDocForm = {
            paramName: 'scan',
            maxFilesize: 1000,
            //acceptedFiles: "image/*",
            init: function () {
                this.on("complete", function () {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        //location.reload();
                        selectTab(TAB_INDEX);
                    }

                });
            }
        });




    }




</script>