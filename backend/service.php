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

        $stmt = $conn->prepare("SELECT id, title, description, url, reserver, reserverEmail, cancelationCode, amount FROM wishlistthings");
        
        $stmt->execute();

        $stmt->bind_result($id, $title, $description, $url, $reserver, $reserverEmail, $cancelationCode, $amount);

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
            $r->amount = $amount;
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
        $amount = $obj['amount'];

        $stmt = $conn->prepare("INSERT INTO wishlistthings(title, description, url, amount) VALUES (?,?,?,?)");
        $stmt->bind_param('sssd', $title,$description, $url, $amount);
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
        $amount = $obj['amount'];

        $stmt = $conn->prepare("UPDATE wishlistthings SET `title`=?,`description`=?,`url`=?, `amount`=? WHERE `id`=?");
        $stmt->bind_param('sssdi', $title,$description, $url,$amount, $id);
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

    function reserveThing($obj, $sendMoney) {

        $rv = new stdClass();
        $rv->result = false;
        $rv->reserved = false; 

        $conn = $this->getConn();

        $id = $obj['id'];
        $reserver = $obj['reserver'];
        $reserverEmail = $obj['reserverEmail'];
        $cancelationCode = (string)rand(10000,99999);

        $stmt = $conn->prepare("UPDATE wishlistthings SET `reserver`=?, `reserverEmail`=?, `cancelationCode`=? WHERE `id`=? AND `reserver` IS NULL");        
        $stmt->bind_param('sssi', $reserver, $reserverEmail, $cancelationCode, $id);
        $stmt->execute();
        $rv->result = $stmt->affected_rows > 0;

        $stmt->close();

        if($rv->result) {

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

            $this->sendReserveConfirmationEmail($thing, $sendMoney);

        } else {
            $stmt3 = $conn->prepare("SELECT COUNT(*) FROM wishlistthings WHERE `id`=?");
            $stmt3->bind_param('i', $id);   
        
            $stmt3->execute();

            $stmt3->bind_result($count);

            $stmt3->fetch();

            $stmt3->close();

            if($count > 0) {          
                $rv->message = "No fue posible hacer la reserva. El regalo fue reservado recientemente.";
                $rv->reserved = true;
            }
            else {
                $rv->message = "No fue posible hacer la reserva.";
            }
        }

        

        return $rv;
    }

    function sendReserveConfirmationEmail($thing, $sendMoney) {
        if(!is_null($thing->reserverEmail)) {
            $headers = 'From: Adri y Eze <no-responder@shijieaimilan.tk>';

            if($sendMoney)
                $message = "Gracias por su regalo. En breve recibirá una solicitud de Mercapago para hacer una donación desde el sitio de Mercapago de forma segura";
            else
                $message = "Su reserva ha sido confirmado. ".$thing->title." ha sido reservado para usted. Si se arrepiente puede cancelar la reserva con su código de cancelación: ". $thing->cancelationCode." Muchas gracias ".$thing->reserver;
            mail($thing->reserverEmail, "Reserva confirmada", $message, $headers);
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

    function requestSendMoney($obj) {
        $rv = new stdClass();
        $rv->result = false; 

        $result = $this->reserveThing($obj, true);

        // $rv->result = $result->result;

        if($result->result) {
            $mercapagoRequest = array();
            $mercapagoRequest['email'] = $obj['reserverEmail'];
            $mercapagoRequest['name'] = $obj['reserver'];
            $mercapagoRequest['detail'] = $obj['title'].'. ID REGALO: '.$obj['id'];            
            //amount
            $mercapagoRequest['amount'] = $obj['amount'];
            $result2 = $this->requestMercapago($mercapagoRequest);

            $rv->result = $result2->result;

            $rv->message = $result2->message;
        }
        else {
            $rv->result = $result->result;
            $rv->message = $result->message;
            $rv->reserved = $result->reserved;
        }

        return $rv;
    }

    function requestMercapago($obj) {
        $rv = new stdClass();

        $conn = $this->getConn();
        
        $rv->result = false;
        
        $email = $obj['email'];
        $name = $obj['name'];
        $amount = $obj['amount'];
        $detail = $obj['detail'];

        $stmt = $conn->prepare("INSERT INTO mercapagorequests(name, email,amount,detail) VALUES (?,?,?,?)");
        $stmt->bind_param('ssds', $name,$email,$amount,$detail);
        $rv->result = $stmt->execute();
        if($rv->result) {
            $this->sendMercapagoRequest($email,$name,$amount,$detail);
            $rv->message = "Su solicitud fue recibida. En breve le enviaremos un email solicitando su donación por Mercapago";
        }
        else {
            $rv->message = "La solicitud no pudo ser enviada. Intentelo en otro momento por favor";
        }
        
        $stmt->close();
        $conn->close();

        return $rv;
    }

    function sendMercapagoRequest($email, $name, $amount, $detail) {
        
        $headers = 'From: Adri y Eze <no-responder@shijieaimilan.tk>';

        $message = $name." ( ".$email." ) ha solicitado una donación por mercapago de $".$amount.". Mensaje: ".$detail;
        mail('zeqk.net@gmail.com', "Solicitud de mercapago", $message, $headers);
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