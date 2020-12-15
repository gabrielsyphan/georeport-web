<?php $v->layout("_theme.php") ?>

<div id="div-upload-report-file" class="div-edit-report">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="web-div-box">
                    <div class="box-div-info">
                        <p class="text-center">
                            Adicionar anexo</p>
                        <hr>
                        <form id="uploadFileForm" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <p style="margin-bottom: 0">
                                    Anexo:
                                </p>
                                <input class="form-control" type="file" id="fileToUpload" name="fileToUpload"
                                       placeholder="Anexo" required>
                                <input type="hidden" name="reportId" value="<?= $report->id ?>">

                                <hr>

                                <button type="button" class="btn btn-style-2" onclick="closeUploadFileModal()">
                                    Voltar</button>
                                <button type="submit" class="btn btn-style-1">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="div-edit-report" class="div-edit-report" xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="web-div-box">
                    <div class="box-div-info">
                        <p class="text-center">
                            Cadastrar chamado</p>
                        <hr>

                        <form id="createNotification" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <p class="form-paragrapher">Título:</p>
                                <input class="form-control" type="text" name="title"
                                     id="title"  placeholder="Insira o título do chamado" required>
                            </div>

                            <div class="form-group">
                                <p class="form-paragrapher">Descrição:</p>
                                <textarea class="form-control" type="text" name="description"
                                          id="description" placeholder="Insira a descrição do chamado" required/></textarea>
                            </div>

                            <div class="form-group">
                                <p class="form-paragrapher">Status:</p>
                                <select class="form-control select-placeholder dropdown" id="status" name="status"
                                        required>
                                    <option selected disabled hidden>Selecione o status</option>
                                    <option value="0">Pendente</option>
                                    <option value="1">Concluído</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <p class="form-paragrapher">Anexo:</p>
                                <input class="form-control select-placeholder dropdown" type="file" id="fileToUpload"
                                       name="fileToUpload" placeholder="Insira um anexo ao chamado" required>
                            </div>

                            <input type="hidden" name="reportId" value="<?= $report->id ?>">

                            <hr>

                            <button type="button" class="btn btn-style-2" onclick="closeCreateReportModal()">Voltar</button>
                            <button type="submit" class="btn btn-style-1">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row mt-xl-3 p-4 justify-content-center mobile-top-login">
        <div class="col-xl-4 mb-3">
            <div class="web-div-box">
                <div class="box-div-info">
                    <p class="text-center">Ações</p>
                    <hr>
                    <p class="profile-div-info-actions" onclick="openUploadFileModal()"><img src="<?= url('themes/assets/img/icone-upload.png') ?>"> Adicionar anexo</p>
