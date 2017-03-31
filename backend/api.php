
<?php 

include 'service.php';

//https://www.leaseweb.com/labs/2015/10/creating-a-simple-rest-api-in-php/
//ej. http://localhost/api.php/{$table}/{$id}

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

$my_method = $request[0];

$rv;

try {
    $service = new Service;

    $data;
    if(isset($_POST['data'])) {
        $data=json_decode($_POST['data'], true);
    }

    //json_encode($data);

    switch ($my_method) {
        case 'get-all-things':  
            $rv = $service->getAll();
            break;
        case 'add-thing':
            $rv = $service->addThings($data);
            break;
        case 'reserve-thing':
            $rv = $service->reserveThing($data);
            break;
        case 'remove-reserved-thing':
            $rv = $service->removeReservedThing($data);
            break;
        case 'delete-thing':
            $rv = $service->deleteThing($data);
            break;
        case 'update-thing':
            $rv = $service->updateThing($data);
            break;
        case 'request-mercapago':
            $rv = $service->requestMercapago($data);
            break;
        case 'login':
            $rv = $service->login($data);
            break;
    }

} catch (Exception $e) {
    $rv = 'Error: '. $e->getMessage();
}



echo ''.json_encode($rv).'';

?>
