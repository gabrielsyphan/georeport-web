<?php
    error_reporting(0);
    
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header('Accept: application/json');

    require("connection.php");
    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        
        $input_json = file_get_contents("php://input");
        $input_array = json_decode($input_json, true);        
        
        $data = array();
        
        $neighborhoodId = $input_array['id'];
        $latitude = $input_array['latitude'];
        $longitude = $input_array['longitude'];

        // Função para calcular a distancia entre 2 pontos em km
        function distance($lat1, $lon1, $lat2, $lon2) {
            $lat1 = deg2rad($lat1);
            $lat2 = deg2rad($lat2);
            $lon1 = deg2rad($lon1);
            $lon2 = deg2rad($lon2);
            
            $dist = (6371 * acos( cos( $lat1 ) * cos( $lat2 ) * cos( $lon2 - $lon1 ) + sin( $lat1 ) * sin($lat2) ) );
            $dist = number_format($dist, 2, '.', '');
            return $dist;
        }

        $result = mysqli_query($connection, "SELECT id, title, date, type, latitude, longitude, description, image FROM geo_reports WHERE `neighborhood_id` = ". $neighborhoodId ." AND `status` = 0");

        if(mysqli_error($connection)){
            echo json_encode(mysqli_error($connection));
            exit();
        }

        while($row = mysqli_fetch_assoc($result)){
            $distance = distance($latitude, $longitude, $row['latitude'], $row['longitude']);
            $data[] = array('id'=> $row['id'], 'date'=> $row['date'], 'distance'=> $distance."Km", 'title'=> $row['title'],'description'=> $row['description'],'type'=> $row['type'], 'image'=> $row['image']);
        }
        
        echo (json_encode($data));
    }
?>