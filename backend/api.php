
<?php 

include 'dao.php';

//https://www.leaseweb.com/labs/2015/10/creating-a-simple-rest-api-in-php/
//ej. http://localhost/api.php/{$table}/{$id}

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

$my_method = $request[0];

$rv;

try {
    $dao = new DAO;

    $data;
    if(isset($_POST['data'])) {
        $data=json_decode($_POST['data'], true);
    }

    //json_encode($data);

    switch ($my_method) {
        case 'get-all-things':  
            $rv = $dao->getAll();
            break;
        case 'add-thing':
            $rv = new stdClass();
            $rv->result = $dao->addThings($data);
            break;
        case 'reserve-thing':
            $rv = new stdClass();
            $rv->result = $dao->reserveThing($data);
            break;
        case 'remove-reserved-thing':
            $rv = new stdClass();
            $rv->result = $dao->removeReservedThing($data);
            break;
        case 'delete-thing':
            $rv = new stdClass();
            $rv->result = $dao->deleteThing($data);
            break;
        case 'update-thing':
            $rv = new stdClass();
            $rv->result = $dao->updateThing($data);
            break;
    }

} catch (Exception $e) {
    $rv = 'Error: '. $e->getMessage();
}



echo ''.json_encode($rv).'';

?>
