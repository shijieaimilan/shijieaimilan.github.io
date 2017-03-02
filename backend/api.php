<?php
 //https://www.leaseweb.com/labs/2015/10/creating-a-simple-rest-api-in-php/
// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

$my_method = $request[0];

echo 'metodo: '. $request[0]. '<br>';
 
// connect to the mysql database
// Create connection
$conn = new mysqli('localhost', 'root', '', 'whishlist');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


switch ($my_method) {
    case 'get-available-things':
        echo "i es igual a 0";
        break;
    case 'get-reserved-things':
        echo "i es igual a 1";
        break;
    case 'get-all-things':
        echo "i es igual a 2";
        break;
    case 'add-thing':
        echo "i es igual a 2";
        break;
    case 'reserve-thing':
        echo "i es igual a 2";
        break;
    case 'remove-reserved-thing':
        echo "i es igual a 2";
        break;
    case 'confirm-attendance':
        echo "i es igual a 2";
        break;
}



$sql = "SELECT * FROM things";
$result = $conn->query($sql);

$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
echo json_encode($rows);
$conn->close();
?>