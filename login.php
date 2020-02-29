<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.6">
    <title>Login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.4/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="https://getbootstrap.com/docs/4.4/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="https://getbootstrap.com/docs/4.4/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="https://getbootstrap.com/docs/4.4/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="https://getbootstrap.com/docs/4.4/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="https://getbootstrap.com/docs/4.4/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="https://getbootstrap.com/docs/4.4/assets/img/favicons/favicon.ico">
    <meta name="msapplication-config" content="/docs/4.4/assets/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/4.4/examples/sign-in/signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
    <form class="form-signin" action="login.php" method="POST">
        <img class="mb-4" src="https://getbootstrap.com/docs/4.4/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <label for="inputName" class="sr-only">Username</label>
        <input type="text" id="inputName" name="name" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name = "password" class="form-control" placeholder="Password" required>
        <input type="submit" value="Login" class="btn btn-lg btn-primary btn-block" />
    </form>
    <?php
        include_once 'classes/PDO.DB.class.php';
        
        if (isset($_POST['name']) && isset($_POST['password'])){
            // echo "heree";
            function getRoleType($name, $pwd, $dbh) {
                try{
                    $data = array();
                    $stmt = $dbh->prepare("SELECT r.name 
                                        FROM role as r INNER JOIN attendee as a 
                                        ON r.idrole = a.role 
                                        WHERE a.name = :name
                                        AND a.password = :pwd ;");
                    $stmt->execute(array('name'=>$name, 'pwd'=>$pwd));
        
                    $data = $stmt->fetch();
                    if (count($data) > 0) {
                        return $data['name'];
                    } 
                    return "";
        
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    return "";
                }
            }
        
            function connectToDB(){ 
                $name = $_POST['name'];
                $pwd = $_POST['password'];
                
                $dbObj = new DB();
                $name = $_POST['name'];
                $roleType = getRoleType($name, $pwd, $dbObj->getDBH());

                if (empty($roleType)){
                    echo "Record not found";
                }
                else {
                    // manageUserSession($name);
                    session_start();
                    $_SESSION[$name] = true;
                    switch($roleType){
                        case "admin":
                            header("Location: admin.php");
                            break;
                        case "attendee":
                            echo "redirect to attendee page";
                            break;
                        case "event manager":
                            echo "redirect to event manager page";
                            break;
                        case "superadmin":
                            header("Location: superadmin.php");
                            break;
                        default:
                            echo "Record not found";
                    }
                }
                
            }
        
            // function manageUserSession($name){
                
            //     // Using use_strict_mode.
            //     ini_set('session.use_strict_mode', 1);
            //     my_session_start();

            //     // Session ID must be regenerated when
            //     my_session_regenerate_id($name);
                
            // }
        
            // // My session start function support timestamp management
            // function my_session_start() {
            //     session_start();
            //     // Do not allow to use too old session ID
            //     if (!empty($_SESSION['deleted_time']) && $_SESSION['deleted_time'] < time() - 180) {
            //         // session_destroy();
            //         session_unset();
            //         session_start();
            //     }
            // }
        
            // // My session regenerate id function
            // function my_session_regenerate_id($name) {
            //     // Call session_create_id() while session is active to 
            //     // make sure collision free.
            //     if (session_status() != PHP_SESSION_ACTIVE) {
            //         session_start();
            //     }
            //     // WARNING: Never use confidential strings for prefix!
            //     $newid = session_create_id($name . '-');

            //     // Set deleted timestamp. Session data must not be deleted immediately for reasons.
            //     $_SESSION['deleted_time'] = time();
            //     var_dump($_SESSION['deleted_time']);
            //     header("Location: admin.php?id={$_SESSION['deleted_time']}");
            //     // Finish session
            //     session_commit();
            //     // Make sure to accept user defined session ID
            //     // NOTE: You must enable use_strict_mode for normal operations.
            //     ini_set('session.use_strict_mode', 0);
            //     // Set new custom session ID
            //     session_id($newid);
            //     // Start with custom session ID
            //     session_start();
            // }

            connectToDB();
        }
    ?>
</body>
</html>
