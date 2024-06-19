let socket = io();
let userid = '1111111111';

init()

function init() {
    socket.emit('auth', userid, (err, data) => {
        if (err) { alert(err) }
        else {
            document.getElementById('username').textContent = `${data[0]} ${data[1]}`
        }
    });
}