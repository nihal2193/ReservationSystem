<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">IRCTC</a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Settings</a></li>
        <li><a href="#">Profile</a></li>
        <li><a href="#">Help</a></li>
        <?php
          if(isset($_SESSION))
          {
            echo '<li><a href="logout.php">Logout</a></li>';
          }
        ?>
      </ul>
      <form action="result.php" method="get" class="navbar-form navbar-left">
          <input type="text" class="form-control" name="src" id="src" placeholer="source" required autofocus >
          <input type="text" class="form-control" name="dst" id="dst" placeholer="destination" required>
          <input type="date" class="form-control" name="date" placeholder="date" required >
          <button class="btn btn-sm btn-primary btn-default" type="submit" style="margin-top:2px">Search</button>
        </form>
    
<?php
    //-------------------------------login form-------------------------------------------  
       
       /* if(!isset($_SESSION))
        {
          echo '
          <form action="verify.php" method="post" class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Username" name="username" required autofocus>
            <input type="password" class="form-control" placeholder="Password" name="password" required></br>
            <input type="checkbox" value="remember-me" color="white">&nbsp Keep me logged in &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            <a href="#" >foreget password</a>&nbsp
          <button class="btn btn-sm btn-primary btn-default" type="submit" style="margin-top:2px">Sign in</button>
          </form>
          ';
        }
        else
        {
          //echo '
          //<form class="navbar-form navbar-right">
            //<input type="text" class="form-control" placeholder="Search...">
          //</form>
          //';
        }*/
      ?>
    </div>
  </div>
</div>