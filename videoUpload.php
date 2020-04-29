<?php

include '../DB/DBConnection.php';

//Post로 받은 데이터 가져오기
$name= $_FILES['file']['name'];
$tmp_name= $_FILES['file']['tmp_name'];

$position= strpos($name, ".");

$fileextension= substr($name, $position + 1);

$fileextension= strtolower($fileextension);


if (isset($name)) {
    $realPath= '/usr/local/apache2.4/htdocs/php_uploader/video_upload/';
    $path='/php_uploader/video_upload/';
    if (empty($name)) {
        echo(json_encode(array("message" => "Please choose a file")));
    }else if (!empty($name)){
        if (($fileextension !== "mp4") && ($fileextension !== "ogg") && ($fileextension !== "webm")){
            echo(json_encode(array("message" => "The file extension must be .mp4, .ogg, or .webm in order to be uploaded")));
        }else if (($fileextension == "mp4") || ($fileextension == "ogg") || ($fileextension == "webm")) {
            if (move_uploaded_file($tmp_name, $realPath.$name)) {
                echo(json_encode(array("result" => "<video width='500' controls><source src='$path/$name' type='video/$fileextension'>Your browser does not support the video tag.</video>")));
            }else{
                echo(json_encode(array("message" => "move_uploaded_file실패")));
            }
        }
    }
}else{
    echo(json_encode(array("message" => "파일용량이 너무커서 실패")));
}

$conn->close();

?>