<?php
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
        
        $id = $input_array['id'];
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

        $result = mysqli_query($connection, "SELECT id, title, date, type, latitude, longitude, description, user_registration, status, team_finish, image, created FROM geo_reports WHERE `id` = ". $id ." AND `status` = 0");

        if(mysqli_error($connection)){
            echo json_encode(mysqli_error($connection));
            exit();
        }

        while($row = mysqli_fetch_assoc($result)){
            $user = mysqli_query($connection, "SELECT registration, name, image FROM geo_users WHERE registration = ". $row['user_registration']);
            
            if(mysqli_error($connection)){
                echo json_encode(mysqli_error($connection));
                exit();
            }
            
            while($userData = mysqli_fetch_assoc($user)){
                $distance = distance($latitude, $longitude, $row['latitude'], $row['longitude']);
                $data = array('id'=> $row['id'], 'date'=> $row['date'], 'distance'=> $distance."Km", 'title'=> $row['title'],'description'=> $row['description'],'type'=> $row['type'], 'userRegistration'=> $row['user_registration'], 'teamFinish'=> $row['team_finish'], 'created'=> $row['created'], 'user' => $userData['name'], 'userImage' => $userData['image'], 'reportImage' => $row['image'], 'latitude' => $row['latitude'], 'longitude' => $row['longitude']);
            }
        }
        
        echo (json_encode($data));
    }
?>