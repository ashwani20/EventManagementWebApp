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
        include_once 'sanitizedatafile.php';
        $name = santizeText($_POST['venuename']);
    }
    
    function getVenueData($name, $dbh){
        $venueData = array();
        try{
            $stmt = $dbh->prepare("SELECT * FROM venue
                                    WHERE name like :name;");
           
            $name = "%$name%";
            $stmt->execute(array('name'=>$name));
            $venueData = $stmt->fetchAll();
            return $venueData;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $venueData;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Browse Venues</title>
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
                        <a href="adminaddvenues.php" class="float-right btn btn-dark btn-sm">
                            <i class="fa fa-fw fa-plus-circle"></i> Add Venues
                        </a>
                </div>

                <div class="card-body">
                    <div class="col-sm-12">
                        <h5 class="card-title"><i class="fa fa-fw fa-search"></i> Find User</h5>
                        <form action="adminbrowsevenues.php" method="POST">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Venue Name</label>
                                        <input type="text" name="venuename" id="venuename" class="form-control" value="" placeholder="Enter venue name">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <input type="submit" name="search" value="search venue" id="search" class="btn btn-primary"><i class="fa fa-fw fa-search"></i></input>
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
                            <th>Venue ID</th>
                            <th>Venue Name</th>
                            <th>Venue Capacity</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $dbObj = new DB();
                            $venueData = getVenueData($name, $dbObj->getDBH());
                            if(count($venueData)>0){
                                foreach($venueData as $val){
                            ?>
                            <tr>
                                <td><?php echo $val['idvenue'];?></td>
                                <td><?php echo $val['name'];?></td>
                                <td><?php echo $val['capacity'];?></td>
                                <td align="center">
                                    <a href="admineditvenues.php?editId=<?php echo $val['idvenue'];?>" class="text-primary"><i class="fa fa-fw fa-edit"></i> Edit</a> | 
                                    <a href="admindeletevenues.php?delId=<?php echo$val['idvenue'];?>" class="text-danger" onClick="return confirm('Are you sure to delete this venue?');"><i class="fa fa-fw fa-trash"></i> Delete</a>
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