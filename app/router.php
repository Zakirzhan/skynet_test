<?php 

$method = $_SERVER['REQUEST_METHOD'];

$request = $_SERVER['REQUEST_URI'];

switch ($method) {
        case 'GET':
           	
           	$regex = '/^\/pages\/([0-9]*)\/services\/([0-9]*)\/tarifs/';

			if (preg_match($regex, $request, $matches))
			{

				$user_id = $matches[1];

				$service_id = $matches[2];

				$tarifs = $mydb->get_tarifs($user_id,$service_id);
				
			} else die("404");


            break;
        case 'PUT':
           
            $regex = '/^\/pages\/([0-9]*)\/services\/([0-9]*)\/tarif/';

			if (preg_match($regex, $request, $matches))
			{

		 	$tarif_id = $matches[1];

			$service_id = $matches[2];
			
			$tarifs = $mydb->update_service_payday($tarif_id,$service_id);

			} else die("404");

            break;
 }

 echo json_encode($tarifs, JSON_UNESCAPED_UNICODE);
?>