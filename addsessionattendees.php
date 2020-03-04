<?php
    session_start();
    ob_start();
    if (!isset($_SESSION['attendee'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();
    
    function getAllEvents($dbh){
        $events = array();
        try{
            $stmt = $dbh->prepare(" SELECT idevent, name
                                    FROM event as e 
                                    INNER JOIN attendee_event as m ON m.event = e.idevent
                                    WHERE m.attendee = :attendee;");
            $stmt->execute(array('attendee'=> $_SESSION['idattendee']));
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
        <title>Add Attendees to Sessions</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

        <script type="text/javascript" src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
        <script>
            $(document).ready(function(){
                $('#allevents').change(function(){
                    $('#session').find('option').remove();
                    var idevent = $(this).val();
                    $.ajax({
                        url: 'sessionsajaxfile.php',
                        type: 'post',
                        data: {request: 2, idevent: idevent},
                        dataType: 'json',
                        success: function(response){
                            var len = response.length;
                            // console.log(response);
                            if (len>0){
                                $("#session").append("<option value=''>Please select a session</option>");
                                for(var i = 0; i<len; i++){
                                    var idsession = response[i]['idsession'];
                                    var name = response[i]['name'];
                                    $("#session").append("<option value='"+idsession+"'>"+name+"</option>");
                                } 
                            } else {
                                $("#session").append("<option value=''>No session for this event</option>");
                            }
                        }
                    });
                });
            });
        </script>

    </head>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="card-header"> 
                    <h5 class="my-0 mr-md-auto font-weight-normal" style="display:inline"> 
                        <a class="my-0 mr-md-auto font-weight-normal" href="attendee.php">BookMyEvent</a>
                    </h5>
                    
                    <a href="regattendsession.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Sessions</a>
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                        <form method="post">
                            <div class="form-group">
                                <label for = "allevents" >Event name<span class="text-danger">*</span></label>
                                <select name="allevents" id="allevents" class="form-control" required> 
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
                                <label for = "session" >Session name<span class="text-danger">*</span></label>
                                <select name="session" id="session" class="form-control" required> 
                                    <option value="">Select a session</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i>Add Session to Attendee</button>
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
                'attendee' => $_SESSION['idattendee']
            ];

            $sql = "INSERT INTO attendee_session (session, attendee) VALUES (:session, :attendee)";
            $stmt= $dbObj->getDBH()->prepare($sql);
            var_dump($data);
            $stmt->execute($data);
            
            header("Location: regattendsession.php");
            ob_end_flush();
            die();
        }
    ?>
</html>