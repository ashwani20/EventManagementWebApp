<?php
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Venue</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

        <script type="text/javascript" src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
        <script>
            $(document).ready(function() {
                $('#submit').click(function(e){
                    e.preventDefault();
                    var name = $("#venuename").val();
                    var venuecapacity = $("#venuecapacity").val();
                    
                    $.ajax({
                        type: "POST",
                        url: "ajaxvenuefile.php",
                        dataType: "json",
                        data: {request:'create', name:name, venuecapacity:venuecapacity},
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
                    
                    <a href="adminbrowsevenues.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Venues</a>
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                        <form method="post">
                            <div class="form-group">
                                <label>Venue Name <span class="text-danger">*</span></label>
                                <input type="text" name="venuename" id="venuename" class="form-control" placeholder="Enter venue name" required>
                            </div>
                            <div class="form-group">
                                <label>Venue Capacity <span class="text-danger">*</span></label>
                                <input type="number" name="venuecapacity" id="venuecapacity" class="form-control" placeholder="Enter venue capacity" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Add Venue</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </body>
</html>