<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="stylesheet" href="css/signin.css">
	
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script src="js/bootstrap-select.js"></script>
    <title>Event Management Login</title>
	 <script type="text/javascript">
			$(window).on('load', function () {

				$('.selectpicker').selectpicker({
					
				});
				
			});
		</script>
		<style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
  </head>

  <body style="">

    <div class="container" style='width:400px'>

      <div class="form-signin">
		
        <h2 class="form-signin-heading">Please sign in</h2>
		
		
		<select style="width:20px" class='selectpicker' data-title="Company Name">
		<?php
		include 'class/DB.php';
		$db = new DB();
		$db->connect();
		$users = $db->select('aetna_client',"1 order by id");
		if(array_key_exists('id', $users)) $users = array($users);
		foreach($users as $user){
		if(is_array($user))	
			echo "<option id='".$user['id']."'>".$user['name']."</option> ";			
		}
		?>
		</select>
        <input type="password" class="form-control" placeholder="Password" required>
		<div class='alert alert-danger hide'></div>
        <button class="btn btn-lg btn-primary btn-block" onclick="submit()">Sign in</button>
      </div>
    </div> <!-- /container -->
<script>
	function submit() {
		mid = $('option:selected').attr('id');
		pw = $('input').val();
		if(pw == "") {
			$('.alert').removeClass('hide');
			$('.alert').html('Password is required.');
			return;
		}
		$.post("EventController.php",{
		command: "signIn",
		id : mid,
		pw : pw,
		},function(data){
			if(data == 1) {
				if( mid == 1) window.location.href="EventAdmin.php";
				else window.location.href="EventAdmin.php?id="+mid;
			}
			else {
			$('.alert').removeClass('hide');
			$('.alert').html('Password is wrong.');}
		});
		
	}
</script>

  </body>
</html>