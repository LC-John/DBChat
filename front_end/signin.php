<?php
	session_start();
	if(isset($_POST["submit"]) && $_POST["submit"] == "signin")
	{
            $_POST['password'] = md5(sha1($_POST['password']));
		$conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
		if (!$conn)
		{
			echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
		}
		else
		{
			$tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
			mysqli_query($conn, "CALL USER_SIGN_IN($_POST[userid], '$_POST[password]', $tmp_granted);");
			$result = mysqli_query($conn, "SELECT $tmp_granted;");
			$num = mysqli_num_rows($result);  
            if(!$num)
            {
            	mysqli_free_result($result);
            	mysqli_close($conn);
            	echo "<script>alert(\"Cannot sign in... Try again later.\"); history.go(-1);</script>";
            }
            else
            {
            	$row = mysqli_fetch_array($result);
            	$granted = $row[$tmp_granted];
            	mysqli_free_result($result);
            	if ($granted > 0)
            	{
            		$tmp_cname = "@tmp_cname_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            		mysqli_query($conn, "CALL USER_GET_NAME($_POST[userid], '$_POST[password]', $tmp_cname, $tmp_granted);");
            		$result = mysqli_query($conn, "SELECT $tmp_cname;");
            		$num = mysqli_num_rows($result);  
            		if(!$num)
            		{
            			mysqli_free_result($result);
            			mysqli_close($conn);
            			echo "<script>alert(\"Cannot sign in... Try again later.\"); history.go(-1);</script>";
            		}
            		$row = mysqli_fetch_array($result);
            		$cname = $row[$tmp_cname];
            		mysqli_free_result($result);
            		mysqli_close($conn);
            		$_SESSION['userid'] = $_POST['userid'];
            		$_SESSION['password'] = $_POST['password'];
            		$_SESSION['username'] = $cname;
            		echo "<script>alert(\"Welcome, $_SESSION[username]!\");</script>";
            		header("Refresh:0; url=main.php");
            	}
            	else
            	{
            		mysqli_close($conn);
            		echo "<script>alert(\"Wrong ID or password!\"); history.go(-1);</script>";
            	}
            }
		}
	}
	else
	{
		echo "<script>alert(\"Something wrong... Try again later.\"); history.go(-1);</script>";
	}
?>