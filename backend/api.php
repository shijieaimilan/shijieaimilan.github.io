
<?php 

//https://www.leaseweb.com/labs/2015/10/creating-a-simple-rest-api-in-php/
//ej. http://localhost/api.php/{$table}/{$id}

include 'db-config.php';

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

$my_method = $request[0];

// echo 'metodo: '. $request[0]. '<br>';
//$data = file_get_contents("php://input");
// $data = json_decode($_POST['data']);
//  echo $data->title;
// echo ' termino';

$data=json_decode($_POST['data'], true);
 
// connect to the mysql database
// Create connection
$conn = new mysqli($host, $user, $pass, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


function getAll($conn) {
    $sql = "SELECT * FROM wishlistthings";
    $result = $conn->query($sql);

    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    } 

    return $rows;
}

function addThings($obj, $conn) {
    $title = $obj['title'];
    $description = $obj['description'];
    $url = $obj['url'];
    $reserver = $obj['reserver'];

    $sql = "INSERT INTO wishlistthings(title, description, url, reserver) VALUES ('$title','$description','$url','$reserver')";
    $canUpdate = $conn->query($sql);
    return $canUpdate;
}

function updateThing($obj, $conn) {
    $id = $obj['id'];
    $title = $obj['title'];
    $description = $obj['description'];
    $url = $obj['url'];
    $reserver = $obj['reserver'];

    $sql = "UPDATE wishlistthings SET `title`='$title',`description`='$description',`url`='$url',`reserver`='$reserver' WHERE `id`='$id'";
    $canUpdate = $conn->query($sql);
    return $canUpdate;
}

function deleteThing($obj, $conn) {
    $id = $obj['id'];

    $sql = "DELETE FROM wishlistthings WHERE `id`='$id'";
    $canDelete = $conn->query($sql);
    return $canDelete;
}

function reserveThing($obj, $conn) {
    $id = $obj['id'];
    $reserver = $obj['reserver'];

    $sql = "UPDATE wishlistthings SET `reserver`='$reserver' WHERE `id`='$id'";
    $canUpdate = $conn->query($sql);
    return $canUpdate;
}

function removeReservedThing($obj, $conn) {
    $id = $obj['id'];

    $sql = "UPDATE wishlistthings SET `reserver`= NULL WHERE `id`='$id'";
    $canUpdate = $conn->query($sql);
    return $canUpdate;
}

$rv;
switch ($my_method) {
    case 'get-all-things':  
        $rv = getAll($conn);
        break;
    case 'add-thing':
        $rv = new stdClass();
        $rv->result = addThings($data, $conn);
        break;
    case 'reserve-thing':
        $rv = new stdClass();
        $rv->result = reserveThing($data, $conn);
        break;
    case 'remove-reserved-thing':
        $rv = new stdClass();
        $rv->result = removeReservedThing($data, $conn);
        break;
    case 'delete-thing':
        $rv = new stdClass();
        $rv->result = deleteThing($data, $conn);
        break;
    case 'update-thing':
        $rv = new stdClass();
        $rv->result = updateThing($data, $conn);
        break;
}

echo json_encode($rv);



$conn->close();
?>
