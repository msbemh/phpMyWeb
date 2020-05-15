
//빈값 체크 함수
var isEmpty = function(value){
    if( value == "" || value == null || value == undefined || ( value != null && typeof value == "object" && !Object.keys(value).length ) ){
        return true
    }else{
        return false
    }
};

let user_id = "";
let nick_name = "";

var express = require('express');
var http = require('http');
var ejs = require('ejs');
var bodyParser = require('body-parser');

//-------https적용------------
var fs = require('fs');
var https = require('https');
var options = {
    ca: fs.readFileSync('/etc/letsencrypt/live/wowtravel.tk/fullchain.pem'),
    key: fs.readFileSync('/etc/letsencrypt/live/wowtravel.tk/privkey.pem'),
    cert: fs.readFileSync('/etc/letsencrypt/live/wowtravel.tk/cert.pem')
};
//---------- memcached -----------
var PHPUnserialize = require('php-unserialize');
var Memcached = require('memcached');
var memcached = new Memcached('127.0.0.1:11211');
var cookie = require('cookie');
var phpUnserialize = require('php-unserialize');
var SESS_PATH = "/web/phpMyWeb/tmp/";
//--------------------------------

var app = express();
app.use(express.static(__dirname + '/'));
app.set('view engine', 'ejs');
app.use(bodyParser.json());
app.use(express.urlencoded({extended: false}));

var server = https.createServer(options,app);

// http server를 socket.io server로 upgrade한다
var io = require('socket.io')(server);

//---- mysql정보  ---
// var mysql      = require('mysql');
// var connection = mysql.createConnection({
//     host     : '127.0.0.1',
//     user     : 'song',
//     password : 'Alshalsh92@seongs22g@',
//     database : 'testDatabase'
// });
var mysql      = require('sync-mysql');
var connection = new mysql({
    host     : '127.0.0.1',
    user     : 'song',
    password : 'Alshalsh92@seongs22g@',
    database : 'testDatabase'
});



// localhost:3000으로(Root경로) 서버에 접속하면 클라이언트로 socketTest.ejs을 전송한다
app.get('/', function(req, res) {
    // res.sendFile(__dirname + '/socketTest.ejs');
    res.render('chatUserList.ejs');
});

//ajax 회원 List 가져오기
app.post('/userList', function(req, res) {
    let user_id = req.body.user_id
    let rows = connection.query('SELECT * from user where not userId like  "%admin%" and userId <> "'+user_id+'"');
    data = JSON.stringify(rows);
    res.send(data);
});

//ajax 유저 세션정보 가져오기
app.post('/userSession', function(req, res) {
    //header에 있는 세션ID를 이용하여 memcached에서 세션정보들을 가져옴.
    var sid = cookie.parse(req.headers.cookie);
    var PHPSESSID = sid.PHPSESSID;
    memcached.get(PHPSESSID,function (err,data) {
        if(!isEmpty(data)){
            data = PHPUnserialize.unserializeSession(data);
            data = JSON.stringify(data);
            user_id = data.userId;
            nick_name = data.nickName;
            res.send(data);

        }else{
            res.send(data);
        }
    });
});

//채팅방으로 이동
app.get('/chatRoom', function(req, res) {
    //상대방 이메일
    let counter_user_email = req.param('counter_user_email');
    //나의 이메일
    let user_email = req.param('user_email');
    //DB쿼리
    let rows = connection.query(
        'SELECT room_no FROM(\n' +
            'SELECT room_no, count(room_no) as cnt FROM participant WHERE user_id = \''+counter_user_email+'\' OR user_id = \''+user_email+'\'\n' +
            'GROUP BY room_no) A\n' +
        'WHERE A.cnt >= 2;');
    //데이터가 없을 경우
    if(rows.length <= 0){
        res.render('chatRoom.ejs',{'room_no':-1});
        return;
    }
    //데이터가 있을 경우
    let room_no = rows[0].room_no;
    global_room_no = room_no;
    res.render('chatRoom.ejs',{'room_no':room_no});
});


// 소켓채팅 관련
// connection event handler
// connection이 수립되면 event handler function의 인자로 socket인 들어온다
io.on('connection', function(socket) {

    //header에 있는 세션ID를 이용하여 memcached에서 세션정보들을 가져옴.
    if(typeof socket.handshake.headers.cookie === "string") {
        //소켓 header정보의 cookie를 가져온다
        var sid = cookie.parse(socket.handshake.headers.cookie);
        console.log("sid:",sid);
        //session 아이디를 가져온다
        var PHPSESSID = sid.PHPSESSID;
        //memcached에서 session 아이디에 대한 정보를 가져온다.
        memcached.get(PHPSESSID,function (err,data) {
            //session데이터가 존재한다면
            if(!isEmpty(data)){
                data = PHPUnserialize.unserializeSession(data);
                socket.userId = data.userId;
                socket.nickName = data.nickName;
                console.log("userId:",socket.userId);
                console.log("nickName:",socket.nickName);
            //session데이터가 없다면 소켓을 끊는다.
            }else{
                socket.disconnect();
            }
        });

    }

    console.log("socket.id:",socket.id);

    //접속한 클라이언트의 정보가 수신되면
    socket.on('login', function(data) {
        console.log('Client logged-in:\n name:' + socket.nickName + '\n userid: ' + socket.userId);

        // 접속된 모든 클라이언트에게 메시지를 전송한다
        io.emit('login', socket.nickName);
    });

    // 클라이언트로부터의 메시지가 수신되면
    socket.on('chat', function(data) {
        let user_email =  socket.userId;
        let user_nick_name =  socket.nickName;
        let msg = data.msg;
        let room_no = data.room_no;

        var send_message = {
            user_nick_name: user_nick_name,
            user_email: user_email,
            msg: data.msg,
            room_no : room_no
        };

        console.log("[서버수신]send_message:",send_message);

        //DB에 messgae 저장
        // let rows = connection.query(
        //     'INSERT INTO message (room_no, user_id, content, creationDate)
        //         VALUES (0,'thdalsehf@naver.com' ,'안녕하세요',now())');

        // 메시지를 전송한 클라이언트를 제외한 모든 클라이언트에게 메시지를 전송한다
        // socket.broadcast.emit('chat', msg);

        // 메시지를 전송한 클라이언트에게만 메시지를 전송한다
        // socket.emit('s2c chat', msg);

        // 접속된 모든 클라이언트에게 메시지를 전송한다
        io.emit('chat', send_message);

        // 특정 클라이언트에게만 메시지를 전송한다
        // io.to(id).emit('s2c chat', data);
    });

    // force client disconnect from server
    socket.on('forceDisconnect', function() {
        socket.disconnect();
    });

    socket.on('disconnect', function() {
        console.log('user disconnected');
    });
});

server.listen(3000, function() {
    console.log('Socket IO server listening on port 3000');
});