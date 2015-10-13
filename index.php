<?php


session_start();

include "class/DB.php";
include "class/Event.php";
if(isset($_GET['name'])&&array_key_exists('id', Event::clientCheck($_GET['name']))) {  
	$events = Event::Display($_GET['name']);
	$client = $_GET['name'];
} else {  //display all events and controlled by Admin
	$events = Event::DisplayAll();
	$client = 'Admin';
}
if(array_key_exists('id', $events)) $events = array($events);
$events = json_encode($events);		
$db = new DB();
$db->connect();
$theme = json_encode($db->select('aetna_month_color,aetna_client','name="'.$client.'" and aetna_client.id = aetna_month_color.client_id order by aetna_month_color.id'));
//print_r($events);
?>


<!DOCTYPE html>
<html>
<head>
<link href='css/fullcalendar.css' rel='stylesheet' />
<link href='css/fullcalendar.print.css' rel='stylesheet' media='print' />

<script src='lib/jquery.min.js'></script>
<script src='lib/jquery-ui.custom.min.js'></script>
<script src='js/fullcalendar.min.js'></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<link href="css/bootstrap.css" rel="stylesheet">
<script>

	$(document).ready(function() {
		var theme = <?php echo $theme?>;
		//alert(theme[0].color);
		//var colorArray = ['#FF6600','#FF6699','#339933','#AC8330','#30ACAC','#FFBF00','#5882FA','#2ECCFA','#33CC33','#DF0174','#6A0888','#FF0000']

		function monthTheme(color){
			$('.fc-header-left').css('background-color',color);
			$('.fc-border-separate th').css('color',color).css('border-bottom-color',color);
		}
		
		$('#calendar').fullCalendar({
			header: {
				left: 'title prev,next today',
				//center: 'title',
				right: '',//'month,basicWeek,basicDay'
			},
			columnFormat: {
				 month: 'dddd'
	
			},
			editable: false,
			events: <?php echo $events;?>,
			eventRender : function(event,element){
				//alert(event.description == null);
				if(event.description!=null) {
				element.popover({container: 'body',trigger:'hover',content: event.description,title: event.title});
				}
			},
			eventClick: function(calEvent, jsEvent, view) {

		        //alert('Event: ' + calEvent.title);
		        //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
		       // alert('View: ' + view.name);

		        // change the border color just for fun
		       // $(this).css('border-color', 'red');

		    },
		    viewDisplay   : function(view) {
		      var now = new Date(); 
		      var end = new Date();
		      now.setMonth(0);
		      end.setMonth(11); //Adjust as needed

		      var cal_date_string = view.start.getMonth()+'/'+view.start.getFullYear();
		      var cur_date_string = now.getMonth()+'/'+now.getFullYear();
		      var end_date_string = end.getMonth()+'/'+end.getFullYear();

		      if(cal_date_string == cur_date_string) { jQuery('.fc-button-prev').addClass("fc-state-disabled"); }
		      else { jQuery('.fc-button-prev').removeClass("fc-state-disabled"); }

		      if(end_date_string == cal_date_string) { jQuery('.fc-button-next').addClass("fc-state-disabled"); }
		      else { jQuery('.fc-button-next').removeClass("fc-state-disabled"); }
		    }
		});
		
		var d = new Date();
		var m =d.getMonth()+1;
		$('.fc-header').css('background-image','url('+theme[m-1].img_path+')');
		monthTheme('#'+theme[m-1].color);

		$('.fc-button-prev').click(function(){
			
			if(m ==1) return;
			m--;
			monthTheme('#'+theme[m-1].color);
			$('.fc-header').css('background-image','url('+theme[m-1].img_path+')');
		});
		$('.fc-button-next').click(function(){
			if(m ==12) return;
			m++;
			monthTheme('#'+theme[m-1].color);
			$('.fc-header').css('background-image','url('+theme[m-1].img_path+')');
		});
		$('.fc-button-today').click(function(){
			m=d.getMonth()+1;
			monthTheme('#'+theme[m-1].color);
			$('.fc-header').css('background-image','url('+theme[m-1].img_path+')');
		});
	});

</script>
<style>

	body {
		margin-top: 40px;
		margin-bottom: 40px;
		text-align: center;
		font-size: 14px;
		
		}
	.fc-event {
		font-size: 0.75em;
	}
	#calendar {
		width: 900px;
		margin: 0 auto;
		}
	.fc-header {
		background-size:650px; 
		background-repeat: no-repeat;
		background-position: 250px 0px;
	}
	.fc-header-left {
		height:200px;
		width:250px!important;
		background-color:#F6CEF5;
		
	}
	.fc-header td:last-child{
		vertical-align: bottom; 
	}
	.fc-header-title{
		display: block;
		vertical-align: top; 
		margin:10px;

	}
	.fc-header-left .fc-button {
		display:inline-block;
		margin-top: 195px;

	}
	.fc-button-prev {
		margin-left: 0px;
	}
	.fc-border-separate thead tr th{
		padding-top: 20px;
		padding-bottom: 10px;
		border-width: 0px 0px 0px 0px;
		border-bottom-width: 3px !important;
		border-bottom-color: #F6CEF5;
		color:rgba(246,206,245,1);
	}
	.fc-border-separate th.fc-first {
    border-left-width: 1px;
	}
	.fc-header-title h2 {
		color:white;
	}
	.fc-event {
		border: 1px solid rgba(246,206,245,1);
		background-color: rgba(246,206,245,0.4);
		color:black;
	.popover {

	}

</style>
</head>
<body>
<?php include "inc/headerCalendar.php";?>
<div id='calendar'></div>
<?php include "inc/footer.php";?>
</body>
</html>
