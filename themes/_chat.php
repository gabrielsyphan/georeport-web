<?php if(!empty($_SESSION['user']['name'])): ?>
    <div class="webchat-div-box">
        <div class="web-div-box">
            <div class="box-div-info">
                <div class="webchat-div-header">
                    <p>
                        <img src="<?= url('themes/assets/img/icone-chat.png') ?>"> Chat
                        <span class="icon-trash-o div-box-span-icon-chat" title="Apagar todas as mensagens." onclick="clearMessages()"></span>
                        <span style="display: none" id="closeChat" class="icon-close2 div-box-span-icon-chat" onclick="closeChat()" title="Fechar janela de chat."></span>
                        <span id="openChat" class="icon-add div-box-span-icon-chat" onclick="openChat()" title="Abrir janela de chat."></span>
                    </p>
                    <hr style="display: none" id="hrHeader">
                </div>
                <div style="display: none" id="chatBody" class="webchat-div-body"></div>
                <div style="display: none" id="chatFooter" class="webchat-div-footer">
                    <hr>
                    <form id="form-submit-message" style="display: flex">
                        <input style="width: 80%; border-radius: 0;" id="input-message" class="form-control" type="text" placeholder="Insira uma mensagem">
                        <button style="width: 20%; border-radius: 0;" type="submit" class="btn btn-style-1"><span class="icon-send"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearMessages() {
            localStorage.clear();
            $("#chatBody").empty();
        }

        function closeChat() {
            $("#closeChat").hide();
            $("#openChat").show();
            $("#chatBody").hide('slow');
            $("#chatFooter").hide('slow');
            $("#hrHeader").hide('slow');
        }

        function openChat() {
            $("#chatBody").show('slow');
            $("#chatFooter").show('slow');
            $("#closeChat").show();
            $("#hrHeader").show('slow');
            $("#openChat").hide();
            $("#tre").show('slow');
            $('#chatBody').scrollTop($('#chatBody')[0].scrollHeight);
        }
    </script>
<?php endif; ?>
