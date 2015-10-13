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
	<script src="//code.jquery.com/jquery-latest.min.js"></script>
	<script src="http://malsup.github.io/jquery.form.js"></script>
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="js/colpick.js"></script>
	<script src="js/jquery.tablesorter.js"></script>
	<script>
	$(function() {
	if($(location).attr('hash')=="#eventForm") {
		$('.section').addClass('hide');
		$('#eventForm').removeClass('hide');
	}
	date = new Date();
	
	$( "#inputSDate" ).datepicker({dateFormat: 'yy-mm-dd',minDate: date, 
	onSelect: function(){
	$(this).parent().removeClass("has-error"); 
	if($('#inputEDate').val()!=""&&$('#inputSDate').val()>$('#inputEDate').val()) {
		$(this).val("");
		$(this).parent().addClass('has-error');
		alert("Please ensure that the End Date is greater than or equal to the Start Date.");
		}}});
	$( "#inputEDate" ).datepicker({dateFormat: 'yy-mm-dd',minDate: date, 
	onSelect: function(){
	$(this).parent().removeClass("has-error");		
	if($('#inputEDate').val()<$('#inputSDate').val()) {
		$(this).val("");
		$(this).parent().addClass('has-error');
		alert("Please ensure that the End Date is greater than or equal to the Start Date.");
		}}});
		
	$('.date').find('textarea').datepicker({dateFormat: 'yy-mm-dd',minDate: date});
	});

	</script>

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

table.tablesorter thead tr .header:not(:last-child) {
	background-image: url(img/bg.gif);
	background-repeat: no-repeat;
	background-position: center right;
	cursor: pointer;
}



#eventForm {
	width:70%;
	padding: 10px;
	border:1px solid #DDD;
	border-radius:5px;
}

.navbar-brand{
	background-color: white;
}
#eventTable tbody td:hover{
	cursor: pointer; 
	cursor: hand;
}

</style>
</head>
<body>
<?php include "inc/headerAdmin.php";?>
<?php include "inc/menu.php";?>

<div id="container" class='col-md-10'>



	<?php
		include 'class/DB.php';
		$db = new DB();
		$db->connect();
	?>
	


<!--Create New Event Form-->
<div id="eventForm" class="section form-horizontal" style="padding:20px">
  <div class='error' id='error_message' ></div>
  <div class = 'row' style="padding:20px">
   <div class="col-xs-6">
	  <div class="form-group">
		<label for="inputTitle" class="col-sm-3 control-label">*Title</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" name ="Title" id="inputTitle" placeholder="Title">
		</div>
	  </div>
	  <div class="form-group">
		<label for="inputLocation" class="col-sm-3 control-label">Short Description</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" name = "Location" id="inputDescription" placeholder="Description">
		</div>
	  </div>
	  <div class="form-group">
		<label for="inputLink" class="col-sm-3 control-label">Link</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" name = "Link" id="inputLink" placeholder="Link">
		</div>
	  </div>
<!-- 	  <div class="form-group">
		<label for="inputCategory" class="col-sm-3 control-label">Category</label>
		<div id="category" class="col-sm-9">
			<input type="checkbox" name = "service" id="inputService"> Service Training <br>
			<input type="checkbox" name = "sales" id="inputSales"> Sales Training
		</div>
	  </div> -->

<!--   <div class="form-group">
    <label for="inputManufacturer" class="col-sm-3 control-label">Manufacturer</label>
    <div class="col-sm-9">
	<div id="m_list">
	
	</div>
    </div>
  </div> -->
  <div class="form-group">
    <label for="inputSDate" class="col-sm-3 control-label">*Start Date</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" name = "Start_Date" id="inputSDate" placeholder="yyyy-mm-dd">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEDate" class="col-sm-3 control-label">End Date</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" name = "End_Date" id="inputEDate" placeholder="yyyy-mm-dd">
    </div>
  </div>
  </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-10 col-sm-2">
      <button onclick="submit()" class="btn btn-success">Insert</button>
    </div>
  </div>
