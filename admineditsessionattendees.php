<?php
    session_start();
    ob_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();
    
    $data = array();
    
    if(isset($_GET['editIdAttendee'])){
        try{
            $stmt = $dbObj->getDBH()->prepare(" SELECT a.name 
                                                FROM attendee as a
                                                INNER JOIN attendee_session as m
                                                ON m.attendee = a.idattendee
                                                WHERE idattendee = :idattendee;");
            $stmt->execute(array('idattendee'=>$_GET['editIdAttendee']));
            $data = $stmt->fetchALL();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function getAllSessions($dbh){
        $sessions = array();
        try{
            $stmt = $dbh->prepare(" SELECT name, idsession 
                                    FROM session
                                    WHERE event = (
                                    SELECT event FROM session
                                    WHERE idsession = :idsession);");
            $stmt->execute(array('idsession'=>$_GET['editIdSession']));
            $sessions = $stmt->fetchAll();

            return $sessions;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $sessions;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Attendee's Session</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    </head>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="card-header"> 
                    <h5 class="my-0 mr-md-auto font-weight-normal" style="display:inline"> 
                        <a class="my-0 mr-md-auto font-weight-normal" href="admin.php">BookMyEvent</a>
                    </h5>
                    
                    <a href="adminregattendsession.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Sessions and Attendees</a>
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                        <form method="post">
                            <div class="form-group">    
                                <label for = "attendee" >Attendee name<span class="text-danger">*</span></label>
                                <input type="text" name="attendee" id="attendee" value = "<?php echo $data[0]['name']; ?>" class="form-control" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <label for = "session" >Session name<span class="text-danger">*</span></label>
                                <select name="session" id="session" class="form-control" required> 
                                    <option value="">Select a session</option>
                                    <?php
                                        $sessions = getAllSessions($dbObj->getDBH());
                                        if(count($sessions)>0){
                                            foreach($sessions as $val){
                                                if ($_GET['editIdSession'] == $val['idsession']){
                                        ?>
                                            <option value= <?php echo $val['idsession'];?> selected><?php echo $val['name'];?></option>    
                                        <?php
                                            } else {
                                        ?>
                                            <option value= <?php echo $val['idsession'];?> ><?php echo $val['name'];?></option>    
                                        <?php }
                                            }
                                        } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Edit Attendee's Session</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </body>

    <?php
        if (isset($_POST['submit'])){
            $data = [
                'session' => $_POST['session'],
                'attendee' => $_GET['editIdAttendee']
            ];
            
            $sql = "UPDATE IGNORE attendee_session 
                    SET session= :session
                    WHERE attendee= :attendee";
            var_dump($data);
            var_dump($sql);
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            
            header("Location: adminregattendsession.php");
            ob_end_flush();
            die();
        }
    ?>
</html>