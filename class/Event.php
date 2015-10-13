<?php 
	class Event{
		public static function DisplayAll(){
			$db = new DB();
			$db->connect();
			//$date = date("Y-m-d");
			$result = $db->select("aetna_event","1");
			return $result;
			}
		public static function ClientCheck($cname){
			$db = new DB();
			$db->connect();
			//$date = date("Y-m-d");
			$result = $db->select("aetna_client","name='".$cname."'");
			return $result;
			}
		public static function Display($cname){
			$db = new DB();
			$db->connect();
			//$date = date("Y-m-d");
			$result = $db->select('aetna_event,aetna_client',"aetna_client.id =aetna_event.client_id and aetna_client.name='".$cname."'");
			return $result;
			}
		public static function AdminDisplayAll(){
			$db = new DB();
			$db->connect();
			$date = date("Y-m-d");
			$result = $db->select('aetna_event',"1 order by id DESC");
			return $result;
			}
		public static function AdminDisplayAllPending(){
			$db = new DB();
			$db->connect();
			$date = date("Y-m-d");
			$result = $db->select('event',"Pending = 1 and (Start_Date>='".$date."' or End_Date>='".$date."') order by Start_Date");
			return $result;
			}
		public static function getEventsByM ($cid){
			$db = new DB();
			$db->connect();
			//$date = date("Y-m-d");
			$result = $db->select('aetna_event'," client_id = ".$cid);
			return $result;
		}
		public static function createEvent ($data,$manufacturers) {
			$db = new DB();
			$db->connect();
			foreach($manufacturers as $manufacturer) {
			$result = $db->insert("insert into event (Title,Location,Event_Link,Start_Date,End_Date,manufacturer,service,sales) values ('".$data['Title']."','".$data['Location']."','".$data['Link']."','".$data['Start_Date']."','".$data['End_Date']."','".$manufacturer."',".$data['Service'].",".$data['Sales'].")");
			}
		}
		public static function AdminCreateEvent ($data) {
			$db = new DB();
			$db->connect();
			
			$result = $db->insert("insert into aetna_event (title,url,start,end,description,client_id) values ('".$data['title']."','".$data['url']."','".$data['start']."','".$data['end']."','".$data['description']."',".$data['client_id'].")");
			
		}
		public static function deleteEvent($id){
			$db = new DB();
			$db->connect();
			$result = $db->delete("delete from aetna_event where id=".$id);
		}
		public static function saveChange($id,$field,$value){
			$db = new DB();
			$db->connect();
			$result = $db->update("update aetna_event set ".$field." = '".$value."' where ID=".$id);
		}
		public static function saveClient($id,$field,$value){
			$db = new DB();
			$db->connect();
			$result = $db->update("update aetna_client set ".$field." = '".$value."' where id=".$id);
		}
		
	
	}



?>