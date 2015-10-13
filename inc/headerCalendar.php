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

</style>
<?php 
  $banner = $db->select('aetna_banner,aetna_client','aetna_banner.id=aetna_client.id and name="'.$client.'"');
?>

<nav class="navbar navbar-default navbar-fixed-top" style="border-left:1px solid #<?=$banner["bkColor"]?>;margin-left:-450px;left:50%;right:50%;width:900px;background-color:#<?=$banner["bkColor"]?>" role="navigation">
  <div class="container" style='height:<?=$banner["height"]?>'>
  	<a class="navbar-brand" style='padding:0px;height:<?=$banner["height"]?>px'  href="<?=$banner["logoLink"]?>"><img style ="height:100%" src='<?=$banner["logoImg"]?>'/></a>
  </div>
</nav>

<div style="height:<?=$banner['height']?>px"></div>
    <?php 
    if(isset($_SESSION['event_management_login'])) {
      echo "<a style='float:right;margin-right:100px' href='EventAdmin.php'>>>Admin View</a>";
    }
    ?>
