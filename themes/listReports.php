<?php $v->layout("_theme.php") ?>

<?php $v->start("css"); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
      integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
      crossorigin="" />
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.Default.css"); ?>" />
<link rel="stylesheet" href="<?= url("themes/assets/css/MarkerCluster.css"); ?>" />
<script src="<?= url("themes/assets/js/leaflet.js"); ?>"></script>
<script src="<?= url("themes/assets/js/leaflet.markercluster-src.js"); ?>"></script>
<?php $v->end(); ?>

<div id="div-create-report" class="div-edit-report" xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="web-div-box">
                    <div class="web-div-title text-center">
                        <h5 class="web-div-title-h5">
                            CADASTRAR NOVA DENÚNCIA
                            <span class="icon-close web-div-title-icon" onclick="closeCreateReport()"></span>
                        </h5>
                    </div>
                    <div class="box-div-info">
                        <div class="row">
                            <div class="col-xl-7">
                                <form id="form-create-report" method="POST" class="formStyleWidth overflow-modal" style="overflow-x: hidden" enctype="multipart/form-data">
                                    <div id="inputHidden"></div>
                                    <div class="form-group">
                                        <label for="type" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-alerta.png') ?>">
                                            Tipo da denúncia:
                                        </label>
                                        <select class="form-control select-placeholder dropdown" name="type" id="type" required>
                                            <option class="select-opt-0" value="0" disabled selected hidden>Selecione o tipo da denúncia</option>
                                            <option value="1">Estabelecimento irregular</option>
                                            <option value="2">Descarte irregular de lixo</option>
                                            <option value="3">Buraco na via</option>
                                            <option value="4">Lampada queimada</option>
                                            <option value="5">Calçada irregular</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="organ" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-orgao.png') ?>">
                                            Órgão:
                                        </label>
                                        <select class="form-control select-placeholder dropdown" name="organ" id="organ" required>
                                            <option class="select-opt-0" value="0" disabled selected hidden>Selecione o órgão</option>
                                            <option value="1">Secretaria Municipal de Economia</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="title" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-titulo.png') ?>">
                                            Título:
                                        </label>
                                        <input class="form-control" type="text" name="title" id="title" placeholder="Insira um título para a denúncia" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="process" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-codigo.png') ?>">
                                            Processo:
                                        </label>
                                        <input class="form-control" type="number" name="process" id="process" placeholder="Insira o número do processo">
                                    </div>

                                    <div class="form-group">
                                        <label for="date" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-prazo.png') ?>">
                                            Prazo de avaliação:
                                        </label>
                                        <input class="form-control" type="text" name="date" id="date" onfocus="(this.type='date')" placeholder="Insira um prazo de avaliação para a denúncia" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="propertyRegistration" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-casa.png') ?>">
                                            Inscrição imobiliária:
                                        </label>
                                        <input class="form-control" type="text" id="propertyRegistration" name="propertyRegistration" placeholder="Insira a inscrição imobiliária" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="description" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-descricao.png') ?>">
                                            Informe a descrição da denúncia:
                                        </label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Insira uma descrição para a denúncia"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="fileToUpload" class="label-input">
                                            <img src="<?= url('themes/assets/img/icone-anexo.png') ?>">
                                            Anexo:
                                        </label>
                                        <input class="form-control" type="file" id="fileToUpload" name="fileToUpload" placeholder="Anexo" required>
                                    </div>

                                    <hr>
                                    <button type="button" class="btn btn-style-2" onclick="closeCreateReport()">Cancelar</button>
                                    <button type="submit" class="btn btn-style-1">Cadastrar</button>
                                </form>
                            </div>
                            <div class="col-xl-5 box-div-info-right">
                                <div id="mapReport"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container fade-in">
    <div class="row mt-xl-3 p-4 justify-content-center mobile-top-login">
        <div class="col-xl-3">
            <div class="web-div-box">
                <div class="box-div-info">
                    <p class="text-center">Ações</p>
                    <hr>
                    <p class="profile-div-info-actions" onclick="openCreateReport()"><img src="<?= url('themes/assets/img/icone-cadastrar.png') ?>"> Cadastrar nova denúncia</p>
                    <p class="profile-div-info-actions">
                        <a href="<?= url('exportData') ?>">
                            <img src="<?= url('themes/assets/img/icone-planilha.png') ?>"> Exportar denúncias
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-9">
            <div class="web-div-box">
                <div class="box-div-info">
                    <p class="text-center">Denúncias cadastradas</p>
                    <input class="form-control py-2 border" id="filter-data" type="search" placeholder="Filtrar por..." id="example-search-input" onkeyup="tableFilter()">
                    <div class="box-div-info-overflow-y">
                        <table class="table table-striped table-list">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Prazo</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Título</th>
                                </tr>
                            </thead>
                            <tbody id="table-data">
                            <?php
                                if ($reports):
                                    $aux = 1;
                                    foreach ($reports as $report):
                                        if($report->type == 1){
                                            $type = "Estabelecimento irregular";
                                        }else if($report->type == 2){
                                            $type = "Descarte irregular de lixo";
                                        }else if($report->type == 3){
                                            $type = "Buraco na via";
                                        }else if($report->type == 4){
                                            $type = "Lampada queimada";
                                        }else if($report->type == 5){
                                            $type = "Calçada irregular";
                                        }else{
                                            $subtype = "Houve um erro ao definir o tipo da irregularidade.";
                                        }
                                        ?>
                                <tr onclick="openReportPage('<?= $report->id ?>')">
                                    <th scope="row"><?= $aux ?></th>
                                    <td><?= date('d/m/Y', strtotime($report->date)); ?></td>
                                    <td><?= $type; ?></td>
                                    <td><?= $report->title; ?></td>
                                    <td style="display: none"><?= $report->status ?></td>
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
    let map = null;
    let markers = [];
    let mapTiles = {};
    let ctrTiles = {};
    let ctrLayers = {};
    let lat;
    let lng;

    let myMarker = L.icon({
        iconUrl:"<?= url("themes/assets/img/marker-5.png"); ?>",
        shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

        iconSize:[31, 40],
        shadowSize:[41, 41],
        iconAnchor:[15, 41],
        shadowAnchor:[13, 41],
        popupAnchor:[0, -41]
    });

    mapTiles['Mapa Jawg'] = L.tileLayer('https://{s}.tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token=C1v' +
        'u4LOmp14JjyXqidSlK8rjeSlLK1W59o1GAfoHVOpuc6YB8FSNyOyHdoz7QIk6', {
        maxNativeZoom: 19,
        maxZoom: 20,
        minZoom: 10
    });
    ctrTiles["Mapa Jawg"] = mapTiles["Mapa Jawg"];

    mapTiles['Mapa OSM'] = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxNativeZoom: 19,
        maxZoom: 20,
        minZoom: 10
    });
    ctrTiles['Mapa OSM'] = mapTiles['Mapa OSM'];

    mapTiles['Satelite'] = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxNativeZoom: 19,
        maxZoom: 20,
        minZoom: 10,
        subdomains:['mt0','mt1','mt2','mt3']
    });
    ctrTiles["Satelite"] = mapTiles["Satelite"];

    map = L.map('mapReport', {
        center: [-9.664728, -35.721903],
        layers: [mapTiles["Mapa OSM"]],
        zoomControl: true,
        maxZoom: 19,
        minZoom: 10,
        zoom: 14
    });

    L.control.layers(ctrTiles, ctrLayers).addTo(map);

    map.on('contextmenu', function(e) {
        lat = e.latlng.lat;
        lng = e.latlng.lng;

        if(markers) {
            map.removeLayer(markers);
        }

        markers = L.marker(e.latlng, { icon: myMarker })
            .bindPopup('A denúncia será cadastrada aqui')
            .openPopup()
            .addTo(map);

        map.setView(e.latlng);
    });



    function openReportPage(data) {
        window.open("<?= url('reportInfo'); ?>/"+ data, '_blank');
    }

    jQuery(".dropdown").change(function () {
        jQuery(this).removeClass("select-placeholder");
    });

    function nextPage(value) {
        if(value === 2){
            $("#modalPage1").animate({opacity: 0}, "fast", function(){
                document.getElementById('modalPage1').style.display = 'none';
                $("#modalPage2").animate({opacity: 1}, "fast", function(){
                    document.getElementById('modalPage2').style.display = 'block';
                });
            });
        }else if(value === 3){
            $("#modalPage2").animate({opacity: 0}, "fast", function(){
                document.getElementById('modalPage2').style.display = 'none';
                $("#modalPage3").animate({opacity: 1}, "fast", function(){
                    document.getElementById('modalPage3').style.display = 'block';
                });
            });
        }
    }

    function lastPage(value) {
        if(value === 2){
            $("#modalPage3").animate({opacity: 0}, "fast", function(){
                document.getElementById('modalPage3').style.display = 'none';
                $("#modalPage2").animate({opacity: 1}, "fast", function(){
                    document.getElementById('modalPage2').style.display = 'block';
                });
            });
        }else if(value === 1){
            $("#modalPage2").animate({opacity: 0}, "fast", function(){
                document.getElementById('modalPage2').style.display = 'none';
                $("#modalPage1").animate({opacity: 1}, "fast", function(){
                    document.getElementById('modalPage1').style.display = 'block';
                });
            });
        }
    }

    function tableFilter() {
        let input, filter, table, tr, td, i, txtValue;

        input = document.getElementById("filter-data");
        filter = input.value.toUpperCase();
        table = document.getElementById("table-data");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[3 - 1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function reportStatus() {
        let status = 2;
        let table, tr, td;

        table = document.getElementById("table-data");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            tr[i].style.display = "";
            td = tr[i].getElementsByTagName("td");
            if (td) {
                if(status == 0){
                    if(td[3].firstChild.nodeValue !== "0"){
                        tr[i].style.display = "none";
                    }
                }else if(status == 1){
                    if(td[3].firstChild.nodeValue !== "1"){
                        tr[i].style.display = "none";
                    }
                }else{
                    tr[i].style.display = "";
                }
            }
        }
    }

    function openCreateReport() {
        $("#div-create-report").show();
        map.invalidateSize();
    }

    function closeCreateReport() {
        $("#div-create-report").hide();
    }

    $('#form-create-report').on('submit',(function(e) {
        let inputHidden = "<input type='hidden' name='latitude' value='"+ lat+ "'> " +
            "<input type='hidden' name='longitude' value='"+ lng+ "'>";
        $("#inputHidden").empty();
        $("#inputHidden").append(inputHidden);

        e.preventDefault();
        $("#loader-div").show();

        let data = new FormData(this);

        $.ajax({
            type:'POST',
            url: "<?= $router->route("web.validateReport"); ?>",
            data:data,
            cache:false,
            contentType: false,
            processData: false,
            success:function(returnData){
                $("#loader-div").hide();
                if(returnData == 1){
                    alert("A denúncia foi cadastrada com sucesso.");

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
</script>
<?php $v->end(); ?>
