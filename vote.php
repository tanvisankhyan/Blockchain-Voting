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

include_once("includes/dao.php");
if(!$_SESSION['voter']){ header("Location:index.php");  }

$directory = "voters/";
$filecount = 0;
$files = glob($directory . "*");
if ($files){
 $filecount = count($files);
}

$accuracy=100/$filecount;
$accuracyResult = 0;
$tempName="";

$head = "Cast your vote for Vice President 2020";
$isDone = 0;
$hasVoted = "no";

if(isset($_POST['voted'])){

	if(isset($_POST['one'])){
		$candidate = "David Ramson";
	} if(isset($_POST['two'])){
		$candidate = "Micheal Ross";
	} if(isset($_POST['three'])){
		$candidate = "Steve Collens";
	} if(isset($_POST['four'])){
		$candidate = "Alison Mathew";
	}

  $dao = new DAO();

	for($j=0; $j<$filecount; $j++){

		$full_chain = $dao->read_all($j+1);
		$records = count($full_chain["votes"]);

		for($i=0; $i<$records; $i++){
			if($full_chain["votes"][$i]["voterId"]==$_SESSION['voter']){
				$accuracyResult += $accuracy;
				$tempName = $full_chain["votes"][$i]["candidate"];
				break;
			}
		}

	} //a similar code will be used for counting votes.

	if($accuracyResult>80){
		$hasVoted = $tempName;
	} //accuracy greater then 80 par confirm else there is some doping in data

	if($hasVoted=="no"){

		$previous_hashid = $dao->get_previous_hashid($full_chain["votes"]);
  	$previous_index = $dao->get_previous_index($full_chain["votes"]);
  	$next_index = $previous_index+1;

		$timestamp = round(microtime(true) * 1000);
		$voterId = $_SESSION['voter'];
		$new_hashid = $dao->get_new_hashid($previous_hashid,$next_index,$timestamp,$voterId);

		$full_chain["votes"][] = [
			"index" => $next_index,
      "timestamp" => $timestamp,
      "voterId" => $voterId,
	    "candidate" => $candidate,
      "previousHash" => $previous_hashid,
      "hash" => $new_hashid
		];

		$jsonData = json_encode($full_chain);

		for($j=0; $j<$filecount; $j++){
			file_put_contents('voters/voter'.($j+1).'/votes.json', $jsonData);
		}

		$isDone = 1;
		$head = "Vote is registered for ".$candidate;

	} else{
		$head = "You have already voted for ".$hasVoted;
	}

}
?>
<body style="background:#d6d2d2">
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
	  <a class="navbar-brand" href="#">Voter ID: <?php echo $_SESSION['voter']; ?></a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="navbarColor01">
	    <ul class="navbar-nav mr-auto">

	    </ul>
			<a style="color:white; font-size:20px;" href="logout.php">Logout</a>
	  </div>
	</nav>
<div class="container">
	<div class="row">
		<div class="col-md-12" style="padding-top:30px; <?php if($isDone == 0){ ?> color:#2c3e50 <?php } else { ?> color:#3f5219; <?php } ?>"><h3 class="text-center"><?php echo $head; ?></h3></div>
	</div>
	<form action="" method="POST">
	<input type="hidden" name="voted">
	<div class="row">
		<div class="col-md-3" style="padding:35px;">
			<img src="candidates/1.jpg" style="width:100%">
			<div style="border:1px solid black; padding:10px;">
				<br/>
				<h4 style="color:#2c3e50">David Ramson</h4>
				<p>Democratic Party of ABC</p>
				<hr/>
				<input style="width:100%;" type="submit" name="one" value="Vote" class="btn btn-danger">
			</div>
		</div>
		<div class="col-md-3" style="padding:35px;">
			<img src="candidates/2.jpg" style="width:100%">
			<div style="border:1px solid black; padding:10px;">
				<br/>
				<h4 style="color:#2c3e50">Micheal Ross</h4>
				<p>Republican United</p>
				<hr/>
				<input style="width:100%;" type="submit" name="two" value="Vote" class="btn btn-danger">
			</div>
		</div>
		<div class="col-md-3" style="padding:35px;">
			<img src="candidates/3.jpg" style="width:100%">
			<div style="border:1px solid black; padding:10px;">
				<br/>
				<h4 style="color:#2c3e50">Steve Collens</h4>
				<p>American Janta Party</p>
				<hr/>
				<input style="width:100%;" type="submit" name="three" value="Vote" class="btn btn-danger">
			</div>
		</div>
		<div class="col-md-3" style="padding:35px;">
			<img src="candidates/4.jpg" style="width:100%">
			<div style="border:1px solid black; padding:10px;">
				<br/>
				<h4 style="color:#2c3e50">Alison Mathew</h4>
				<p>XYZ Congress</p>
				<hr/>
				<input style="width:100%;" type="submit" name="four" value="Vote" class="btn btn-danger">
			</div>
		</div>
	</div>
	</form>
</div>
</body>
</html>
