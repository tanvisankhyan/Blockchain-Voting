<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>e Voting</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
	 <link rel="stylesheet" href="bootstrap.min.css">
  </head>
<?php
session_start();

$directory = "voters/";
$filecount = 0;
$files = glob($directory . "*");
if ($files){
 $filecount = count($files);
}

if(isset($_POST['login'])){

	$flag=1;
	$accuracy=100/$filecount;
	$accuracyResult = 0;
	$voterId=$_POST['voterId'];
	$password=$_POST['password'];

	for( $i = 0; $i<$filecount ; $i++){
		$jsondata = file_get_contents("voters/voter".($i+1)."/login.json");
		$arr_data = json_decode($jsondata, true);
		$records = count($arr_data["chain"]);
		for( $j = 0; $j<$records ; $j++){
			if($arr_data["chain"][$j]["voterId"]==$voterId){
				$flag=2;
				if(password_verify($password,$arr_data["chain"][$j]["password"])){
					$flag=3;
					$accuracyResult += $accuracy;
				}
			}
		}
	}

	if($accuracyResult>80){
		$_SESSION['voter']=$voterId;
		header("Location:vote.php");
	} //accuracy greater then 80 par allow else there is some doping in data

}
?>
<body style="background-image: url('bg.jpg'); background-attachment: fixed; background-repeat: no-repeat; ">
<nav class="navbar-expand-lg navbar-dark bg-primary" style="padding:20px;">
  <h4 class="text-center" style="color:white">e-Voting using BlockChain</h4>
</nav>
<div class="container">
	<div class="row">
		<div class="alert rounded col-md-4 w3-animate-left" style="background:#4b5c7a; margin:20px; margin-top:100px; padding:25px; padding-bottom:50px;">
			<strong><h2 class="text-center" style="color:white">Voter Login</h2></strong><hr/>
			<?php if(isset($flag)){ if($flag==1){ ?>
				<div class="alert alert-danger">The Voter ID is invalid !</div>
			<?php } if($flag==2){ ?>
				<div class="alert alert-danger">The Password is invalid !</div>
			<?php } } ?>
			<form action="" method="POST">
				<h6 style="color:white;">Voter ID:</h6><input type="text" style="width:100%;" required="required" name="voterId" class="form-control"><br><h6 style="color:white;">Password:</h6>
				<input type="password" style="width:100%;" required="required" name="password" class= "form-control"><a href=""><p align="right" style="color:white"></p></a>
				<br/>
				<input style="width:100%; background:#e8c293;" type="submit" name="login" value="Log In Now " class="btn">
			</form>
		</div>
		<div class="col-md-7" style="padding-top:200px;"><center>
		</center></div>
	</div>
</div>
</body>
</html>
