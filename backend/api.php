
<?php 

//https://www.leaseweb.com/labs/2015/10/creating-a-simple-rest-api-in-php/
//ej. http://localhost/api.php/{$table}/{$id}

include 'db-config.php';

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

$my_method = $request[0];

echo 'metodo: '. $request[0]. '<br>';

echo ' test data: '.$_POST;
 
// connect to the mysql database
// Create connection
$conn = new mysqli($db, $user, $pass, 'whishlist');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


switch ($my_method) {
    case 'get-all-things':
  		$sql = "SELECT * FROM things";
        $result = $conn->query($sql);

        $rows = array();
        while($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        } 
        echo json_encode($rows);
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





$conn->close();
?>
