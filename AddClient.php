<?php

session_start();
if(!isset($_SESSION['event_management_login']) || $_SESSION['event_management_login'] != 1 ) header("Location:index.php");

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
#eventTable tbody tr:not(:last-child) td:hover{
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
	



<style>
tbody{
	cursor: pointer;
}
#createClient div{
	background-color:#DDD;
	border-bottom-left-radius: 5px;
	border-bottom-right-radius: 5px;
}
#createClient div:hover{
	background-color: #75B2DD;
	color:white;
}
</style>
<h4>
	<small>
		<li>You can only change the password of the Admin. You cannot delete or change the name of Admin.</li>
		<li>When you create a new client, the calendar link for this client is http://www.ignitorlabs.com/aetna_calendar/client Name</li>
		<li>When you delete the client, all information including events belonging to this client will be deleted from the system. </li>
		<li>Double click existing Client to modify. </li>
		<li>Client Name should be unique. </li>
	</small>
</h4>
<table id='client' class="table">
	<thead><tr><th>Client Name</th><th>Password</th><th></th><tr></thead>
	<tbody>
	<?php
	$pws = $db->select("aetna_client","1 order by id");
	if(array_key_exists('id', $pws)) $pws = array($pws);
	foreach($pws as $pw) {
		if(is_array($pw)){
			if($pw['id']==1)  echo "<tr id='".$pw['id']."'><td>".$pw['name']."</td>";
			else echo "<tr id='".$pw['id']."'><td class='editable' field='name'>".$pw['name']."</td>";
		echo "<td class='editable' field='pw'>".$pw['pw']."</td><td>";
		if($pw['id']!=1) echo "<a onclick='deleteClient(".$pw['id'].")'><img style='width:20px' src='img/Delete-icon.png' /></a>"; 
		echo "</td></tr>";
		}

	}
	
	?>
	<tr id='createClient'><td style='text-align:center' colspan="3"><center><div style='margin-top:-9px;padding:2px;width:300px;'>Add New Client</div></center></td></tr>
	</tbody>

</table>
<script>
$('#createClient div').click(function(){
	if($('.save').is(':visible')) return;
	$(this).parent().parent().parent().before("<tr><td><input type='text'  class='form-control username' placeholder='username'/></td><td><input type='text' class='form-control pw' placeholder='Password'/></td><td><img class='save' style='width:28px' src='img/Save-icon.png' /></td></tr>");

	$('.save').click(function(){
	name = $(this).parent().parent().find('.username').val();
	pw = $(this).parent().parent().find('.pw').val();
	if(name.length>49) { alert("Client name is too long!");return;}
	
	if(pw!=""&&name!=""&&name!=null&&pw!=null) {
		if(pw.search('^[a-zA-Z0-9]+$')) {alert("Password can only consist of letters or numbers ");return;}
 		newClient(name,pw);
	} 
	});

});


function newClient(name,pw) {
	 	$.post("EventController.php",{
		command: "newClient",
		pw : pw,
		username: name
		},function(data){
   			location.reload();
		});
}

function deleteClient(id){
		$.post("EventController.php",{
		command: "deleteClient",
		id :id
		},function(data){
   			location.reload();
		});
}

	var orgVal="";
	$('#client tbody td.editable').dblclick(   //double click text to show edit box
		function(){
		
		if($(this).html().indexOf("<textarea")>=0) { //already open, close edit box
			var val = $(this).find("textarea").html();
			$(this).html(val);
		}
		else {
			var val = $(this).html();
			orgVal = val;  //store original data;
			// change to textarea		
			$(this).html("<textarea>"+val+"</textarea> "); 
			$(this).find('textarea').focus();
			}

		
	});

	$(document).on('blur','td.editable textarea',function(){
	//alert($(this).parent().parent().attr('id'));
	var val = $(this).val();
	if(val == "") {alert("Cannot be empty!"); $(this).parent().html(orgVal); return; }
	if(val != orgVal) {
		$.post("EventController.php",{
			command: "saveClient",
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
