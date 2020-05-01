<?php

include '../DB/DBConnection.php';

session_start();
$user_id = $_SESSION['userId'];
$nick_name = $_SESSION['nickName'];

//Post로 받은 데이터 가져오기
$idx = $_POST['idx'];
$content = $_POST['content'];

//댓글 넣기
$sql = "INSERT INTO freeBoardComment (board_no, writer_email, writer_nick_name, content, creation_date, update_date) VALUES ($idx,'$user_id','$nick_name','$content',now(),now())";
$conn->query($sql);
$comment_no = $conn->insert_id;

//방금 넣은 댓글 정보 가져오기
$sql = "SELECT * FROM freeBoardComment WHERE comment_no = $comment_no";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$comment_no = $row["comment_no"];
$writer_email = $row["writer_email"];
$writer_nick_name = $row["writer_nick_name"];
$content = $row["content"];
$update_date = $row["update_date"];
$update_date = explode(' ', $update_date);
$date = $update_date[0];
$time = $update_date[1];
if($date == Date('Y-m-d'))
    $update_date = $time;
else
    $update_date = $date;

$html =     "<div class=\"container_medium2 comment_view_$comment_no\" style=\"border: 2px solid silver; margin-bottom: 20px;\">
                <div style=\"padding:10px;\">
                    <div style=\"float:left; margin-bottom: 10px; color: #41169A; font-size: 15px; font-weight: bold;\">$writer_nick_name ( $writer_email )</div>";
if($user_id == $writer_email){
    $html = $html. "<div style=\"float:right; color:silver;\"><span style=\"cursor: pointer;\" onclick=\"comment_view_update($comment_no)\">수정</span>&nbsp;|&nbsp;<span style=\"cursor: pointer;\" onclick=\"comment_delete($comment_no)\">삭제</span></div>";
}

$html = $html.     "<div style=\"clear:both;\"></div>
                    <div class=\"content\" style=\"width: 100%; margin-bottom: 10px;\">$content</div>
                    <div class=\"update_date\" style=\"color:silver;\">$update_date</div>
                </div>
            </div>
            <div class=\"container_medium2 comment_textarea_$comment_no\" style=\"display:none; border: 2px solid silver; margin-bottom: 20px;\">
                <div style=\"padding:10px;\">
                <div style=\"float:left; margin-bottom: 10px; color: #41169A; font-size: 15px; font-weight: bold;\">$writer_nick_name ( $writer_email )</div>";
if($user_id == $writer_email){
    $html = $html. "<div style=\"float:right; color:silver;\"><span style=\"cursor: pointer;\" onclick=\"comment_update_cancel($comment_no)\">수정취소</span></div>";
}
$html = $html.     "<div style=\"clear:both;\"></div>
                    <div style='width:100%; position: relative;'>
                        <textarea class=\"content\" style='width: 90%;'></textarea>
                        <button style=\"background: black; color:white; position: absolute; right:0%; top: 50%; transform: translateY(-50%);\"
                            onclick=\"comment_update_btn($comment_no)\" class=\"btn\">수정</button>
                    </div>
                    <div class=\"update_date\" style=\"color:silver;\">$update_date</div>
                </div>
            </div>";
echo $html;
$conn->close();

?>