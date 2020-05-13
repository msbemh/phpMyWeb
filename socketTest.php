<!DOCTYPE html>
<html>
<head>
    <meta name="google-site-verification" content="CmEROeOUZRl5cIPRUdqQwNkTeSsnvs87UyDhC1Pk4_0" />
    <title>여행일정</title>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link type="text/css" rel="stylesheet" href='./css/style.css'>

    <!-- font awesome 추가 -->
    <script src="https://kit.fontawesome.com/60fa22b10c.js" crossorigin="anonymous"></script>

    <!-- Bootstrap cdn 설정 (영어버전) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Bootstrap cdn 설정 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="/socket.io/socket.io.js"></script>
</head>

<body>

<div class="container">
    <h3>Socket.io Chat Example</h3>
    <form class="form-inline">
        <div class="form-group">
            <label for="msgForm">Message: </label>
            <input type="text" class="form-control" id="msgForm">
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
    <div id="chatLogs"></div>
</div>


<script type="text/javascript">
    $(function() {
        // socket.io 서버에 접속한다
        var socket = io();

        // 서버로 자신의 정보를 전송한다.
        socket.emit("login", {
            // name: "ungmo2",
            name: makeRandomName(),
            userid: "ungmo2@gmail.com"
        });

        // 서버로부터의 메시지가 수신되면
        socket.on("login", function(data) {
            $("#chatLogs").append("<div><strong>" + data + "</strong> has joined</div>");
        });

        // 서버로부터의 메시지가 수신되면
        socket.on("chat", function(data) {
            $("#chatLogs").append("<div>" + data.msg + " : from <strong>" + data.from.name + "</strong></div>");
        });

        // Send 버튼이 클릭되면
        $("form").submit(function(e) {
            e.preventDefault();
            var $msgForm = $("#msgForm");

            // 서버로 메시지를 전송한다.
            socket.emit("chat", { msg: $msgForm.val() });
            $msgForm.val("");
        });

        function makeRandomName(){
            var name = "";
            var possible = "abcdefghijklmnopqrstuvwxyz";
            for( var i = 0; i < 3; i++ ) {
                name += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return name;
        }

    });

    $(document).on('ready', function(e){
    });
</script>

</body>
</html>
