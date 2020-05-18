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

<!-- 채팅 모달 -->
<div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">채팅창</h5>
                <button type="button" class="close modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px;">
                <iframe class="chat_iframe_modal" id="chat_iframe_modal" style="width:100%; height: 100%;" src=""></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal_close" data-dismiss="modal">취소</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    //부모가 자식에게 메시지 보내기
    function sendMessageIframe(){
        let iframe = document.getElementById('chat_iframe_modal').contentWindow;
        iframe.postMessage({'message' : "socket_close"}, 'https://wowtravel.tk:3000/chatRoom.ejs' );
    }

    $(document).on('ready', function(e) {
        $("#url_copy_button").on("click", function () {
            $("#modal_input_url").trigger("select");
            document.execCommand('copy');
        });

        //채팅 모달창 닫힐때 자식에게 메시지 보내기
        $(".modal_close").on("click", function () {
            console.log("모달 닫기 클릭!");
            sendMessageIframe();
        });

    });
</script>
