<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header('Accept: application/json');

    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
        require("../connection.php");
                
        $input_json = file_get_contents("php://input");
        $input_array = json_decode($input_json, true);
        
        $data = array();
        
        $userData = mysqli_query($connection, "SELECT image, called FROM maceiogeousers WHERE registration =  ". $input_array['registration'] ."");
        while($rowUser = mysqli_fetch_assoc($userData)){
            $data[] = array('called' => $rowUser['called'], 'image' => $rowUser['image']);
        }
        
        $teamData = mysqli_query($connection, "SELECT telephone, name, init_name, image FROM maceiogeoteam WHERE id = ". $input_array['organ'] ."");
        while($rowTeam = mysqli_fetch_assoc($teamData)){
            $teamCalleds = mysqli_query($connection, "SELECT id FROM maceiogeousersreports WHERE team = ". $input_array['organ'] ." OR team = 0 AND status = 0");
            $num_calleds = mysqli_num_rows($teamCalleds);
            $data[] = array('teamName' => $rowTeam['name'], 'teamInitName' => $rowTeam['init_name'], 'teamImage' => $rowTeam['image'], 'num_calleds'=> $num_calleds, 'telephone' => $rowTeam['telephone']);
        }
        
        $teamCalls = mysqli_query($connection, "SELECT user_registration, title, date FROM maceiogeousersreports WHERE team = ". $input_array['organ'] ." OR team = 0 AND status = 0");
        $num_calls = mysqli_num_rows($teamCalls);
        while($rowCalls = mysqli_fetch_assoc($teamCalls)){
            $callUser = mysqli_query($connection, "SELECT name FROM maceiogeousers WHERE registration = ". $rowCalls['user_registration'] ."");
            while($rowCallUser = mysqli_fetch_assoc($callUser)){
                $agentName = $rowCallUser['name'];
            }
            $calls[] = array('callTitle' => $rowCalls['title'], 'callDate' => $rowCalls['date'], 'callUser' => $agentName);
        }
        
        if ($num_calls > 0){
            $data[] = array('calls' => $calls);
        }
        
        echo(json_encode($data));
        

        mysqli_close($connection);        
    }
?>