<style>
  .navbar-default {
    background-color: #75B2DD;
    border-color: #F26644;
    border-bottom-width: 5px;
  }
  .container{
    width:100%;
  }
  .navbar-brand{
    background-color: white;
  }
  #container{
    padding-top: 20px;
  }
  #menu li {
    color:#F26644;
  }
  #menu h3,a {
    color:white;
  }
</style>
<?php
  $home = "index.php";
  if ($_SESSION['event_management_manager'] != 'Admin') $home = $_SESSION['event_management_manager'];
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
  	<a class="navbar-brand" href="<?php echo $home?>"><img style ="height:25px" src='img/logo.png'/></a>
    <p class="navbar-text" style='color:white'>Welcome, admin! </p>
    <ul class="nav navbar-nav navbar-right" style='color:white'>
    <li><a href="javascript:logout()" style='color:white'>log out</a></li>
    <ul>
  </div>
</nav>
<div style="height:55px"></div>

<script>
  function logout() {
    $.post("EventController.php",{
    command : "logout"
    },function(){
      location.href="index.php";
    });
  
  }
</script>