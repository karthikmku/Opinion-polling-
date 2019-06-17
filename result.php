<?php
include('database_connection.php');

if(isset($_POST["poll_option"]))
{
	$query = "
	INSERT INTO tbl_poll 
	(php_framework) VALUES (:php_framework)
	";
	$data = array(
		':php_framework' =>	$_POST["poll_option"]
	);
	$statement = $connect->prepare($query);
	$statement->execute($data);
}


$php_framework = array("JQuery", "MooTools", "React JS", "Glow", "Babylon JS");

$total_poll_row = get_total_rows($connect);
$output = '';
if($total_poll_row > 0)
{
	foreach($php_framework as $row)
	{
		$query = "SELECT * FROM tbl_poll WHERE php_framework = '".$row."'";
		$statement = $connect->prepare($query);
		$statement->execute();
		$total_row = $statement->rowCount();
		$percentage_vote = round(($total_row/$total_poll_row)*100);
		$progress_bar_class = '';
		if($percentage_vote >= 40)
		{
			$progress_bar_class = 'progress-bar-success';
		}
		else if($percentage_vote >= 25 && $percentage_vote < 40)
		{
			$progress_bar_class = 'progress-bar-info';
		}
		else if($percentage_vote >= 10 && $percentage_vote < 25)
		{
			$progress_bar_class = 'progress-bar-warning';
		}
		else
		{
			$progress_bar_class = 'progress-bar-danger';
		}
		$output .= '
		<div class="row">
			<div class="col-md-2" align="right">
				<label>'.$row.'</label>
			</div>
			<div class="col-md-10">
				<div class="progress">
					<div class="progress-bar '.$progress_bar_class.'" role="progressbar" aria-valuenow="'.$percentage_vote.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentage_vote.'%">
						'.$percentage_vote.' % programmer like <b>'.$row.'</b> PHP Framework
					</div>
				</div>
			</div>
		</div>
		
		';
	}
}



function get_total_rows($connect)
{
	$query = "SELECT * FROM tbl_poll";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}


?>


<html>  
    <head>  
        <title>Opinion Poll System</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
		
    </head>  
     <style type="text/css">
        body{ font: 14px sans-serif;background:url(image.png)}
        </style>
       <nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">OPINION POLL</a>
    </div>
    <ul class="nav navbar-nav navbar-right">
       <li><a href="reset-password.php">Reset Password</a></li>
      <li><a href="logout.php">Logout</a></li>
      </ul>
  </div>
</nav> 
        
    <body>  
        <div class="container">  
            <br />  
            <br />
			<br />
			<h2 align="center">Opinion Poll System</h2><br />
			<div class="row">
				
				<div class="col-md-10">
					<br />
					<br />
					<br />
					<h4>Poll Result</h4><br />
					<div id="poll_result"><?php echo $output; ?></div>
				</div>
			</div>
				<br />
			<br />
			<br />
             <div class="form-group" align="center">
                <input type="button" class="btn btn-primary" value="Cancel" onClick="window.location.href='index.php'">
            </div>
		</div>
    </body>  
</html>  
