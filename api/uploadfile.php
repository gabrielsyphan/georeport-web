<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header('Accept: application/json');

    require("../connection.php");
    
    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        $input_json = file_get_contents("php://input");
        $input_array = json_decode($input_json, true);
		
		file_put_contents("../upload/". $input_array['name'], $input_array['content']);
		
		echo (json_encode($input_array['content']));
    }
    
    mysqli_close($connection);
?>