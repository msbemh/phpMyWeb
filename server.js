
//빈값 체크 함수
var isEmpty = function(value){
    if( value == "" || value == null || value == undefined || ( value != null && typeof value == "object" && !Object.keys(value).length ) ){
        return true
    }else{
        return false
    }
};

var express = require('express');
var http = require('http');
var ejs = require('ejs');

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
//--------------------------------

var app = express();
app.use(express.static(__dirname + '/'));
app.set('view engine', 'ejs');
//---------- memcached ------------
var cookie = require('cookie');
var phpUnserialize = require('php-unserialize');
var SESS_PATH = "/web/phpMyWeb/tmp/";

//---------------------------------

var server = https.createServer(options,app);

// http server를 socket.io server로 upgrade한다
var io = require('socket.io')(server);

// localhost:3000으로 서버에 접속하면 클라이언트로 socketTest.ejs을 전송한다
app.get('/', function(req, res) {
    // res.sendFile(__dirname + '/socketTest.ejs');
    res.render('socketTest.ejs',{hello : 'hello2'});
});

// connection event handler
// connection이 수립되면 event handler function의 인자로 socket인 들어온다
var userId = "";
var nickName = "";
io.on('connection', function(socket) {

    //I just check if cookies are a string - may be better method
    if(typeof socket.handshake.headers.cookie === "string") {
        var sid = cookie.parse(socket.handshake.headers.cookie);
        console.log("sid:",sid);
        var PHPSESSID = sid.PHPSESSID;
        memcached.get(PHPSESSID,function (err,data) {
            console.log("data:",data);
            if(!isEmpty(data)){
                data = PHPUnserialize.unserializeSession(data);
                socket.userid = data.userid;
                socket.nickName = data.nickName;
                console.log("userId:",socket.userId);
                console.log("nickName:",socket.nickName);

            }
        });

    }

    console.log("socket.id:",socket.id);

    //접속한 클라이언트의 정보가 수신되면
    socket.on('login', function(data) {
        console.log('Client logged-in:\n name:' + socket.nickName + '\n userid: ' + socket.userid);

        // 접속된 모든 클라이언트에게 메시지를 전송한다
        io.emit('login', socket.nickName );
    });

    // 클라이언트로부터의 메시지가 수신되면
    socket.on('chat', function(data) {
        console.log('Message from %s: %s', socket.name, data.msg);

        var msg = {
            from: {
                name: socket.name,
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