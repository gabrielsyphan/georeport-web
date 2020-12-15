<?php $v->layout("_theme.php") ?>

<div class="container">
    <div class="row mt-3 p-4 justify-content-center mobile-top-login">
        <div class="col-xl-4 text-center mb-5">
            <div class="web-div-box">
                <div class="box-div-info">
                    <hr>
                    <form id="login-form" class="form-login" method="POST">
                        <div class="form-group">
                            <p class="text-left"> <img src="<?= url('themes/assets/img/icone-identidade.png') ?>"> Insira sua matrícula:</p>
                            <input type="text" class="form-control login-input" id="registration" name="registration" title="Matrícula" placeholder="Matrícula" required>
                        </div>

                        <div class="form-group">
                            <p class="text-left"><img src="<?= url('themes/assets/img/icone-senha.png') ?>"> Insira sua senha:</p>
                            <input type="password" class="form-control login-input" id="password" name="password" title="Senha" placeholder="Senha" required>
                        </div>

                        <p class="login-recovery" onclick="showPswRecovery()">Esqueci minha senha</p>

                        <button type="submit" style="width: 100%;" class="btn btn-style-1 mt-3">Acessar</button>

                        <hr>

                        <div class="col-xl-12 text-center mb-2">
                            <p class="profile-div-team-people">Maceió, <?= utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))) ?></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script>
    $(document).ready(function () {
        $('#login-form').on('submit',(function(e) {
            e.preventDefault();
            $("#loader-div").show();

            let data = new FormData(this);
            $.ajax({
                type:'POST',
                url: "<?= url('validateLogin') ?>",
                data:data,
                cache:false,
                contentType: false,
                processData: false,
                success:function(returnData){
                    $("#loader-div").hide();
                    if(returnData == 1){
                        window.location.href = "<?= url('profile') ?>";
                    }else{
                        alert("Os dados informados estão incorretos");
                    }
                },
                error: function(returnData){
                    $("#loader-div").hide();
                    console.log("error");
                    console.log(returnData);
                }
            });
        }));
    });
</script>
<?php $v->end(); ?>
