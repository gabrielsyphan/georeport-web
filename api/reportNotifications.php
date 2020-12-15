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

        $result = mysqli_query($connection, "SELECT title, description, user_name, user_registration, image, created FROM geo_notifications WHERE `report_id` = " . $input_array['id']);
        while($row = mysqli_fetch_assoc($result)){
            $user_data = mysqli_query($connection, "SELECT id, image from geo_users WHERE registration = ". $row['user_registration']);
            while($row2 = mysqli_fetch_assoc($user_data)){
             $data[] = array('title'=> $row['title'], 'description'=> $row['description'], 'user'=> $row['user_name'], 'registration'=> $row['user_registration'], 'created'=> $row['created'], 'image'=> $row2['image'], 'reportImage'=> $row['image']);
            }
        }
        
        echo (json_encode($data));
    }
?>