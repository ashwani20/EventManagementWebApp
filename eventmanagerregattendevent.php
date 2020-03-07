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
        $name = santizeText($_POST['attendeename']);
    }
    
    function getAttendeesData($name, $dbh){
        $attendeeData = array();
        try{
            $stmt = $dbh->prepare(" SELECT a.idattendee as AttendeeID, a.name as Attendee,
                                    e.idevent, e.name as Event, m.paid
                                    FROM event as e
                                    INNER JOIN attendee_event as m ON m.event = e.idevent
                                    INNER JOIN manager_event as me ON me.event = e.idevent
                                    INNER JOIN attendee as a ON a.idattendee = m.attendee
                                    INNER JOIN role as r ON r.idrole = a.role
                                    WHERE r.name = 'attendee'
                                    AND me.manager = :manager
                                    AND a.name like :name;");
            $name = "%$name%";
            $stmt->execute(array('name'=>$name, 'manager'=>$_SESSION['idattendee']));
            $attendeeData = $stmt->fetchAll();
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
                            <a class="my-0 mr-md-auto font-weight-normal" href="eventmanager.php">BookMyEvent</a>
                        </h5>
                        <a href="eventmanageraddeventattendees.php" class="float-right btn btn-dark btn-sm">
                            <i class="fa fa-fw fa-plus-circle"></i> Add Events to Attendee
                        </a>
                        <a style = "margin-right:10px;" href="eventmanagerattendeereg.php" class="float-right btn btn-dark btn-sm">
                            Attendee Page
                        </a>
                </div>

                <div class="card-body">
                    <div class="col-sm-12">
                        <h5 class="card-title"><i class="fa fa-fw fa-search"></i> Find Event Attendees</h5>
                        <form action="eventmanagerregattendevent.php" method="POST">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Attendee's Name</label>
                                        <input type="text" name="attendeename" id="attendeename" class="form-control" value="" placeholder="Enter attendee name">
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
                            <th>Amount Paid</th>
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
                                <td><?php echo $val['Attendee'];?></td>
                                <td><?php echo $val['Event'];?></td>
                                <td><?php echo $val['paid'];?></td>
                                <td align="center">
                                    <a href="eventmanagerediteventattendees.php?editIdAttendee=<?php echo $val['AttendeeID'];?>&editIdEvent=<?php echo $val['idevent'];?>" class="text-primary"><i class="fa fa-fw fa-edit"></i> Edit</a> | 
                                    <a href="eventmanagerdeleteeventattendees.php?editIdAttendee=<?php echo$val['AttendeeID'];?>&editIdEvent=<?php echo $val['idevent'];?>" class="text-danger" onClick="return confirm('Are you sure to delete this record?');"><i class="fa fa-fw fa-trash"></i> Delete</a>
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