<!--                    <p class="profile-div-info-actions"><img src="--><?//= url('themes/assets/img/icone-editar.png') ?><!--"> Alterar dados</p>-->
                    <p class="profile-div-info-actions" onclick="openCreateReportModal()"><img src="<?= url('themes/assets/img/icone-notificacao.png') ?>"> Cadastrar novo chamado</p>
                    <p class="profile-div-info-actions" onclick="deleteReport()"><img src="<?= url('themes/assets/img/icone-apagar.png') ?>"> Excluir denúncia</p>
                </div>
            </div>

            <div class="web-div-box mt-5">
                <div class="box-div-info">
                    <p class="text-center">Chamados</p>
                    <hr>
                    <?php if($notifications): ?>
                    <div id="carousel-report-calls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $aux = 1;
                            foreach ($notifications as $notification):
                                if ($aux == 1): ?>
                                    <div class="carousel-item active">
                                <?php else: ?>
                                    <div class="carousel-item">
                                <?php endif; ?>
                                    <div class="profile-div-data">
                                        <p class="mt-2"><img src="<?= url('themes/assets/img/icone-titulo.png') ?>"> <?= $notification->title ?></p>
                                        <hr>
                                        <p><img src="<?= url('themes/assets/img/icone-descricao.png') ?>"> <?= $notification->description ?></p>
                                        <hr>
                                        <p><img src="<?= url('themes/assets/img/icone-prazo.png') ?>"> <?= date('d/m/Y H:i', strtotime($notification->created)) ?></p>
                                        <hr>
                                        <p class="profile-div-info-actions"><img src="<?= url('themes/assets/img/icone-agente.png') ?>"> <?= $notification->user_name ?></p>
                                        <hr>
                                        <form action="<?= url('openFile/'. $notification->file_name) ?>" class="mb-4">
                                            <button class="btn btn-style-1" type="submit">
                                                <span class="icon-download"></span>
                                                Baixar anexo
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <?php $aux++; endforeach; ?>
                            <a class="carousel-control-prev" href="#carousel-report-calls" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon report-info-span" aria-hidden="true"></span>
                            </a>
                            <a class="carousel-control-next" href="#carousel-report-calls" role="button" data-slide="next">
                                <span class="carousel-control-next-icon report-info-span" aria-hidden="true"></span>
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                        <p class="profile-div-info-actions">Nenhum chamado foi cadastrado</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mobile-top-page mb-3">
            <div class="web-div-box">
                <div class="box-div-info">
                    <p class="text-center">Informações da denúncia</p>
                    <hr>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="profile-div-data">
                                <p class="form-paragrapher">
                                    <img src="<?= url('themes/assets/img/icone-titulo.png') ?>">
                                    Título: <?= $report->title ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="profile-div-data">
                                <p class="form-paragrapher">
                                    <img src="<?= url('themes/assets/img/icone-descricao.png') ?>">
                                    Descrição: <?= $report->description ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="profile-div-data">
                                <p class="form-paragrapher">
                                    <img src="<?= url('themes/assets/img/icone-prazo.png') ?>">
                                    Prazo: <?= date('d/m/Y', strtotime($report->created)); ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="profile-div-data">
                                <p class="form-paragrapher">
                                    <img src="<?= url('themes/assets/img/icone-alerta.png') ?>">
                                    Tipo:
                                    <?php if ($report->type == 1): ?>
                                        Estabelecimento Irregular
                                    <?php elseif ($report->type == 2): ?>
                                        Descarte Irregular de Lixo
                                    <?php elseif ($report->type == 3): ?>
                                        Buraco na via
                                    <?php elseif ($report->type == 4): ?>
                                        Lampada queimada
                                    <?php elseif ($report->type == 5): ?>
                                        Calçada irregular
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="profile-div-data">
                                <p class="form-paragrapher">
                                    <img src="<?= url('themes/assets/img/icone-codigo.png') ?>">
                                    Processo: <?= $report->process ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="profile-div-data">
                                <p class="form-paragrapher">
                                    <img src="<?= url('themes/assets/img/icone-casa.png') ?>">
                                    Inscrição: <?= $report->process ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="profile-div-data mb-3">
                                <p class="form-paragrapher">
                                    <img src="<?= url('themes/assets/img/icone-checkbox.png') ?>">
                                    Status:
                                    <?php if ($report->status == 0): ?>
                                        Pendente
                                    <?php else: ?>
                                        Concluído
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="web-div-box mt-5">
                <div class="box-div-info">
                    <p class="text-center">Anexos</p>
                    <div class="box-div-info-overflow-y-attach">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Ações</th>
                            </tr>
                            </thead>
                            <tbody id="table-data">
                            <?php if($uploads && count($uploads) > 0):
                                $aux = 1;
                                foreach($uploads as $upload): ?>
                                    <tr>
                                        <th scope="row"><?= $aux ?></th>
                                        <td><?= $upload['fileName'] ?></td>
                                        <td>
                                            <form action="<?= url('openFile/'. $upload['localName']) ?>">
                                                <button class="btn btn-style-1" type="submit">
                                                    <span class="icon-download"></span>
                                                    Baixar
                                                </button>
                                                <button class="btn btn-style-2" onclick="confirmDelete(
                                                    '<?= $upload['localName'] ?>')" type="button">
                                                    <span class="icon-trash"></span>
                                                    Apagar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php $aux++; endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $v->start("scripts"); ?>
