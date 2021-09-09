const io = require('socket.io')();


io.on('connection', client => {
    console.log('a user connected');
    client.emit('init', {data: 'hello world'});
});

io.listen(3000)


  