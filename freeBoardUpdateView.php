<?php
session_start();
//로그인 세션 없을때
if(!isset($_SESSION['userId'])){
    echo("<script>location.href='/index.php';</script>");
//로그인 세션 있을때
}else{

}


//DB연결
include '../DB/DBConnection.php';

//get방식 data받기
$idx =  $_GET["idx"];

//조회수 증가시키기
$sql = "UPDATE freeBoard SET views = views + 1 WHERE idx = $idx";
$result = $conn->query($sql);

//자유 게시판 view내용 가져오기
$sql = "select * from freeBoard where idx = $idx";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$title = $row["title"];
//\r\n을 <br>태그로 변환
$content = $row["content"];
$content = preg_replace("/\r\n|\r|\n/",'<br>',$content);

$writer = $row["writer"];
$updateDate = $row["updateDate"];



//DB해제
$conn->close();
?>

<script type="text/javascript" src="./util.js"></script>
<script type="text/javascript" src="../smartEditor/js/service/HuskyEZCreator.js" charset="utf-8"></script>

<!DOCTYPE html>
<html>
<head>
    <?php include './header.php'?>
</head>
<body>
<div class="container" style="min-width:550px; height: 500px;">

    <!-- 상단 부분 -->
    <?php include './topPart.php'?>

    <div style="clear: both"></div>
    <!-- 글쓰기 부분 -->
    <div class="container_medium2" >
        <form id ="form" method="POST"  >
            <input type="hidden" id="idx" name="idx" class="form-control" value="<?php echo $idx ?>"/><br>
            <input type="text" id="title" name="title" class="form-control" placeholder="제목 입력"/><br>
            <textarea style="max-width:698px; min-height: 350px;" name="textarea" id="textarea" rows="10" cols="100"></textarea>
        </form>
        <!-- 비디오 업로드 부분 -->
        <form id ="videoForm" action="videoUpload.php" method="POST" enctype="multipart/form-data">
            <h5>비디오 업로드</h5>
            <input type="file" id="file" name="file"/>
            <button type="button" id="video_upload_button">비디오 업로드</button><br>
        </form>

        <!-- 비디오 업로드 진행바 -->
        <progress id="progress_bar" value="0" max="100" style="width:300px;"></progress>
        <?php
        if($writer == $_SESSION['userId']){ ?>
            <div style="float:right; margin-top: 10px;">
                <button type="button" id="save" class="btn btn-dark">저장</button>
                <button type="button" id="cancel" class="btn btn-dark">취소</button>
            </div>
            <?php
        }
        ?>
    </div>

</div>

<script type="text/javascript">
    $(document).on('ready', function(e){

        $("#save").on("click", function() {
            submitContents(document.getElementById("textarea"));
            console.log("textarea:",$("#textarea").val());
        });

        $("#cancel").on("click", function() {
            history.back();
        });


        $("#textarea").val('<?php echo $content ?>');
        $("#title").val('<?php echo $title ?>');

        $("#video_upload_button").on("click", function() {
            let form = $("#videoForm")[0];
            let formData = new FormData(form);
            formData.append("file", $("#file")[0].files[0]);

            //formData가 존재한다면
            if(formData.get("file")["size"] != 0) {
                //ajax통신 시작
                $.ajax({
                    //프로그래스바 동작
                    xhr: function () { //XMLHttpRequest 재정의 가능
                        let xhr = new window.XMLHttpRequest();
                        xhr.upload.onprogress = function (e) { //progress 이벤트 리스너 추가
                            let percent = e.loaded * 100 / e.total;
                            $("#progress_bar").val(percent);
                        };
                        return xhr;
                    },
                    type: "POST",
                    url: "/videoUpload.php",
                    data: formData,
                    dataType: "json",
                    processData: false, //파일 업로드 필수(querystring으로 날아가는것을 false시키는것)
                    contentType: false, //파일 업로드 필수(false로하면 multipart/form-data로 contentType이 설정됨)
                    success: function (data) {
                        console.log("data:", data);
                        //프로그래스바 초기화
                        $("#progress_bar").val(0);
                        /* 스마트에디터부분에 데이터 넣기 */
                        if (data.result != null) {
                            oEditors.getById["textarea"].exec("PASTE_HTML", [data.result]);
                        } else if (data.message) {
                            alert(data.message);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#progress_bar").val(0);
                        console.log(jqXHR.responseText);
                    }
                });
            }else{
                alert("선택한 파일이 없습니다.");
            }

        });
    });

</script>



</body>
</html>

<script type="text/javascript">
    var oEditors = [];
    nhn.husky.EZCreator.createInIFrame({
        oAppRef: oEditors,
        elPlaceHolder: "textarea",
        sSkinURI: "../smartEditor/SmartEditor2Skin.html",
        fCreator: "createSEditor2"
    });

    function submitContents(elClickedObj) {
        // 에디터의 내용이 textarea에 적용된다.
        oEditors.getById["textarea"].exec("UPDATE_CONTENTS_FIELD", []);

        // 에디터의 내용에 대한 값 검증은 이곳에서
        // document.getElementById("textarea").value를 이용해서 처리한다.
        let title = document.getElementById("title").value;
        let content = document.getElementById("textarea").value;

        if(isEmpty(title) || isEmpty(content) || content == "<p><br></p>"){
            alert("제목과 내용을 입력해주세요.")
            return;
        }

        try {
            let $form = $("#form");
            $form.attr("action", "freeBoardUpdate.php");
            elClickedObj.form.submit();
        } catch(e) {
            console.log(e);
        }
    }
</script>