</div>
<!--End of Create New Event Form-->



<script>
	$(document).ready(function() 
	    { 
	        $("#events").tablesorter(); 
	    } 
	); 


	$('#eventForm input').blur(function()
	{	
		if( !$(this).val() ) {
			 $(this).parent().addClass('has-error');
		}else {
			$(this).parent().removeClass('has-error');
		}
		
	});
	

	$('.delete').click(function() {
		var id = $(this).parent().parent().attr("id");
		$.post("EventController.php",{
		command : "delete",
		id : id
		},function(){
		location.reload();
		});
	});
	
	$('.approve').click(function() {
		var id = $(this).parent().parent().attr("id");
		$.post("EventController.php",{
		command : "saveChange",
		id: id,
		field : "Pending",
		value : 0
		},function(){
		location.reload();
		});
	});
	function submit() {

		title = $('#inputTitle').val();
		description = $('#inputDescription').val();
		link = $('#inputLink').val();
		client = "<?=$_SESSION['event_management_login']?>";
		Start_Date = $('#inputSDate').val();
		End_Date = $('#inputEDate').val();
		
		if(title == ""||link == ""|| Start_Date == "" ) {
		alert("Please fill in the required fields");
		return;
		}
		
		link = addHttp(link);


		$.post("EventController.php",{
		command: "save",
		title : title,
		url : link,
		start : Start_Date,
		end : End_Date,
		client: client,
		description: description
		},function(){
			$.each($('#eventForm input'),function(i,j){
			$(this).val("");
			// $('#m_list').find(':checked').each(function() {
			//    $(this).removeAttr('checked');
			// });
		});
		location.reload();
		});

		

		function addHttp(url) {
		   if (!/^(f|ht)tps?:\/\//i.test(url)) {
		      url = "http://" + url;
		   }
		   return url;
		}


		
	}
	
	var orgVal="";
	$('#eventTable tbody td').dblclick(   //double click text to show edit box
		function(){
		if($(this).html().indexOf("<button")>=0) return;   // if click area is not text
		if($(this).html().indexOf("<textarea")>=0||$(this).html().indexOf("<input")>=0) { //already open, close edit box
			var val = ($(this).html().indexOf("textarea")>=0) ? $(this).find("textarea").html() : $(this).find("input").val();
			$(this).html(val);
		}
		else {
			var val = $(this).html();
			orgVal = val;  //store original data;
			//change to edit box
			if($(this).attr('field') == "start" || $(this).attr('field') == "end" ) {  // open datepicker if click date area
				$(this).html("<input class ='date' val="+val+"></input>");
						
						$('.date').datepicker({ dateFormat: 'yy-mm-dd',
												onSelect:function(date) { $(this).find("input").val(date);},
												onClose: function(date) { if(date =="" ){ $(this).parent().html(orgVal); return; } 
																		 else { 
																			if(date != orgVal) {
																			$.post("EventController.php",{
																				command: "saveChange",
																				id: $(this).parent().parent().attr('id'),
																				field : $(this).parent().attr('field'),
																				value : date
																				},function(){
																				
																				});
																				$(this).parent().html(date);
																			} }
																		}
						});
						
					//});
				$(this).find('input').focus();
			}  else {
			// change to textarea		
			$(this).html("<textarea>"+val+"</textarea> "); 
			$(this).find('textarea').focus();
			}

		}
	});
	

	
	$(document).on('blur','#eventTable tbody td textarea',function(){
	//alert($(this).parent().parent().attr('id'));
	var val = $(this).val();
	if(val == "") {alert("Cannot be empty!"); $(this).parent().html(orgVal); return; }
	if(val != orgVal) {
	$.post("EventController.php",{
		command: "saveChange",
		id: $(this).parent().parent().attr('id'),
		field : $(this).parent().attr('field'),
		value : val
		},function(){
		
		});
	}
	$(this).parent().html(val);
	
	});
	
	

</script>
</div>
</body>
</html>
