<?php
    session_start();
    
    if (!isset($_SESSION['eventmanager'])){
        header("Location: login.php");
        die();
    } 
    ob_start();
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();
    $data = array();
    
    include_once 'sanitizedatafile.php';
    if(isset($_GET['editIdEvent']) && !isValidNumber($_GET['editIdEvent'])){
        header('location: eventmanagerbrowseevents.php');
    }

    if(isset($_GET['editIdEvent'])){
        try{
            $stmt = $dbObj->getDBH()->prepare(" SELECT name,
                                                DATE_FORMAT(datestart, '%Y-%m-%dT%H:%i') as datestart, 
                                                DATE_FORMAT(dateend, '%Y-%m-%dT%H:%i') as dateend, 
                                                numberallowed, venue 
                                                FROM event e
                                                INNER JOIN manager_event as m ON m.event = e.idevent
                                                WHERE manager = :manager
                                                AND idevent = :idevent");
            $stmt->execute(array('idevent'=>$_GET['editIdEvent'], 'manager'=>$_SESSION['idattendee']));
            $data = $stmt->fetchALL();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    

    function getAllVenues($dbh){
        $venues = array();
        try{
            $stmt = $dbh->prepare(" SELECT idvenue, name 
                                    FROM venue;");
            $stmt->execute();
            $venues = $stmt->fetchAll();
            return $venues;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $venues;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Event</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <script type="text/javascript" src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
        <script>
            $(document).ready(function() {
                $('#submit').click(function(e){
                    e.preventDefault();
                    var name = $("#eventname").val();
                    var eventstartdate = $("#eventstartdate").val();
                    var eventenddate = $("#eventenddate").val();
                    var eventcapacity = $("#eventcapacity").val();
                    var eventvenue = $("#eventvenue").val();
                    var id = getUrlVars()['editIdEvent'];
                    // console.log(name, eventstartdate, eventenddate);
                    $.ajax({
                        type: "POST",
                        url: "ajaxeventfile.php",
                        dataType: "json",
                        data: {request:'updateManager', name:name, eventstartdate:eventstartdate, 
                        eventenddate:eventenddate, eventcapacity:eventcapacity, 
                        eventvenue:eventvenue, id:id},
                        success : function(data){
                            console.log(data);
                            if (data['code'] == "200"){
                                window.location.href = data['location'];
                            }
                            else if (data['code'] == "404"){
                                if ($("#errorDiv")){
                                    $("#errorDiv").remove();
                                }
                                $("form").prepend(data['msg']);
                            } 
                        }
                    });
                });
            });

            function getUrlVars() {
                var vars = {};
                var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    vars[key] = value;
                });
                return vars;
            }

        </script>
    </head>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="card-header"> 
                    <h5 class="my-0 mr-md-auto font-weight-normal" style="display:inline"> 
                        <a class="my-0 mr-md-auto font-weight-normal" href="admin.php">BookMyEvent</a>
                    </h5>
                    
                    <a href="eventmanagerbrowseevents.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Events</a>
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                        <form method="post">
                            <div class="form-group">
                                <label>Event Name <span class="text-danger">*</span></label>
                                <input type="text" name="eventname" id="eventname" class="form-control" value = "<?php echo $data[0]['name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Event Start Date<span class="text-danger">*</span></label>
                                <input type="datetime-local" name="eventstartdate" id="eventstartdate" class="form-control" value = "<?php echo $data[0]['datestart']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Event End Date<span class="text-danger">*</span></label>
                                <input type="datetime-local" name="eventenddate" id="eventenddate" class="form-control" value = "<?php echo $data[0]['dateend']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Event Capacity <span class="text-danger">*</span></label>
                                <input type="number" name="eventcapacity" id="eventcapacity" class="form-control" value = "<?php echo $data[0]['numberallowed']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for = "eventvenue">Event Venues<span class="text-danger">*</span></label>
                                <select name="eventvenue" id="eventvenue" class="form-control" required> 
                                    <?php
                                        $venues = getAllVenues($dbObj->getDBH());
                                        if(count($venues)>0){
                                            foreach($venues as $val){
                                            if ($data[0]['venue'] == $val['idvenue']){
                                        ?>
                                                <option value= <?php echo $val['idvenue'];?> selected><?php echo $val['name'];?></option>
                                        <?php
                                            } else {
                                        ?>
                                            <option value= <?php echo $val['idvenue'];?>>
                                                <?php echo $val['name'];?>
                                            </option>    
                                        <?php
                                            }
                                        } 
                                    }?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Edit Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </body>
</html>