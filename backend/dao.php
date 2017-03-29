<?php 
class DAO {

    function getConn() {
        include 'db-config.php';

        // connect to the mysql database
        // Create connection
        $conn = new mysqli($host, $user, $pass, $db);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        return $conn;
    }


    public function getAll() {

        $conn = $this->getConn();

        $sql = "SELECT * FROM wishlistthings";
        $result = $conn->query($sql);

        $rows = array();
        while($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        } 

        
        $conn->close();

        return $rows;
    }

    function addThings($obj) {
        $conn = $this->getConn();

        $title = $obj['title'];
        $description = $obj['description'];
        $url = $obj['url'];
        $reserver = $obj['reserver'];

        $sql = "INSERT INTO wishlistthings(title, description, url, reserver) VALUES ('$title','$description','$url','$reserver')";
        $canUpdate = $conn->query($sql);

        $conn->close();

        return $canUpdate;
    }

    function updateThing($obj) {
        $conn = $this->getConn();

        $id = $obj['id'];
        $title = $obj['title'];
        $description = $obj['description'];
        $url = $obj['url'];
        $reserver = $obj['reserver'];

        $sql = "UPDATE wishlistthings SET `title`='$title',`description`='$description',`url`='$url',`reserver`='$reserver' WHERE `id`='$id'";
        $canUpdate = $conn->query($sql);

        $conn->close();

        return $canUpdate;
    }

    function deleteThing($obj) {
        $conn = $this->getConn();

        $id = $obj['id'];

        $sql = "DELETE FROM wishlistthings WHERE `id`='$id'";
        $canDelete = $conn->query($sql);

        $conn->close();

        return $canDelete;
    }

    function reserveThing($obj) {
        $conn = $this->getConn();

        $id = $obj['id'];
        $reserver = $obj['reserver'];

        $sql = "UPDATE wishlistthings SET `reserver`='$reserver' WHERE `id`='$id'";
        $canUpdate = $conn->query($sql);

        $conn->close();

        return $canUpdate;
    }

    function removeReservedThing($obj) {
        $conn = $this->getConn();
        
        $id = $obj['id'];

        $sql = "UPDATE wishlistthings SET `reserver`= NULL WHERE `id`='$id'";
        $canUpdate = $conn->query($sql);

        $conn->close();

        return $canUpdate;
    }
}

?>