<script>
    $(document).ready(function () {
        jQuery(".dropdown").change(function () {
            jQuery(this).removeClass("select-placeholder");
        });
    });

    function openUploadFileModal() {
        $("#div-upload-report-file").show();
    }

    function closeUploadFileModal() {
        $("#div-upload-report-file").hide();
    }

    function openCreateReportModal() {
        $("#div-edit-report").show();
    }

    function closeCreateReportModal() {
        $("#div-edit-report").hide();
    }

    function confirmDelete(e) {
        if(confirm("Deseja mesmo remover esse anexo?")) {
            $("#loader-div").show();
            const data = {fileName: e}
            $.ajax({
                type:'POST',
                url: "<?= $router->route("web.deleteAttach"); ?>",
                data:data,
                success:function(returnData){
                    $("#loader-div").hide();
                    if(returnData == 1){
                        alert("O anexo foi removido com sucesso.");
                        window.location.reload();
                    } else {
                        alert("Não foi possível remover o anexo. Tente novamente mais tarde.");
                        console.log(returnData);
                    }
                },
                error: function(returnData){
                    $("#loader-div").hide();
                    alert("Não foi possível remover o anexo. Tente novamente mais tarde.");
                    console.log(returnData);
                }
            });
        }
    }

    $('#uploadFileForm').on('submit',(function(e) {
        e.preventDefault();
        $("#loader-div").show();

        let data = new FormData(this);

        $.ajax({
            type:'POST',
            url: "<?= $router->route("web.validateUpload"); ?>",
            data:data,
            cache:false,
            contentType: false,
            processData: false,
            success:function(returnData){
                $("#loader-div").hide();
                if(returnData == 1){
                    alert("O anexo foi cadastrada com sucesso.");
                    window.location.reload();
                } else {
                    alert("Não foi possível enviar o anexo. Tente novamente mais tarde.");
                    console.log(returnData);
                }
            },
            error: function(returnData){
                $("#loader-div").hide();
                alert("Não foi possível enviar o anexo. Tente novamente mais tarde.");
                console.log(returnData);
            }
        });
    }));

    $('#createNotification').on('submit',(function(e) {
        e.preventDefault();
        $("#loader-div").show();

        let data = new FormData(this);

        $.ajax({
            type:'POST',
            url: "<?= $router->route("web.validateNotification"); ?>",
            data:data,
            cache:false,
            contentType: false,
            processData: false,
            success:function(returnData){
                $("#loader-div").hide();
                if(returnData == 1){
                    alert("O chamado foi cadastrada com sucesso.");

                    window.location.reload();
                } else {
                    alert("Não foi possível cadastrar a denúncia. Tente novamente mais tarde.");
                    console.log(returnData);
                }
            },
            error: function(returnData){
                $("#loader-div").hide();
                alert("Não foi possível cadastrar a denúncia. Tente novamente mais tarde.");
                console.log(returnData);
            }
        });
    }));

    $('#fileToUpload').change(function(e){
        var fileName = e.target.files[0].name;
        var ext = fileName.substr(fileName.lastIndexOf('.') + 1);
        if(ext === 'jpg' || ext === 'jpeg' || ext === 'png' || ext === 'JPG' || ext === 'JPEG' || ext === 'PNG'){
            if(e.target.files[0].size > 1133695){
                alert("Por favor, insira uma imagem com no máximo 1mb de tamanho.");
                $('#fileToUpload').val('');
            }
        }else{
            alert("O tipo do anexo é inválido. Por favor, insira uma imagem em formato JPEG, JPG ou PNG.");
            $('#fileToUpload').val('');
        }
    });

    function deleteReport() {
        if(confirm("Deseja mesmo excluir essa denúncia?") === true) {
            $.ajax({
                type:'POST',
                url: "<?= $router->route("web.deleteReport"); ?>",
                data: {'reportId': <?= $report->id ?>},
                success:function(returnData){
                    $("#loader-div").hide();
                    if(returnData == 1){
                        alert("A denúncia foi excluída com sucesso.");
                        window.location.href = "<?= url("listReports") ?>";
                    } else {
                        alert("Não foi possível excluir a denúncia. Tente novamente mais tarde.");
                        console.log(returnData);
                    }
                },
                error: function(returnData){
                    $("#loader-div").hide();
                    alert("Não foi possível excluir a denúncia. Tente novamente mais tarde.");
                    console.log(returnData);
                }
            });
        }
    }
</script>
<?php $v->end(); ?>
