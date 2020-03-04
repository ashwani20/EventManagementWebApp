<?php 
    session_start();
    if (!isset($_SESSION['attendee'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();

    $name = "";
    if (isset($_POST['search'])){
        $name = $_POST['eventname'];
    }
    
    function getAttendeesData($name, $dbh){
        $attendeeData = array();
        try{
            include "classes/Attendee.class.php";
            $stmt = $dbh->prepare(" SELECT a.idattendee as idattendee, a.name as name,
                                    e.idevent, e.name as event, e.datestart, e.dateend, 
                                    v.name as venue, r.name as role 
                                    FROM event as e
                                    INNER JOIN venue as v ON v.idvenue = e.venue
                                    INNER JOIN attendee_event as m ON m.event = e.idevent
                                    INNER JOIN attendee as a ON a.idattendee = m.attendee
                                    INNER JOIN role as r ON r.idrole = a.role
                                    WHERE r.name = 'attendee'
                                    AND a.idattendee = :attendee
                                    AND e.name like :name;");
            $name = "%$name%";
            $stmt->execute(array('name'=>$name, 'attendee'=>$_SESSION['idattendee']));
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Attendee");

            while($row=$stmt->fetch()){
                $attendeeData[] = $row;
            }
            // var_dump($attendeeData);
            // $attendeeData = $stmt->fetchAll();
            return $attendeeData;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $attendeeData;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Browse Attendees</title>
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
                            <a class="my-0 mr-md-auto font-weight-normal" href="attendee.php">BookMyEvent</a>
                        </h5>
                        <a href="addeventattendees.php" class="float-right btn btn-dark btn-sm">
                            <i class="fa fa-fw fa-plus-circle"></i> Add Events to Attendee
                        </a>
                        <!-- <a style = "margin-right:10px;" href="attendeereg.php" class="float-right btn btn-dark btn-sm">
                            Attendee Page
                        </a> -->
                </div>

                <div class="card-body">
                    <div class="col-sm-12">
                        <h5 class="card-title"><i class="fa fa-fw fa-search"></i> Find your Events</h5>
                        <form action="regattendevent.php" method="POST">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Event's Name</label>
                                        <input type="text" name="eventname" id="eventname" class="form-control" value="" placeholder="Enter event name">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <input type="submit" name="search" value="search" id="search" class="btn btn-primary"><i class="fa fa-fw fa-search"></i></input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
            <div style = "overflow: scroll;">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th>Attendee Name</th>
                            <th>Event</th>
                            <th>DateStart</th>
                            <th>DateEnd</th>
                            <th>Venue</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            $dbObj = new DB();
                            $attendeeData = getAttendeesData($name, $dbObj->getDBH());
                            if(count($attendeeData)>0){
                                foreach($attendeeData as $val){
                            ?>
                            <tr>
                                <td><?php echo $val->getName();?></td>
                                <td><?php echo $val->getEvent();?></td>
                                <td><?php echo $val->getDateStart();?></td>
                                <td><?php echo $val->getDateEnd();?></td>
                                <td><?php echo $val->getVenue();?></td>
                                <td align="center">
                                    <a href="deleteeventattendees.php?editIdEvent=<?php echo $val->getIDEvent();?>" class="text-danger" onClick="return confirm('Are you sure to delete this record?');"><i class="fa fa-fw fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                            <?php
                                }
                            } else{
                            ?>
                            <tr><td colspan="6" align="center">No Record(s) Found!</td></tr>
                        <?php 
                            }
                        ?>                     
                    </tbody>
                </table>
            </div> 
        </div>
    </body>
</html>