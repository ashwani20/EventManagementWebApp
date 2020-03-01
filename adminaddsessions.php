<?php
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    ob_start();
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();

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
        <title>Add Session</title>
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
                    
                    <a href="adminbrowsesessions.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Sessions</a>
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                        <form method="post">
                            <div class="form-group">
                                <label>Session Name <span class="text-danger">*</span></label>
                                <input type="text" name="sessionname" id="sessionname" class="form-control" placeholder="Enter session name" required>
                            </div>
                            <div class="form-group">
                                <label>Session Start Date<span class="text-danger">*</span></label>
                                <input type="datetime-local" name="sessionstartdate" id="sessionstartdate" class="form-control" value="<?php echo date('Y-m-d\TH:i');?>" required>
                            </div>
                            <div class="form-group">
                                <label>Session End Date<span class="text-danger">*</span></label>
                                <input type="datetime-local" name="sessionenddate" id="sessionenddate" class="form-control" value="<?php echo date('Y-m-d\TH:i');?>" required>
                            </div>
                            <div class="form-group">
                                <label>Session Capacity <span class="text-danger">*</span></label>
                                <input type="text" name="sessioncapacity" id="sessioncapacity" class="form-control" placeholder="Enter session capacity" required>
                            </div>
                            <div class="form-group">
                                <label for = "sessionvenue">Event List<span class="text-danger">*</span></label>
                                <select name="sessionvenue" id="sessionvenue" class="form-control" required> 
                                    <option value="">Select a venue</option>
                                    <?php
                                        $venues = getAllVenues($dbObj->getDBH());
                                        if(count($venues)>0){
                                            foreach($venues as $val){
                                        ?>
                                            <option value= <?php echo $val['idvenue'];?>><?php echo $val['name'];?></option>    
                                        <?php
                                            }
                                        } 
                                        ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Add Session</button>
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
                'datestart' => $_POST['sessionstartdate'],
                'dateend' => $_POST['sessionenddate'],
                'event' => $_POST['eventvenue']
            ];

            $sql = "INSERT INTO event (name, datestart, dateend, numberallowed, venue) VALUES (:name, :datestart, :dateend, :numberallowed, :venue)";

            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            header("Location: adminbrowseevents.php");
            ob_end_flush();
            die();
        }
    ?>
</html>