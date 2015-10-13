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
.table-responsive{
	position: relative;
	padding: 15px 15px 15px;
	margin: 0px -15px 15px;
	margin-left: 0px;
	margin-right: 0px;
	background-color: #FFF;
	border-width: 1px;
	border-color: #DDD;
	border-radius: 4px 4px 0px 0px;
	box-shadow: none;
}

  .uploadform{
  	padding:80px;
  	background-color: rgba(255,255,255,0.7);
  }
  .month_image:hover{
  	cursor: pointer;
  }

</style>
</head>
<body>
<?php include "inc/headerAdmin.php";?>
<?php include "inc/menu.php";?>

<div id="container" class='col-md-10'>

<h4>
	<small>
		<li>Please Click 'Save Changes' button to save your changes.</li>
	</small>
</h4>
<div id = 'color' class='section'>
	<?php
		include 'class/DB.php';
		$db = new DB();
		$db->connect();
		$colors = $db->select('aetna_month_color',"client_id=".$_SESSION['event_management_login']." order by id");
		
		foreach($colors as $color){
			if(is_array($color)){
			echo "<div class='row' style='margin:5px'><label class='col-sm-2 control-label'>".$color['month']."</label>"; 
			echo "<div class='col-sm-10' >";
			echo "<input id='".$color['mid']."' type='text' class='col-sm-3 colorpicker form-control' style='width:100px;background-color:#".$color['color']."' value = '".$color['color']."'>"; 
			echo "<div data-target='".$color['mid']."' class='col-sm-6 month_image image_div_".$color['mid']."' style='background-image:url(\"".$color['img_path']."\") ;background-repeat:no-repeat;background-size:450px;height:200px;width:450px'>";
			
			?>
			
			 <form class='hide uploadform img_<?php echo $color['mid']?>' action="upload.php" method="POST" >
			  <input type="file" name="file"/>
			  <input type="text" class='hide' name="month" value="<?php echo $color['mid']?>"/>
			  <input type="submit" value="Upload"/>
			  <div><button class='close_f'>Cancel</button></div>
			  <div id="loader" style="display:none;">
			   <center><img src="load.gif" /></center>
			  </div>
			  <div id ="onsuccessmsg"></div>
			 </form>
			
			<?php
			echo "</div>"; 
			echo "<a class ='hide changeBackIcon' style='color:red' href='javascript:changeBack(".$color['mid'].",\"".$color['img_path']."\")'><img style='width:20px' src='img/cancel.png'/>Change Back</a>";
			echo "</div>";
			echo "</div>";
			
			}
		}
	?>

	<button style ='position: fixed; bottom:50px;right:150px' class='color-submit btn btn-info'>Save Changes</button>

	<script>
	$('.close_f').click(function(){
		$(this).parent().parent().addClass('hide');
	});
	$('.month_image').dblclick(function(){
		if(!$(this).find('form').is(':visible')){
			id = $(this).attr('data-target');
			$('.img_'+id).removeClass('hide');
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
		}
	}).keyup(function(){
		$(this).colpickSetColor(this.value);
	}).click(function(){$(this).colpickSetColor(this.value);});

	function changeBack(month,path){

  	$('.image_div_'+month).css('background-image','url('+path+')');
  	$('.image_div_'+month).parent().find('.changeBackIcon').addClass('hide');
  	//$('.img_'+response.month).addClass('hide');
	}
	$('.color-submit').click(function(){
		var color= new Array();
		var image = new Array();
		for (var i = 1;i<13;i++) {
			color.push($('#'+i).val());
			image.push($('.image_div_'+i).css('background-image').replace('url(','').replace(')',''));
		}
		
		$.post("EventController.php",{
		command: "saveColor",
		data : color,
		image : image
		},function(){
			//$.each($('#eventForm input'),function(i,j){
			//$(this).val("");
			// $('#m_list').find(':checked').each(function() {
			//    $(this).removeAttr('checked');
			// });
		});
		//location.reload();
		
	});

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
  //alert(response.month);
  	$('.image_div_'+response.month).css('background-image','url('+response.path+')').parent().find('.changeBackIcon').removeClass('hide');
  	$('.img_'+response.month).addClass('hide');
  	//$('.changeBackIcon').removeClass('hide');
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
