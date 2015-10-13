<?php

session_start();
if(!isset($_SESSION['event_management_login'])) header("Location:index.php");

?>

<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/colpick.css" rel="stylesheet" media="screen">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="js/colpick.js"></script>


<style>
.colpick_full{
	z-index: 99;
}
.uploadform{
	padding:80px;
	background-color: rgba(255,255,255,0.7);
}
.image {
	cursor: pointer;
}
</style>
</head>
<body>
<?php include "inc/headerAdmin.php";?>
<?php include "inc/menu.php";?>

<div id="container" class='col-md-10'>


<div id = 'banner' class='section'>
	<?php
		include 'class/DB.php';
		$db = new DB();
		$db->connect();
		$banner = $db->select('aetna_banner',"id=".$_SESSION['event_management_login']);
	?>	
	<div class="form-horizontal" role="form">
	  <div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label">Background Color</label>
	    <div class="col-sm-10">
	      <input type="text" style='width:100px;background-color:#<?=$banner["bkColor"]?>' class="form-control colorpicker" id="bk-color" placeholder="Background Color" value="<?=$banner['bkColor']?>">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="inputPassword3"  class="col-sm-2 control-label">Banner Height</label>
	    <div class="col-sm-10">
		    <div class="input-group" style='width:100px'>
		      <input type="text" class="form-control" id="heightControl" placeholder="Height" value="<?=$banner['height']?>"> 
			  <span class="input-group-addon">Px</span>
			</div>
			<h4><small>minimum height is 50px.</small></h4>
		</div>
	    
	  </div>
	  <div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">Logo Image</label>
	    <div class="col-sm-10 image" style='border:1px solid;width:450px;min-height:150px;background-image:url(<?=$banner['logoImg']?>) ;background-repeat:no-repeat;background-size:100%;'>
	      

	      	<form class='hide uploadform' action="upload.php" method="POST" >
			  <input type="file" name="file"/>
			  <input type="submit" value="Upload"/>
			  <div><button class='close_f'>Cancel</button></div>
			  <div id="loader" style="display:none;">
			   <center><img src="load.gif" /></center>
			  </div>
			  <div id ="onsuccessmsg"></div>
			</form>
		
	    </div>
	    <h4><small>Double click image to change.</small></h4>
	    <a style='color:red' href="javascript:changeBack('<?=$banner['logoImg']?>')">Change Back</a>
	  </div>
	  <div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">Logo Hyperlink</label>
	    <div class="col-sm-10">
	      <input type="text" style='width:200px' class="form-control" id="link" placeholder="Link" value="<?=$banner['logoLink']?>"> 
	    </div>
	  </div>
	</div>
	<!--Demo-->
	<div style='width:900px'>
		<h3><b style='color:#75B2DD'>Demo</b></h3>
		<div style='border:1px solid'>
			<nav class="demo navbar navbar-default" style="border-radius: 0px;border-top: 0px;border-left:0;border-right:0;background-color:<?=$banner["bkColor"]?>"　role="navigation">
			  <div class="container" style='height:<?=$banner["height"]?>'>
			  	<a class="navbar-brand" style='padding:0;height:<?=$banner["height"]?>'  href="#"><img style ="height:100%" src='<?=$banner["logoImg"]?>'/></a>
			  </div>
			</nav>
		</div>
	</div>
	<!--End of Demo Div-->
	<button style ='position: fixed; bottom:50px;right:150px' class='color-submit btn btn-info'>Save Changes</button>

	<script>
	$('.close_f').click(function(){
		$(this).parent().parent().addClass('hide');
	});
	$('.image').dblclick(function(){
		if(!$(this).find('form').is(':visible')){
			//id = $(this).attr('data-target');
			$('.uploadform').removeClass('hide');
		}

	});
	$('.colorpicker').colpick({
		layout:'full',
		submit:0,
		colorScheme:'dark',
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			$(el).css('background-color','#'+hex);
			// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
			if(!bySetColor) $(el).val(hex);
			$('.demo').css('background-color','#'+hex);
		}
	}).keyup(function(){
		$(this).colpickSetColor(this.value);
	}).click(function(){$(this).colpickSetColor(this.value);});

	$('.color-submit').click(function(){
		if($('#heightControl').val()>=50){
			$.post("EventController.php",{
			command: "saveBanner",
			bkColor : $('#bk-color').val(),
			logoImage : $('.demo .navbar-brand img').attr('src'),
			logoLink:$('#link').val(),
			height:$('#heightControl').val()
			},function(){
				location.reload();
			});

		}

		
		
	});
	$('#heightControl').keyup(function(){
		if($(this).val()>=50){
			//$('.demo').css('height',$(this).val());
			$('.demo .container').css('height',$(this).val());
			$('.demo .navbar-brand').css('height',$(this).val());
			//$('.demo .nav li a').css('height',$(this).val()-5);
			//$('.demo .navbar-brand img').css('height',$(this).val()-5);
		}
		
	});

	function changeBack(path){
		$('.image').css('background-image','url('+path+')');
  		$('.demo .navbar-brand img').attr('src',path);
	}
	</script>

</div>
<script src="//code.jquery.com/jquery-latest.min.js"></script>
<script src="http://malsup.github.io/jquery.form.js"></script>




<script>
$(document).ready(function(){

 function onsuccess(response,status){
 	$("#onsuccessmsg").html('');
  $("#loader").hide();
  
  try {
    response = $.parseJSON(response);
  	
  	$('.image').css('background-image','url('+response.path+')');
  	$('.demo .navbar-brand img').attr('src',response.path);
  	$('.uploadform').addClass('hide');
  	return;

  }catch (e) {
  //response = JSON.parse(response);
  //alert(response.month);
  //$('.image_div_'+response.month).css('background-image','url('+response.path+')')
	  $("#onsuccessmsg").html('Error :<div>'+response+'</div>');
	 }
}

 $(".uploadform").on('submit',function(){
	$("#onsuccessmsg").html('');
	$("#loader").show();
	var options={
		url     : 'upload.php',
		success : onsuccess
	};
	$(this).ajaxSubmit(options);
	return false;
 });



});
</script>



</div>
</body>
</html>
