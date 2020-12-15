<?php
    error_reporting(0);
    
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header('Accept: application/json');

    require("connection.php");
    $return = null;
    
    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        $input_json = file_get_contents("php://input");
        $input_array = json_decode($input_json, true);
        
        //$return["token"] = $_SERVER["HTTP_AUTH_TOKEN"];
        
        if(isset($input_array["type"], $input_array["image"], $input_array["latitude"], $input_array["longitude"], $input_array["accuracy"])){
            // Recebimento dos dados e tratamento do tamanho da imagem que aparecerá no mapa
            $type = $input_array["type"] + 0;
            $latitude = $input_array["latitude"] + 0;
            $longitude = $input_array["longitude"] + 0;
            $accuracy = $input_array["accuracy"] + 0;
            $date = $input_array["date"];
            
            $image = $input_array["image"];
            $imageheader = strtok($image,",");
            $imagebody=strtok("");
	        $data = base64_decode($imagebody);
	        $im = imagecreatefromstring($data);
            $width = imagesx($im);
            $height = imagesy($im);
            $newwidth = 250;
            $newheight = round($height * 250 / $width);
            $thumb = @imagecreatetruecolor($newwidth, $newheight)  or die("Cannot Initialize new GD image stream");
            
            // Resize
            imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            ob_start();
            imagejpeg($thumb, NULL, 30);
            $imagedata =  base64_encode(ob_get_clean());
            $finalimage = $imageheader.",".$imagedata;
            
            if(isset($input_array["description"])){
                $description = utf8_decode($input_array["description"]);
            }else{
                $description = null;
            }
        
            // Formata a latitude e longitude para salvar no formato POINT do mysql
            $point = 'POINT('. $latitude . ' ' . $longitude .')';
                    
            $result = mysqli_query($connection, "INSERT INTO `geo_reports`(`user_registration`, `title`, `type`, `subtype`,  `image`, `description`, `latitude`, `longitude`, `accuracy`, `point`, `date`) VALUES ('". $input_array["registration"] ."','". $input_array["title"] ."'," . $type . ", '". $input_array["subtype"] ."',  '" . $finalimage . "', '" . $description . "', " . $latitude . ", " . $longitude .  ", " . $accuracy . ", GeomFromText('".$point."'), '" . $date . "')");
            
            if(mysqli_error($connection)){
                echo json_encode(mysqli_error($connection));
                exit();
            }
            
            // Pega o ID do autoincrement
            $last_id = mysqli_insert_id($connection);
            
            // Tratamento do Json dos bairros
            $json_neighborhood = file_get_contents("../json/Bairros.json");
            $json_array = json_decode($json_neighborhood)->features;
            $neighborhood_count = count($json_array) - 1;
            
            // Declaração de variavéis
            $array = array();
            
            // Passa o Json com os bairros para um array que deixa ele em ordem crescente quanto aos seus id's
            for($i = 0; $i <= $neighborhood_count; $i++){
                $keynumber = $json_array[$i]->properties->ID_BAIRROS;
                $array[$keynumber] = $json_array[$i];
            }
            
            // Ordena o array em ordem crescente
            sort($array);
            
            $neighborhood_id = 51;

            // Repete a função para cada um dos bairros existentes no json
            for($i = 0; $i <= $neighborhood_count; $i++){
                $array_point = array();
                // Pega cada geopoint do poligono para adicionar no array de pontos
                foreach ($array[$i]->geometry->coordinates[0] as $point){
                    // Adiciona a latitude e longitude da região ao array
                    $array_point[] = $point[1] ." ". $point[0];
                }
            
                //  Adiciona uma virgula entre cada casa do array e transforma ele em uma string
                $str = implode(',', $array_point);
                
                // Utiliza a string para criar o nome da função do poligono
                $polygon = 'POLYGON(('. $str .'))';
                
                // Criação do poligono e consulta se há alguma denúncia dentro dele
                $result = mysqli_query($connection, "SELECT id, point FROM geo_reports WHERE ST_CONTAINS(ST_GEOMFROMTEXT('". $polygon ."'), point) AND id = ". $last_id ."");
                $num_rows = mysqli_num_rows($result);
                
                // Verifica se houve alguma resposta do banco de dados
                if($num_rows > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        // Adiciona a região e a denuncia encontrada a um array
                        $neighborhood_id = $i;
                    }
                }
                // Elimina o array de pontos
                unset($array_point);
            }            
            
            // Faz o update do bairro da denúncia
            mysqli_query($connection, "UPDATE geo_reports SET neighborhood_id = ". $neighborhood_id ." WHERE id = ". $last_id);
            
            // Resposta ao APP
            if($result){
                $return["status"] = 1;
                $return["responseHeader"] = "Sucesso!";
                $return["responseBody"] = "Dados salvos com sucesso.";
            }else{
                $return["status"] = 2;
                $return["responseHeader"] = "Erro!";
                $return["responseBody"] = "Falha ao salvar dados.";
            }
        }else{
            $return["status"] = 0;
            $return["description"] = "Sem dados suficientes para o armazenamento.";
        }
    }
    
    mysqli_close($connection);
    echo json_encode($return);

?>
