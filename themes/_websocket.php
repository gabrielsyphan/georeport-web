<?php if(!empty($_SESSION['user']['name'])): ?>
    <script src="https://ionicwebchat.herokuapp.com/socket.io/socket.io.js"></script>
    <script>
        let messages = [];
        if(localStorage.getItem('messages')) {
            JSON.parse(localStorage.getItem('messages')).forEach(element => {
                messages.push(element);
                $('#chatBody').append($('<p class="chat-message-balloon">').text(element.user + ': ').append($('<span class="span-message-wrap">').text(element.msg)));
            });
        }

        $(function () {
            const socket = io('https://ionicwebchat.herokuapp.com/');

            socket.nickname = '';

            submeterForm(socket);
            $('#form-submit-message').submit(() => sendMessage(socket));

            // socket.on('new user', exibirMsg);
            socket.on('geolocation', curretTeamGeolocation);
            socket.on('chat msg', exibirMsg);
        });

        function sendMessage(socket) {
            socket.emit('chat msg', {date: new Date(), msg: $("#input-message").val()});
            $("#input-message").val('');
            return false;
        }

        function curretTeamGeolocation(data) {
            const auxMarker = Object.values(markers);
            let auxArrayMarker = 0;
            auxMarker.forEach((marker) => {
                if (marker !== undefined && marker.user === data.user) {
                    map.removeLayer(marker.obj);
                    marker.obj = L.marker([data.latitude, data.longitude], { icon: agentMarker }).bindPopup(data.user).addTo(map);
                    auxArrayMarker = 1;
                }
            });

            if (auxArrayMarker === 0) {
                markers.push({
                    user: data.user,
                    obj: L.marker([data.latitude, data.longitude], { icon: agentMarker }).bindPopup(data.user).addTo(map),
                });
            }
        }

        function exibirMsg(msg) {
            let userName = msg.user.split(' ');
            if (userName[1]) {
                userName = userName[0] + ' ' + userName[1];
            } else {
                userName = userName[0];
            }

            messages.push({
               user: userName,
               msg: msg.msg
            });

            localStorage.setItem('messages', JSON.stringify(messages));

            $('#chatBody').append($('<p class="chat-message-balloon">').text(userName + ': ').append($('<span class="span-message-wrap">').text(msg.msg)));
        }

        function submeterForm(socket) {
            if (socket.nickname === '') {
                socket.nickname = '<?= $_SESSION['user']['name'] ?>';
                socket.emit('login', socket.nickname);
            }
            return false
        }
    </script>
<?php endif; ?>
