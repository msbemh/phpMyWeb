var socket = io.connect('ws://localhost:3000', {secure: true});

socket.on('chat-message',data => {
    console.log(data);
});