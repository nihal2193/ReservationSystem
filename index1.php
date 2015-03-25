<?php
include('stations.php');
//print_r($arr);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <title>Landin Page of Project</title>
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="signin.css" rel="stylesheet">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script type="text/javascript">
$(function(){
   $("#myAlert").bind('closed.bs.alert', function () {
      alert("Alert message box is closed.");
   });
});
 $(".alert").alert();
</script>
    <script type="text/javascript">
      var jArray= <?php echo json_encode($arr); ?>;
      var availableTags =jArray;
      $(function() {
        $( "#src" ).autocomplete({
          source: availableTags
        });
      });
      $(function() {
        $( "#dst" ).autocomplete({
          source: availableTags
        });
      });
    </script>
  </head>
  <body>
    <?php
        include('navbar.php');
     ?>    
    <div class="container">
      
      <form action="verify.php" method="post" class="form-signin" role="form" >
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" class="form-control" placeholder="Username" name="username" required autofocus>
        <input type="password" class="form-control" placeholder="Password" name="password" required>
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>
      <div class="signup">
        <form action="signup.php" method="post" class="form-signup" role="form" >
          <h2 class="form-signin-heading">Please sign up</h2>
          <input type="text" class="form-control" placeholder="Choose unique username..." name="username" required autofocus>
          <!--<input type="text" class="form-control" placeholder="Your Name" name="name" required>-->
          <input type="password" class="form-control" placeholder="Password" name="password" required>
          <input type="email" class="form-control" placeholder="Email" name="email" required>
          <input type="text" class="form-control" placeholder="Mobile Number" name="contactnum" required>
          <textarea name="address" placeholder="Address..." rows="4" cols="46"></textarea>
          <select class="form-control" name="gender">
            <option  value="M">Male</option>
            <option value="F">Female</option>
            <option value="O">Others</option>
          </select>
          <input type="date" class="form-control" placeholder="D.O.B." name="dob" required>
          <input type="text" class="form-control" placeholder="Hint for password" name="hint" required>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Sign Up</button>
        </form>
        <?php
        if(isset($_REQUEST['message'])){
          if($_REQUEST['message'] !="")
          {
            if($_REQUEST['message'] == "success")
            {
              echo '<div class="alert alert-success" data-dismiss="alert">
                 <a href="#" class="close" data-dismiss="alert">&times;</a>
                  Your account created successfully!</div>';
            }
            else
            {
              echo '<div class="alert alert-danger" data-dismiss="alert">
                  <a href="#" class="close" data-dismiss="alert">&times;</a>
                  Sorry! Your account could not created successfully.</div>';
            }
          }  
        }
        ?> 
</div>
    </div> <!-- /container -->
  </body>
</html>