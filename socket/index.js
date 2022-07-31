const cors = require('cors');
const express = require('express');
const app = express();
app.use(cors());
const http = require('http').Server(app);
const io = require('socket.io')(http, {
    cors: {
        origins: ['http://localhost:8000/']
    }
});

const redis = require('redis');

// подключаемся к redis
/*let subscriber = redis.createClient({
    host: 'redis',
    port: 6379,
    password: 'eustatos',
    address: 'redis'
});*/

let redisClient = redis.createClient({
    password: 'eustatos',
    url: 'redis://redis:6379'
});

redisClient.connect().then(() => {
    console.log('Redis connect');
})
.catch(console.error);

redisClient.on('ready', () => {
    console.log('Redis ready');

    redisClient.subscribe("eustatos", (message) => {
        console.log('message');
        console.log(message);
        io.emit('eustatosRoom', message);
    });
});

// открываем соединение socket.io
io.on('connection', function(socket){
    // подписываемся на канал redis 'eustatos' в callback
    console.log('connection');
});

const port = process.env.PORT || 5000;

http.listen(
    port,
    function() {
        console.log('Listen at ' + port);
    }
);