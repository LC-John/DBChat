<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title> DBChat </title>
		<!-- Loading Bootstrap -->
    	<link href="css/css/bootstrap.min.css" rel="stylesheet">
    	<!-- Loading Flat UI -->
   		<link href="css/css/flat-ui.min.css" rel="stylesheet">
		<!--<link href="css/docs.css" rel="stylesheet">--> 
		<script src="js/jquery.min.js"></script>
		<script src="js/flat-ui.min.js"></script>
		<script src="js/d3.min.js"></script>
		<style>
			body {  
				background-image: url("img/bg.png");  
				background-position: center 0;  
				background-repeat: no-repeat;  
				background-attachment: fixed;  
				background-size: cover;  
				-webkit-background-size: cover;  
				-o-background-size: cover;  
				-moz-background-size: cover;  
				-ms-background-size: cover;  
            }  
		</style>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-01">
      			<span class="sr-only">Toggle navigation</span>
    			</button>
     			<a class="navbar-brand" href="main.php" target="_self"> Home </a>
			</div>
		</nav>

		<div class="container" style="margin-top: 80px; margin-bottom: 100px;">
            <div class="row">
                <div class="container">
                    <div class="col-md-4"> </div>
                    <div class="col-md-8">
                        <p><dl class="text-muted">

<?php
	if ($_POST['submit'] == 'Friend')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            $result=mysqli_query($conn, "CALL USER_GET_FRIEND($_SESSION[userid], '$_SESSION[password]', $tmp_granted);");

            echo "<table border='1'>
			<tr>
			<th> Friend ID </th>
			<th> Friend name </th>
			</tr>";
			while($row = mysqli_fetch_array($result))
			{
				echo "<tr>";
				echo "<td> " . $row['CID'] . " </td>";
			  	echo "<td> " . $row['CNAME'] . " </td>";
			  	echo "</tr>";
			}
			echo "</table>";
			mysqli_free_result($result);
        }
        mysqli_close($conn);
    }
    elseif ($_POST['submit'] == 'Group')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            $result=mysqli_query($conn, "CALL SESSION_GET_MY_SESSION($_SESSION[userid], '$_SESSION[password]', $tmp_granted);");

            echo "<table border='1'>
			<tr>
			<th> Session ID </th>
			<th> Session name </th>
			<th> Manager </th>
			</tr>";
			while($row = mysqli_fetch_array($result))
			{
				echo "<tr>";
				echo "<td> " . $row['SID'] . " </td>";
			  	echo "<td> " . $row['SNAME'] . " </td>";
			  	echo "<td> " . $row['MANAGER'] . " </td>";
			  	echo "</tr>";
			}
			echo "</table>";
			mysqli_free_result($result);
        }
        mysqli_close($conn);
    }
    elseif ($_POST['submit'] == 'Application')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            $result=mysqli_query($conn, "CALL USER_GET_FRIEND_APPLICATION($_SESSION[userid], '$_SESSION[password]', $tmp_granted);");

            echo "<table border='1'>
			<tr>
			<th> Application ID </th>
			<th> User ID </th>
			<th> User name </th>
			</tr>";
			while($row = mysqli_fetch_array($result))
			{
				echo "<tr>";
				echo "<td> " . $row['FAID'] . " </td>";
			  	echo "<td> " . $row['CID'] . " </td>";
			  	echo "<td> " . $row['CNAME'] . " </td>";
			  	echo "</tr>";
			}
			echo "</table>";
			mysqli_free_result($result);
        }
        mysqli_close($conn);
    }
	else
	{
		echo "<script>alert(\"Something wrong... Try again later.\"); history.go(-1);</script>";
	}
?>
                        </dl></p>
                        <form action="return_main.php" method="post"> 
                            <input type="submit" name="submit" class="btn btn-info" value="I see." /> 
                        </form>
                    </div>
                </div>
            </br>

	</body>
</html>