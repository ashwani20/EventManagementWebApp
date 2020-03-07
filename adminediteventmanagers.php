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

    include_once 'sanitizedatafile.php';
    if(isset($_GET['editIdManager']) && !isValidNumber($_GET['editIdManager'])){
        header('location: adminbrowseeventmanagers.php');
    }
    
    if(isset($_GET['editIdManager'])){
        try{
            $stmt = $dbObj->getDBH()->prepare(" SELECT name FROM attendee 
                                                WHERE idattendee = :idattendee;");
            $stmt->execute(array('idattendee'=>$_GET['editIdManager']));
            $data = $stmt->fetchALL();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Manager's Events</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <script type="text/javascript" src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
        <script>
            $(document).ready(function() {
                $('#submit').click(function(e){
                    e.preventDefault();
                    var event = $('#event').val();
                    var manager = getUrlVars()['editIdManager'];
                    console.log(event, manager);
                    $.ajax({
                        type: "POST",
                        url: "ajaxeventmanagerfile.php",
                        dataType: "json",
                        data: {request:'update', event:event, manager:manager},
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

                function getUrlVars() {
                    var vars = {};
                    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                        vars[key] = value;
                    });
                    return vars;
                }
            });
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
                    
                    <a href="adminbrowseeventmanagers.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Events and Managers</a>
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                        <form method="post">
                            <div class="form-group">    
                                <label for = "manager" >Manager name<span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" value = "<?php echo $data[0]['name']; ?>" class="form-control" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <label for = "event" >Event name<span class="text-danger">*</span></label>
                                <select name="event" id="event" class="form-control" required> 
                                    <option value="">Select an event</option>
                                    <?php
                                        $events = getAllEvents($dbObj->getDBH());
                                        if(count($events)>0){
                                            foreach($events as $val){
                                                if ($_GET['editIdEvent'] == $val['idevent']){
                                        ?>
                                            <option value= <?php echo $val['idevent'];?> selected><?php echo $val['name'];?></option>    
                                        <?php
                                            } else {
                                        ?>
                                            <option value= <?php echo $val['idevent'];?> ><?php echo $val['name'];?></option>    
                                        <?php }
                                            }
                                        } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Edit Manager's Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </body>
</html>