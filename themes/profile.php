<?php $v->layout("_theme.php") ?>

<div id="div-update-user-info" class="div-edit-report">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="web-div-box">
                    <div class="web-div-title text-center">
                        <h5 class="web-div-title-h5">
                            ATUALIZAR DADOS
                            <span class="icon-close web-div-title-icon" onclick="closeUserUpdataInfo()"></span>
                        </h5>
                    </div>
                    <div class="box-div-info">
                        <div class="row">
                            <div class="col-xl-7">
                                <form id="uploadFileForm" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="name" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-nome.png') ?>">
                                            Nome:
                                        </label>
                                        <input type="text" class="form-control login-input" id="name" name="name"
                                               title="Nome" placeholder="Nome" value="<?= $user->name ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-email.png') ?>">
                                            Email:
                                        </label>
                                        <input type="text" class="form-control login-input" id="name" name="name"
                                               title="Nome" placeholder="Nome" value="<?= $user->email ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-telefone.png') ?>">
                                            Telefone:
                                        </label>
                                        <input type="text" class="form-control login-input" id="name" name="name"
                                               title="Nome" placeholder="Nome" value="<?= $user->phone ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="fileToUpload" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-upload.png') ?>">
                                            Foto de perfil:
                                        </label>
                                        <input class="form-control" type="file" id="fileToUpload" name="fileToUpload"
                                               placeholder="Anexo" required>
                                        <input type="hidden" name="reportId" value="<?= $report->id ?>">
                                    </div>

                                    <hr>
                                    <button type="button" class="btn btn-style-2" onclick="closeUserUpdataInfo()">
                                        Cancelar</button>
                                    <button type="submit" class="btn btn-style-1">Atualizar</button>
                                </form>
                            </div>
                            <div class="col-xl-5 box-div-info-right">
                                <div class="text-center mt-5">
                                    <img class="profile-img" style="width: 40%" src="<?= $user->image ?>">
                                    <p class="profile-user-name" style="margin-bottom: 0;"><?= explode(' ', $user->name)[0]; ?> <?= explode(' ', $user->name)[1]; ?></p>
                                    <p class="text-center" style="margin: 0">Estagiário</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container fade-in">
    <div class="row mt-3 p-4 mobile-top-pages">
        <div class="col-xl-3 text-center mb-3">
            <img class="profile-img" src="<?= $user->image ?>">
            <p class="profile-user-name" style="margin-bottom: 0;"><?= explode(' ', $user->name)[0]; ?> <?= explode(' ', $user->name)[1]; ?></p>
            <p class="text-center" style="margin: 0">Estagiário</p>
        </div>
        <div class="col-xl-9">
            <div class="web-div-box">
                <div class="box-div-info">
                    <p class="text-center">Informações pessoais</p>
                    <hr>
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="profile-div-data">
                                <img src="<?= url('themes/assets/img/icone-matricula.png') ?>">
                                Matrícula: <?= $user->registration; ?>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <div class="profile-div-data">
                                <img src="<?= url('themes/assets/img/icone-nome.png') ?>">
                                Nome: <?= $user->name ?>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="profile-div-data">
                                <img src="<?= url('themes/assets/img/icone-telefone.png') ?>">
                                Fone: <?= $user->phone; ?>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <div class="profile-div-data">
                                <img src="<?= url('themes/assets/img/icone-email.png') ?>">
                                Email: <?= $user->email; ?>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="profile-div-data">
                                <img src="<?= url('themes/assets/img/icone-notificacao.png') ?>">
                                Chamados: <?= $notificationCount; ?>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <div class="profile-div-data">
                                <img src="<?= url('themes/assets/img/icone-orgao.png') ?>">
                                Órgão: <?= $organ ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 mobile-top-pages mt-3 mb-5 profile-div-actions">
            <div class="web-div-box">
                <div class="box-div-info">
                    <p class="text-center">Ações</p>
                    <hr>
                    <p class="profile-div-info-actions" onclick="openUserUpdateInfo()">
                        <img src="<?= url('themes/assets/img/icone-editar.png') ?>">
                        Atualizar dados
                    </p>
                    <p class="profile-div-info-actions">
                        <img src="<?= url('themes/assets/img/icone-email.png') ?>">
                        Enviar um email
                    </p>
                    <p class="profile-div-info-actions">
                        <img src="<?= url('themes/assets/img/icone-orgao.png') ?>">
                        Página do órgão
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-9 mt-3 mobile-top-pages">
            <div class="web-div-box">
                <div class="box-div-info">
                    <p>Pessoas do mesmo órgão</p>
                    <hr>
                    <div class="profile-div-team">
                        <?php
                            if($organGroup):
                                foreach ($organGroup as $organAgent): ?>
                                    <div class="profile-div-team-users text-center">
                                        <img class="profile-div-team-img" src="<?= $organAgent->image ?>">
                                        <p class="profile-div-team-people">
                                            <?= explode(' ',$organAgent->name)[0] ?>
                                        </p>
                                    </div>
                                <?php endforeach;
                            endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script>
    $(document).ready(function () {
        $("#div-edit-report").hide();
    });

    function openUserUpdateInfo() {
        $("#div-update-user-info").show();
    }

    function closeUserUpdataInfo() {
        $("#div-update-user-info").hide();
    }
</script>
<?php $v->end(); ?>
