<?php 
    session_start();
    if (!isset($_SESSION['eventmanager'])){
        header("Location: login.php");
        die();
    } 

    
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();

    $name = "";
    if (isset($_POST['search'])){
        include_once 'sanitizedatafile.php';
        $name = santizeText($_POST['eventname']);
    }
    
    function getEventData($name, $dbh){
        $eventData = array();
        try{
            $stmt = $dbh->prepare(" SELECT e.idevent, e.name, e.datestart, e.dateend, 
                                    e.numberallowed, v.name as venue, a.name as manager, 
                                    a.idattendee as idmanager
                                    FROM event as e
                                    INNER JOIN manager_event as m ON e.idevent= m.event
                                    INNER JOIN attendee as a ON a.idattendee= m.manager
                                    INNER JOIN role as r ON r.idrole= a.role
                                    INNER JOIN venue as v ON v.idvenue = e.venue
                                    WHERE r.name = 'event manager'
                                    AND a.idattendee = :idattendee
                                    AND e.name like :name;");
           
            $name = "%$name%";
            $stmt->execute(array('name'=>$name, 'idattendee'=>$_SESSION["idattendee"]));
            $eventData = $stmt->fetchAll();
            return $eventData;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $eventData;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Browse Events</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="card-header"> 
                        <h5 class="my-0 mr-md-auto font-weight-normal" style="display:inline"> 
                            <a class="my-0 mr-md-auto font-weight-normal" href="eventmanager.php">BookMyEvent</a>
                        </h5>
                        <a href="eventmanageraddevents.php" class="float-right btn btn-dark btn-sm">
                            <i class="fa fa-fw fa-plus-circle"></i> Add Events
                        </a>
                </div>
                <div class="card-body">
                    <div class="col-sm-12">
                        <h5 class="card-title"><i class="fa fa-fw fa-search"></i> Find Event</h5>
                        <form action="eventmanagerbrowseevents.php" method="POST">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Event Name</label>
                                        <input type="text" name="eventname" id="eventname" class="form-control" value="" placeholder="Enter event name">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <input type="submit" name="search" value="search event" id="search" class="btn btn-primary"><i class="fa fa-fw fa-search"></i></input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
            <div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th>Event ID</th>
                            <th>Event Name</th>
                            <th>StartDate</th>
                            <th>EndDate</th>
                            <th>Capacity</th>
                            <th>Venue</th>
                            <th>Manager</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $dbObj = new DB();
                            $eventData = getEventData($name, $dbObj->getDBH());
                            if(count($eventData)>0){
                                foreach($eventData as $val){
                            ?>
                            <tr>
                                <td><?php echo $val['idevent'];?></td>
                                <td><?php echo $val['name'];?></td>
                                <td><?php echo $val['datestart'];?></td>
                                <td><?php echo $val['dateend'];?></td>
                                <td><?php echo $val['numberallowed'];?></td>
                                <td><?php echo $val['venue'];?></td>
                                <td><?php echo $val['manager'];?></td>
                                <td align="center">
                                    <a href="eventmanagereditevents.php?editIdEvent=<?php echo $val['idevent'];?>&idManager=<?php echo $val['idmanager'];?>" class="text-primary"><i class="fa fa-fw fa-edit"></i> Edit</a> | 
                                    <a href="eventmanagerdeleteevents.php?delIdEvent=<?php echo $val['idevent'];?>&idManager=<?php echo $val['idmanager'];?>" class="text-danger" onClick="return confirm('Are you sure to delete this event?');"><i class="fa fa-fw fa-trash"></i> Delete</a>
                                </td>
                                
                            </tr>
                            <?php }
                            } else{
                            ?>
                            <tr><td colspan="6" align="center">No Record(s) Found!</td></tr>
                        <?php } ?>
                        
                    </tbody>
                </table>
            </div> 
        </div>
    </body>
</html>