<?php
    error_reporting(0);

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header('Accept: application/json');

    require("connection.php");
    $return = null;

    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
        $input_json = file_get_contents("php://input");
        $input_array = json_decode($input_json, true);

        // Tratamento do Json dos bairros
        $json_neighborhood = file_get_contents("../themes/assets/json/Bairros.json");
        $json_array = json_decode($json_neighborhood)->features;
        $neighborhood_count = count($json_array) - 1;

        // Declaração de variavéis
        $array = array();

        // Passa o Json com os bairros para um array que deixa ele em ordem crescente quanto aos seus id's
        for ($i = 0; $i <= $neighborhood_count; $i++) {
            $keynumber = $json_array[$i]->properties->ID_BAIRROS;
            $array[$keynumber] = $json_array[$i];
        }

        // Ordena o array em ordem crescente
        sort($array);

        $neighborhood_id = 51;

        // Repete a função para cada um dos bairros existentes no json
        for ($i = 0; $i <= $neighborhood_count; $i++) {
            $array_point = array();
            // Pega cada geopoint do poligono para adicionar no array de pontos
            foreach ($array[$i]->geometry->coordinates[0] as $point) {
                // Adiciona a latitude e longitude da região ao array
                $array_point[] = $point[1] . " " . $point[0];
            }

            //  Adiciona uma virgula entre cada casa do array e transforma ele em uma string
            $str = implode(',', $array_point);

            // Utiliza a string para criar o nome da função do poligono
            $polygon = 'POLYGON((' . $str . '))';

            // Criação do poligono e consulta se há alguma denúncia dentro dele
            $result = mysqli_query($connection, "SELECT id, point FROM geo_reports WHERE ST_CONTAINS(ST_GEOMFROMTEXT('" . $polygon . "'), point) AND id = " . $last_id . "");
            $num_rows = mysqli_num_rows($result);

            // Verifica se houve alguma resposta do banco de dados
            if ($num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Adiciona a região e a denuncia encontrada a um array
                    $neighborhood_id = $i;
                }
            }
            // Elimina o array de pontos
            unset($array_point);
        }
    }
