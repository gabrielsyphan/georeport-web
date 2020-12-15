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

<div id="map"></div>

<div class="div-map-serch">
    <form id="form-search-map" style="display: flex">
        <input class="form-control input-map-search" type="text" name="searchText"
               placeholder="Pesquisar por...">
        <button type="submit" class="btn btn-style-1 btn-map-search">
            <span class="icon-search"></span>
        </button>
    </form>
</div>

<?php $v->start("scripts"); ?>
<script>
    let markers = [];
    let map = null;
    let myMarker = L.icon({
        iconUrl:"<?= url("themes/assets/img/marker-5.png"); ?>",
        shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

        iconSize:[31, 40],
        shadowSize:[41, 41],
        iconAnchor:[15, 41],
        shadowAnchor:[13, 41],
        popupAnchor:[0, -41]
    });

    let searchMarker = L.icon({
        iconUrl:"<?= url("themes/assets/img/marker-4.png"); ?>",
        shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

        iconSize:[31, 40],
        shadowSize:[41, 41],
        iconAnchor:[15, 41],
        shadowAnchor:[13, 41],
        popupAnchor:[0, -41]
    });

    let agentMarker = L.icon({
        iconUrl:"<?= url("themes/assets/img/marker-icon.png"); ?>",
        shadowUrl:"<?= url("themes/assets/img/marker-shadow.png"); ?>",

        iconSize:[31, 40],
        shadowSize:[41, 41],
        iconAnchor:[15, 41],
        shadowAnchor:[13, 41],
        popupAnchor:[0, -41]
    });

    let groupMarker = new L.MarkerClusterGroup({
        disableClusteringAtZoom: 14,
        showCoverageOnHover: true,
        zoomToBoundsOnClick: true,
        spiderfyOnMaxZoom: true
    });

    $("#form-search-map").on("submit", function (e) {
        e.preventDefault();
        $("#loader-div").show();
        const text = $(".input-map-search").val();
        $(".input-map-search").val('')
        if (isNaN(text)) {
            $.getJSON("https://nominatim.openstreetmap.org/search?q="+ text +"&format=geojson&viewbox=" +
                "-35.829849243164055,-9.723946569800441,-35.591583251953125,-9.467349225571983&bounded=1",
                function(geoinfo){
                    $.each(geoinfo.features, function(a){
                        if(geoinfo.features[a].geometry.type == "Point"){
                            var coordinates = geoinfo.features[a].geometry.coordinates;
                            L.marker([coordinates[1], coordinates[0]],{icon:searchMarker,
                                alt:(JSON.stringify(geoinfo.features[a].properties))})
                                .bindPopup("Local: " + geoinfo.features[a].properties.display_name).addTo(map);
                            map.setView([coordinates[1], coordinates[0]], 14);
                        }
                    });
                });
            $("#loader-div").hide();
        } else {
            $.ajax({
                type:"POST",
                url: "https://semecmaceio.com/v1/integracao/polygon",
                headers: {
                    'Content-type': 'application/x-www-form-urlencoded'
                },
                dataType: 'json',
                data: '{"lotes":[{"inscricao":'+ text +'}]}',
                success:function(data){
                    L.geoJSON(data.features[0]).bindPopup(data.features[0].properties.LOGRADOURO +" - "+ data.features[0].properties.PATH).addTo(map).openPopup();
                    console.log(data);
                    $("#loader-div").hide();
                },
                error: function(data){
                    $("#loader-div").hide();
                    console.log("error");
                    console.log(data);
                    $("#loader-div").hide();
                }
            });
        }
    });

    $(document).ready(function () {
        let mapTiles = {};
        let ctrTiles = {};
        let ctrLayers = {};

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

        map = L.map('map', {
            center: [-9.664728, -35.721903],
            layers: [mapTiles["Mapa OSM"]],
            zoomControl: true,
            maxZoom: 19,
            minZoom: 10,
            zoom: 14
        });

        // map.on('contextmenu', function(e) {
        //     L.marker(e.latlng, { icon: myMarker })
        //         .bindPopup('<button>Cadastrar uma denúncia aqui</button>')
        //         .openPopup()
        //         .addTo(map);
        // });

        L.control.layers(ctrTiles, ctrLayers).addTo(map);

        <?php if($points): foreach($points as $point): ?>
            L.marker(['<?= $point->latitude ?>','<?= $point->longitude ?>'], { icon: myMarker })
                .bindPopup('<div style="font-weight: bold; text-align: center; color: #df514f;"><?= $point->title ?>' +
                '</div><hr style="background: #e4e4e4;"><div><span style="font-weight: 700;">Descrição:</span>' +
                '<?= $point->description ?></div><div><span style="font-weight: 700;">Prazo:</span>' +
                '<?= date('d-m-Y', strtotime($point->date)); ?></div><br><div style="text-align: center">'+
                '<img style="border-radius: 5px;width: 100%;" src="<?= $point->image ?>">').addTo(groupMarker);
        <?php endforeach; endif; ?>

        $("#loader-div").hide();
        map.addLayer(groupMarker);
    });
</script>
<?php $v->end(); ?>
