<?php
session_start();
//print_r($_SESSION);
include('stations.php');
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
    <title>Home Page of DB Project</title>
    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
   <link rel="stylesheet" href="css/jquery-ui.css">
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script type="text/javascript">
        var jArray= <?php echo json_encode($arr); ?>;
        $(function() {
        var availableTags =jArray;
        $( "#src" ).autocomplete({
          source: availableTags
        });
        });
        $(function() {
        var availableTags =jArray;
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
  </body>
</html>