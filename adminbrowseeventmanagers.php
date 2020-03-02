<?php 
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();

    $name = "";
    if (isset($_POST['search'])){
        $name = $_POST['managername'];
    }
    
    function getMangersData($name, $dbh){
        $managerData = array();
        try{
            $stmt = $dbh->prepare(" SELECT a.idattendee as ManagerID, e.name as Event,
                                    e.idevent, a.name as EventManager
                                    FROM event as e
                                    INNER JOIN manager_event as m ON m.event = e.idevent
                                    INNER JOIN attendee as a ON a.idattendee = m.manager
                                    INNER JOIN role as r ON r.idrole = a.role
                                    WHERE r.name = 'event manager'
                                    and a.name like :name;");
            $name = "%$name%";
            $stmt->execute(array('name'=>$name));
            $managerData = $stmt->fetchAll();
            return $managerData;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $managerData;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Browse Managers</title>
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
                            <a class="my-0 mr-md-auto font-weight-normal" href="admin.php">BookMyEvent</a>
                        </h5>
                        <a href="adminaddeventmanagers.php" class="float-right btn btn-dark btn-sm">
                            <i class="fa fa-fw fa-plus-circle"></i> Add Events to Manager
                        </a>
                </div>

                <div class="card-body">
                    <div class="col-sm-12">
                        <h5 class="card-title"><i class="fa fa-fw fa-search"></i> Find Event Managers</h5>
                        <form action="adminbrowseeventmanagers.php" method="POST">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Manager's Name</label>
                                        <input type="text" name="managername" id="managername" class="form-control" value="" placeholder="Enter Event manager name">
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
                            <th>ManagerName</th>
                            <th>Event</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            $dbObj = new DB();
                            $managerData = getMangersData($name, $dbObj->getDBH());
                            if(count($managerData)>0){
                                foreach($managerData as $val){
                            ?>
                            <tr>
                                <td><?php echo $val['EventManager'];?></td>
                                <td><?php echo $val['Event'];?></td>
                                <td align="center">
                                    <a href="adminediteventmanagers.php?editIdManager=<?php echo $val['ManagerID'];?>&editIdEvent=<?php echo $val['idevent'];?>" class="text-primary"><i class="fa fa-fw fa-edit"></i> Edit</a> | 
                                    <a href="admindeleteeventmanagers.php?editIdManager=<?php echo$val['ManagerID'];?>&editIdEvent=<?php echo $val['idevent'];?>" class="text-danger" onClick="return confirm('Are you sure to delete this record?');"><i class="fa fa-fw fa-trash"></i> Delete</a>
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