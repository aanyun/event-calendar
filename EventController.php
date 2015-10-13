<?php 
include 'class/DB.php';
//include 'class/Manufacture.php';
include 'class/Event.php';

$command = $_POST['command'];
switch($command) {
	case "save":
		$data['title'] = addslashes($_POST['title']);
	//	$data['Location'] = $_POST['Location'];
		$data['start'] = $_POST['start'];
		$data['end'] = $_POST['end'];
		//$data['Service'] = $_POST['service'];
		//$data['Sales'] = $_POST['sales'];
		$data['client_id'] = $_POST['client'];
		$data['description'] = addslashes($_POST['description']);
		$data['url'] = $_POST['url'];
		return Event::AdminCreateEvent($data);
		break;
	case "send":
		$data['Title'] = $_POST['Title'];
		$data['Location'] = $_POST['Location'];
		$data['Start_Date'] = $_POST['Start_Date'];
		$data['End_Date'] = $_POST['End_Date'];
		$manufacturers = $_POST['manufacturer'];
		$data['Location'] = $_POST['Location'];
		$data['Service'] = $_POST['service'];
		$data['Sales'] = $_POST['sales'];
		$data['Link'] = $_POST['Link'];
		return Event::createEvent($data,$manufacturers);
		break;
	case "delete":
		$id = $_POST['id'];
		return Event::deleteEvent($id);
		break;
	case "saveChange":
		$id = $_POST['id'];
		$field = $_POST['field'];
		$value = addslashes($_POST['value']);
		Event::saveChange($id,$field,$value);
		break;
	case "saveClient":
		$id = $_POST['id'];
		$field = $_POST['field'];
		$value = addslashes($_POST['value']);
		Event::saveClient($id,$field,$value);
		break;
	case "saveColor":
		$data = $_POST['data'];
		$image = $_POST['image'];
		$db = new DB();
		$db->connect();
		session_start();
		foreach($data as $key => $value){
			$id=$key+1;

			$db->update('update aetna_month_color set color ="'.$value.'",img_path='.$image[$key].' where mid='.$id.' and client_id='.$_SESSION['event_management_login']);
			echo $image[$key];
		}
		
		
		break;
	case "saveBanner":
		$db = new DB();
		$db->connect();
		session_start();
		$db->update('update aetna_banner set bkColor ="'.$_POST['bkColor'].'",logoImg="'.$_POST['logoImage'].'",logoLink="'.$_POST['logoLink'].'",height='.$_POST['height'].' where id='.$_SESSION['event_management_login']);
		break;
	case "signIn":
		$mid = $_POST['id'];
		$pw = $_POST['pw'];
		$db = new DB();
		$db->connect();
		$server_pw = $db->select('aetna_client','id ='.$mid);
		if ($pw == $server_pw['pw']) {
		session_start();
		$_SESSION['event_management_login'] = $mid;
		$_SESSION['event_management_manager'] = $server_pw['name'];
		echo 1;}
		else echo 0;
		break;
	case "newClient":
		$username = addslashes($_POST['username']);
		$pw = $_POST['pw'];
		$db = new DB();
		$db->connect();
		$cid = $db->insert('insert into aetna_client (name,pw) values ("'.$username.'","'.$pw.'")');
		$db->insert('insert into aetna_banner select bkColor,logoImg,logoLink,height,'.$cid.' from aetna_banner where id=1');
		for($i=1;$i<13;$i++) {
			$month = date("F", mktime(0, 0, 0, $i, 10));
			$db->insert('insert into aetna_month_color (client_id,color,month,img_path,mid) values ('.$cid.',"FF6600","'.$month.'","http://ignitorlabs.com/aetna_calendar/img/default.png",'.$i.')');
		}
		break;
	case "deleteClient":
		$id = $_POST['id'];
		$db = new DB();
		$db->connect();
		$db->delete('delete from aetna_client where id='.$id);
		$db->delete('delete from aetna_banner where id='.$id);
		$db->delete('delete from aetna_month_color where client_id='.$id);
		$db->delete('delete from aetna_event where client_id='.$id);
		break;
	case "logout":
		session_start();
		session_destroy();
		break;
}

?>