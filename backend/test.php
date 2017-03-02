<?php
 //https://www.leaseweb.com/labs/2015/10/creating-a-simple-rest-api-in-php/
// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
 $input = json_decode(file_get_contents('php://input'),true);

echo json_encode($request);
 
// connect to the mysql database
// Create connection
$conn = new mysqli('localhost', 'root', '', 'whishlist');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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