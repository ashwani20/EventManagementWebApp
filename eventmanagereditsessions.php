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
    
    if(isset($_GET['editId'])){
        try{
            $stmt = $dbObj->getDBH()->prepare(" SELECT s.name, s.numberallowed, s.event, e.name as eventname,
                                                DATE_FORMAT(s.startdate, '%Y-%m-%dT%H:%i') as startdate, 
                                                DATE_FORMAT(s.enddate, '%Y-%m-%dT%H:%i') as enddate  
                                                FROM session as s 
                                                INNER JOIN event as e 
                                                ON s.event = e.idevent
                                                WHERE s.idsession = :id");
            $stmt->execute(array('id'=>$_GET['editId']));
            $data = $stmt->fetchALL();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Session</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <script type="text/javascript" src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <script>
            // $(document).ready(function(){
            //     $('#allevents').change(function(){
            //         var idevent = $(this).val();
            //         $.ajax({
            //             url: 'sessionsajaxfile.php',
            //             type: 'post',
            //             data: {request: 1, idevent: idevent},
            //             dataType: 'json',
            //             success: function(response){
            //                 // console.log(response);
            //                 $("#sessionstartdate").val(response['datestart']);
            //                 $("#sessionstartdate")[0].min = response['datestart'];

            //                 $("#sessionenddate").val(response['dateend']);
            //                 $("#sessionenddate")[0].max = response['dateend'];

            //                 $("#sessioncapacity").val(response['numberallowed']);
            //                 $("#sessioncapacity")[0].max = response['numberallowed'];
            //             }
            //         });
            //     });
            // });
        </script>
    </head>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="card-header"> 
                    <h5 class="my-0 mr-md-auto font-weight-normal" style="display:inline"> 
                        <a class="my-0 mr-md-auto font-weight-normal" href="eventmanager.php">BookMyEvent</a>
                    </h5>
                    
                    <a href="eventmanagerbrowsesessions.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Sessions</a>
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                        <form method="post">
                            <div class="form-group">
                                <label for = "eventname">Event<span class="text-danger">*</span></label>
                                <input type="text" name="eventname" id="eventname" class="form-control" value = "<?php echo $data[0]['eventname']; ?>" disabled required>
                            </div>
                            <div class="form-group">
                                <label>Session Name <span class="text-danger">*</span></label>
                                <input type="text" name="sessionname" id="sessionname" class="form-control" value = "<?php echo $data[0]['name']; ?>" placeholder="Enter session name" required>
                            </div>
                            <div class="form-group">
                                <label>Session Start Date<span class="text-danger">*</span></label>
                                <input type="datetime-local" name="sessionstartdate" id="sessionstartdate" class="form-control" value = "<?php echo $data[0]['startdate']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Session End Date<span class="text-danger">*</span></label>
                                <input type="datetime-local" name="sessionenddate" id="sessionenddate" class="form-control" value = "<?php echo $data[0]['enddate']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Session Capacity <span class="text-danger">*</span></label>
                                <input type="text" name="sessioncapacity" id="sessioncapacity" class="form-control" placeholder="Enter session capacity" value = "<?php echo $data[0]['numberallowed']; ?>" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Edit Session</button>
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
                'name' => $_POST['sessionname'],
                'numberallowed' => $_POST['sessioncapacity'], 
                'startdate' => $_POST['sessionstartdate'],
                'enddate' => $_POST['sessionenddate'],
                'idsession' => $_GET['editId']
            ];

            $sql = "UPDATE session 
                    SET name=:name, 
                    numberallowed=:numberallowed, 
                    startdate= :startdate, 
                    enddate= :enddate
                    WHERE idsession= :idsession";

            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            header("Location: eventmanagerbrowsesessions.php");
            ob_end_flush();
            die();
        }
    ?>
</html>