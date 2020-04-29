<!-- 모달 -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="modal_input_url" style="width: 100%;" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
                <button id="url_copy_button" type="button" class="btn btn-primary">url복사</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('ready', function(e) {
        $("#url_copy_button").on("click", function () {
            $("#modal_input_url").trigger("select");
            document.execCommand('copy');
        });
    });
</script>
