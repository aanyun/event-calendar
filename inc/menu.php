<div id = "menu" style='background-color:#75B2DD' class="col-md-2">
	<h3>Event Management</h3>
	<ul>
		<!-- <li><a href="#" class = 'togglePendingList'> Approve Pending Events </a></li> -->
		<li><a href='EventAdmin.php'> View/Edit Current Events </a></li>
		<li><a href='InsertEvent.php'> Insert Events </a></li>
		<li><a href="ThemeAdmin.php" data-target = 'color'> Change Monthly Theme </a></li>
		<li><a href="HeaderChange.php"> Customize Banner </a></li>
		<?php if($_SESSION['event_management_login'] ==1) {
			echo '<li><a href="AddClient.php"> Add New Client </a></li>'; 
			} 
		?> 
		
	<ul>
</div>
<script>
$(window).scroll(function(){
    $("#menu")
          .animate({"marginTop": ($(window).scrollTop()) + "px"}, 0 );
	});
	$('#menu').find('a').click(function(){
		$('.section').addClass('hide');
		id = $(this).attr('data-target');
		$('#'+id).removeClass('hide');
	});
</script>

<style>
  #menu li {
    color:#F26644;
  }
  #menu h3,a {
    color:white;
  }

</style>