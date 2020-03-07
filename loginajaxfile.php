<?php
        include_once 'classes/PDO.DB.class.php';
        include_once 'sanitizedatafile.php';

        function getUserData($name, $pwd, $dbh) {
            try{
                $pwd = hash('sha256', $pwd);
                $data = array();
                $stmt = $dbh->prepare("SELECT r.name, a.idattendee 
                                    FROM role as r INNER JOIN attendee as a 
                                    ON r.idrole = a.role 
                                    WHERE a.name = :name
                                    AND a.password = :pwd ;");
                $stmt->execute(array('name'=>$name, 'pwd'=>$pwd));
                $data = $stmt->fetch();
                if (count($data) > 0) {
                    return $data;
                } 
                return data;        
            } catch (PDOException $e) {
                echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
                return "";
            }
        }

        function connectToDB(){ 
            $name = $_POST['name'];
            $pwd = $_POST['password'];
            $dbObj = new DB();

            $userData = getUserData($name, $pwd, $dbObj->getDBH());
            $roleType = $userData['name'];
            $idattendee = $userData['idattendee'];
            
            if (empty($roleType)){
                $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Incorrect user name or password!</div>';
                echo json_encode(['code'=>'404', 'msg'=>$msg]);
            }
            else {
                session_start();
                $_SESSION["user"] = $name;
                $_SESSION["idattendee"] = $idattendee;
                $data = [];
                if ($roleType == "admin" || $roleType == "superadmin"){
                    $_SESSION["admin"] = true;
                    $data = ['code'=>'200', 'location' => "admin.php"];
                }
                else if ($roleType == "attendee"){
                    $_SESSION["attendee"] = true;
                    $data = ['code'=>'200', 'location' => "attendee.php"];
                }
                else if ($roleType == "event manager"){
                    $_SESSION["eventmanager"] = true;
                    $data = ['code'=>'200', 'location' => "eventmanager.php"];
                }
                echo json_encode($data);   
            }                  
        }

        $request = $_POST['request'];
        if (isset($_POST['name']) && santizeText($_POST['name']) == ""){
            $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> User name field is empty</div>';
            echo json_encode(['code'=>'404', 'msg'=>$msg]);
        } 
        else if (isset($_POST['password']) && santizeText($_POST['password']) == ""){
            $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> User password field is empty</div>';
            echo json_encode(['code'=>'404', 'msg'=>$msg]);
        }

        else if (isset($_POST['name']) && isset($_POST['password']) && $request == "signin"){
            connectToDB();
        } 

        else if (isset($_POST['name']) && isset($_POST['password']) && $request == "signup"){
            try {
                include_once 'classes/PDO.DB.class.php';
                $dbObj = new DB();
                $attendeeRole = array();
                $stmt = $dbObj->getDBH()->prepare("SELECT idrole from role where name = :name");
                $stmt->execute(array('name'=>'attendee'));

                $attendeeRole = $stmt->fetch();
                // var_dump($attendeeRole);

                $data = [
                    'name' => $_POST['name'],
                    'password' => hash('sha256', $_POST['password']),
                    'role' => $attendeeRole['idrole']
                ];

                $sql = "INSERT INTO attendee (name, password, role) VALUES (:name, :password, :role)";

                $stmt= $dbObj->getDBH()->prepare($sql);
                $stmt->execute($data);
                $data = ['code'=>'200', 'location' => "login.php"];
                echo json_encode($data);  
            } catch (PDOException $e) {
                    echo $e->getMessage();
                    return [];
            }
        }
        
        
    ?>