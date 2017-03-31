<?php 
class Service {

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

        $stmt = $conn->prepare("SELECT id, title, description, url, reserver, reserverEmail, cancelationCode FROM wishlistthings");
        
        $stmt->execute();

        $stmt->bind_result($id, $title, $description, $url, $reserver, $reserverEmail, $cancelationCode);

        $rows = array();
        while($stmt->fetch()) {
            $r = new stdClass();
            $r->id = $id;
            $r->title = $title;
            $r->description = $description;
            $r->url = $url;
            $r->reserver = $reserver;
            $r->reserverEmail = $reserverEmail;
            $r->cancelationCode = $cancelationCode;
            $rows[] = $r;
        } 
        $stmt->close();
        
        $conn->close();

        return $rows;
    }

    function addThings($obj) {
        $rv = new stdClass();

        $conn = $this->getConn();

        $title = $obj['title'];
        $description = $obj['description'];
        $url = $obj['url'];

        $stmt = $conn->prepare("INSERT INTO wishlistthings(title, description, url) VALUES (?,?,?)");
        $stmt->bind_param('sss', $title,$description, $url);
        $rv->result = $stmt->execute();
        
        $stmt->close();
        $conn->close();

        return $rv;
    }

    function updateThing($obj) {
        $rv = new stdClass();

        $conn = $this->getConn();

        $id = $obj['id'];
        $title = $obj['title'];
        $description = $obj['description'];
        $url = $obj['url'];

        $stmt = $conn->prepare("UPDATE wishlistthings SET `title`=?,`description`=?,`url`=? WHERE `id`=?");
        $stmt->bind_param('sssi', $title,$description, $url,$id);
        $rv->result = $stmt->execute();

        $stmt->close();
        $conn->close();

        return $rv;
    }

    function deleteThing($obj) {
        $rv = new stdClass();

        $conn = $this->getConn();

        $id = $obj['id'];

        $stmt = $conn->prepare("DELETE FROM wishlistthings WHERE `id`=?");
        $stmt->bind_param('i', $id);
        $rv->result = $stmt->execute();

        $stmt->close();
        $conn->close();

        return $rv;
    }

    function reserveThing($obj) {

        $rv = new stdClass();

        $conn = $this->getConn();

        $id = $obj['id'];
        $reserver = $obj['reserver'];
        $reserverEmail = $obj['reserverEmail'];
        $cancelationCode = (string)rand(10000,99999);

        $stmt = $conn->prepare("UPDATE wishlistthings SET `reserver`=?, `reserverEmail`=?, `cancelationCode`=? WHERE `id`=?");        
        $stmt->bind_param('sssi', $reserver, $reserverEmail, $cancelationCode, $id);
        $rv->result = $stmt->execute();

        $stmt->close();

        $stmt2 = $conn->prepare("SELECT id, title, reserver, reserverEmail, cancelationCode FROM wishlistthings WHERE `id`=?");
        $stmt2->bind_param('i', $id);   

        $stmt2->execute();     

        $stmt2->bind_result($id, $title, $reserver2, $reserverEmail2, $cancelationCode2);

        $thing = null;
        while($stmt2->fetch()) {
            $thing = new stdClass();
            $thing->id = $id;
            $thing->title = $title;
            $thing->reserver = $reserver2;
            $thing->reserverEmail = $reserverEmail2;
            $thing->cancelationCode = $cancelationCode2;    
            break;     
        } 

        $stmt2->close();

        $conn->close();

        $rv->message = "El regalo fue reservado. Su código de cancelación es ".$cancelationCode2;
        //$this->sendReserveConfirmationEmail($thing);

        return $rv;
    }

    function sendReserveConfirmationEmail($thing) {
        if(!is_null($thing->reserveremail)) {
            $message = "Su reserva ha sido confirmado. ".$thing.title." ha sido reservado para usted. Si se arrepiente puede cancelar la reserva con su código de cancelación: ". $thing.cancelationcode." Muchas gracias ".$thing.reserver;
            //mail($thing->reserveremail, "Reserva confirmada", $message);
        }
    }

    function removeReservedThing($obj) {
        $rv = new stdClass();

        $conn = $this->getConn();
        
        $rv->result = false;
        
        $id = $obj['id'];
        $cancelationCode = $obj['cancelationCode'];

        $stmt = $conn->prepare("SELECT cancelationCode FROM wishlistthings WHERE `id`=?");
        $stmt->bind_param('i', $id);

        $stmt->execute();

        $stmt->bind_result($originalCancelationCode);

        $thing = null;
        while($stmt->fetch()) {
            $thing = new stdClass();
            $thing->id = $id;
            $thing->cancelationCode = $originalCancelationCode;         
        } 

        $stmt->close();

        
        if($thing->cancelationCode == $cancelationCode) {

            $stmt2 = $conn->prepare("UPDATE wishlistthings SET `reserver`= NULL, `reserverEmail`=NULL, `cancelationCode`=NULL WHERE `id`='$id'");
            $rv->result = $stmt2->execute();

            $stmt2->close();
        }
        else {
            $rv->message = "El código de cancelación no coincide.";
        }

        $conn->close();

        return $rv;
    }

    function requestMercapago($obj) {
        $rv = new stdClass();

        $conn = $this->getConn();
        
        $rv->result = false;
        
        $email = $obj['email'];
        $name = $obj['name'];
        $amount = $obj['amount'];

        $stmt = $conn->prepare("INSERT INTO mercapagorequests(name, email,amount) VALUES (?,?,?)");
        $stmt->bind_param('ssd', $name,$email,$amount);
        $rv->result = $stmt->execute();
        if($rv->result)
            $rv->message = "Su solicitud fue recibida. En breve le enviaremos un email solicitando su donación por Mercapago";
        else {
            $rv->message = "La solicitud no pudo ser enviada. Intentelo en otro momento por favor";
        }
        
        $stmt->close();
        $conn->close();

        return $rv;
    }

    function login($obj) {
        $rv = false;
        $user = $obj['user'];
        $pass = $obj['pass'];

        $rv = $user == 'zeqk' && $pass == 'ezequiel123';

        return $rv;

    }
}

?>