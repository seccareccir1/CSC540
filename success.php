<?php 
session_start();
if($_SESSION["ayn"] == 1){
  require_once "menuAdmin.php";
}else{
  require_once "menuReadOnly.php";
}
?>
<div class="jumbotron text-xs-center">
  <h1 class="display-3">Thank You!</h1>
  <p class="lead"><strong>You will be redirected shortly...</strong></p>
  <hr>
  <p>
    Having trouble? <a href="">Contact us</a>
  </p>
  <p class="lead">
    <a class="btn btn-primary btn-sm" href="welcome.php" role="button">Continue to homepage</a>
  </p>
</div>
<?php header( "refresh:5;url=welcome.php" ); ?>