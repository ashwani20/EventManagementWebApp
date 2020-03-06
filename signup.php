<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.6">
    <title>Sign Up</title>

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
    <link rel="stylesheet" src="css/login.css">
    <link href="https://getbootstrap.com/docs/4.4/examples/sign-in/signin.css" rel="stylesheet">
    <script type="text/javascript" src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
    <script>
        $(document).ready(function() {
            $('#submit').click(function(e){
                e.preventDefault();
                var name = $("#inputName").val();
                var password = $("#inputPassword").val();
                console.log(name, password);
                $.ajax({
                    type: "POST",
                    url: "loginajaxfile.php",
                    dataType: "json",
                    data: {request:'signup', name:name, password:password},
                    success : function(data){
                        console.log(data);
                        if (data['code'] == "200"){
                            window.location.href = data['location'];
                        }
                        else if (data['code'] == "404"){
                            console.log('asasas', data);
                            if ($("#errorDiv")){
                                $("#errorDiv").remove();
                            }
                            // $( data['tag'] ).after('');
                            $( "form").prepend( data['msg'] );
                        } 
                    }
                });
            });
        });
    </script>

  </head>
  <body class="text-center">
    <form class="form-signin">
        <img class="mb-4" src="https://getbootstrap.com/docs/4.4/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Please sign up</h1>
        <label for="inputName" class="sr-only">Username</label>
        <input type="text" id="inputName" name="name" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name = "password" class="form-control" placeholder="Password" required>
        <input type="submit" name="submit" id="submit" value="Signup" class="btn btn-lg btn-primary btn-block" />
    </form>
    
   </body>
</html>
