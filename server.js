
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
var mysql      = require('mysql');
var connection = mysql.createConnection({
    host     : '127.0.0.1',
    user     : 'song',
    password : 'Alshalsh92@seongs22g@',
    database : 'testDatabase'
});


// localhost:3000으로(Root경로) 서버에 접속하면 클라이언트로 socketTest.ejs을 전송한다
app.get('/', function(req, res) {
    // res.sendFile(__dirname + '/socketTest.ejs');
    res.render('chatUserList.ejs',{hello : 'hello2'});
});

//ajax 회원 List 가져오기
app.post('/userList', function(req, res) {
    let user_id = req.body.user_id
    connection.query('SELECT * from user where not userId like  "%admin%" and userId <> "'+user_id+'"', function(err, rows, fields) {
        if (err){
            console.log('Error while performing Query.', err);
            return;
        }
        data = JSON.stringify(rows);
        res.send(data);
    });
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
    console.log("[room]라우터");
    res.render('chatRoom.ejs');
});



// connection event handler
// connection이 수립되면 event handler function의 인자로 socket인 들어온다
io.on('connection', function(socket) {

    //header에 있는 세션ID를 이용하여 memcached에서 세션정보들을 가져옴.
    if(typeof socket.handshake.headers.cookie === "string") {
        var sid = cookie.parse(socket.handshake.headers.cookie);
        console.log("sid:",sid);
        var PHPSESSID = sid.PHPSESSID;
        memcached.get(PHPSESSID,function (err,data) {
            console.log("data:",data);
            if(!isEmpty(data)){
                data = PHPUnserialize.unserializeSession(data);
                socket.userId = data.userId;
                socket.nickName = data.nickName;
                console.log("userId:",socket.userId);
                console.log("nickName:",socket.nickName);

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
        console.log('Message from %s: %s', socket.nickName, data.msg);

        var msg = {
            from: {
                name: socket.nickName,
                userid: socket.userid
            },
            msg: data.msg
        };

        // 메시지를 전송한 클라이언트를 제외한 모든 클라이언트에게 메시지를 전송한다
        // socket.broadcast.emit('chat', msg);

        // 메시지를 전송한 클라이언트에게만 메시지를 전송한다
        // socket.emit('s2c chat', msg);

        // 접속된 모든 클라이언트에게 메시지를 전송한다
        io.emit('chat', msg);

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