<?php
    session_start();
    ob_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();
    
    function getAllEvents($dbh){
        $events = array();
        try{
            $stmt = $dbh->prepare(" SELECT idevent, name
                                    FROM event;");
            $stmt->execute();
            $events = $stmt->fetchAll();

            return $events;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $events;
        }
    }

    function getAllManagers($dbh){
        $managers = array();
        try{
            $stmt = $dbh->prepare(" SELECT a.name, a.idattendee 
                                    FROM attendee a
                                    INNER JOIN role as r ON a.role = r.idrole
                                    WHERE r.name = 'event manager';");
            $stmt->execute();
            $managers = $stmt->fetchAll();

            return $managers;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $managers;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Managers to Events</title>
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
                    
                    <a href="adminbrowseeventmanagers.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Events and Managers</a>
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                        <form method="post">
                            <div class="form-group">
                                <label for = "event" >Event name<span class="text-danger">*</span></label>
                                <select name="event" id="event" class="form-control" required> 
                                    <option value="">Select an event</option>
                                    <?php
                                        $events = getAllEvents($dbObj->getDBH());
                                        if(count($events)>0){
                                            foreach($events as $val){
                                        ?>
                                            <option value= <?php echo $val['idevent'];?>><?php echo $val['name'];?></option>    
                                        <?php
                                            }
                                        } 
                                        ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for = "manager" >Manager name<span class="text-danger">*</span></label>
                                <select name="manager" id="manager" class="form-control" required> 
                                    <option value="">Select a manager</option>
                                    <?php
                                        $managers = getAllManagers($dbObj->getDBH());
                                        if(count($managers)>0){
                                            foreach($managers as $val){
                                        ?>
                                            <option value= <?php echo $val['idattendee'];?>><?php echo $val['name'];?></option>    
                                        <?php
                                            }
                                        } 
                                        ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Add</button>
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
                'event' => $_POST['event'],
                'manager' => $_POST['manager'],
            ];

            $sql = "INSERT INTO manager_event (event, manager) VALUES (:event, :manager)";
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            
            header("Location: adminbrowseeventmanagers.php");
            ob_end_flush();
            die();
        }
    ?>
</html>