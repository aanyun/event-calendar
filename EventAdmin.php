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
	date = new Date();
	
	$( "#inputSDate" ).datepicker(
		{dateFormat: 'yy-mm-dd',
		minDate: date, 
		onSelect: function(){
			$(this).parent().removeClass("has-error"); 
			if($('#inputEDate').val()!=""&&$('#inputSDate').val()>$('#inputEDate').val()) {
			$(this).val("");
			$(this).parent().addClass('has-error');
			alert("Please ensure that the End Date is greater than or equal to the Start Date.");
			}
		}
	});


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
.url{
	word-wrap: break-word;
	-ms-word-break: break-all;

     /* Be VERY careful with this, breaks normal words wh_erever */
     word-break: break-all;

     /* Non standard for webkit */
     word-break: break-word;

-webkit-hyphens: auto;
   -moz-hyphens: auto;
        hyphens: auto;
	
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
	



<!--Event Management List-->

<div id='eventTable' class="section table-responsive">
<p style="color:#B8B8B8;margin-top:30px;margin-bottom:30px">Double Click to change data</p>
  <table id="events" class="table tablesorter">
	<thead><tr><th>Title</th><th>Description</th><th>Link</th><th>Start_Date</th><th>End_Date</th><th>Delete</th><tr></thead>
	<tbody>
	<?php
	//include 'class/DB.php';
	include 'class/Event.php';
	if($_SESSION['event_management_login'] == 1) $events = Event::AdminDisplayAll();
	else $events = Event::getEventsByM($_SESSION['event_management_login']);
		if(count($events) == 0) echo "<p>No event yet</p>";
		else {
		if(array_key_exists('id',$events)) $events = array($events);
		//print_r($events);
		foreach($events as $event){
			if(is_array($event)){
			 echo "<tr id = ".$event['id'].">";
			 echo "<td field = 'title'>".$event['title']."</td>";
			 echo "<td field = 'description'>".$event['description']."</td>";
			 echo "<td class='url' field = 'url'>".$event['url']."</td>";
			 echo "<td id='date_".$event['id']."' field = 'start'>".$event['start']."</td>";
			 echo "<td field = 'end'>".$event['end']."</td>";
			 echo "<td>";
			 //echo ($event['Pending'] == 1)? "<button type='button' class='approve btn btn-success'>Approve</button>":"";
			 echo "<button type='button' class='delete btn btn-danger'>Delete</button></td>";
			 echo "</tr>";
			}
		}
		}
	?>
	</tbody>
  </table>
</div> 


<!--End of Event Management List-->  

<div style='height:100px'></div>

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
	if(val == ""&&$(this).parent().attr('field')!='description') {alert("Cannot be empty!"); $(this).parent().html(orgVal); return; }
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
