<?php
	session_start();
	if(isset($_POST["submit"]) && $_POST["submit"] == "signup")
	{
		$_POST['password'] = md5(sha1($_POST['password']));
		$conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
		if (!$conn)
		{
			echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
		}
		else
		{
			$tmp_cid = "@tmp_cid_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
			mysqli_query($conn, "CALL USER_SIGN_UP('$_POST[username]', '$_POST[password]', $tmp_cid);");
			$result = mysqli_query($conn, "SELECT $tmp_cid;");
			$num = mysqli_num_rows($result);  
            if(!$num)
            {
            	mysqli_free_result($result);
            	mysqli_close($conn);
            	echo "<script>alert(\"Cannot sign up... Try other names.\"); history.go(-1);</script>";
            }
            else
            {
            	$row = mysqli_fetch_array($result);
            	$cid = $row[$tmp_cid];
            	mysqli_free_result($result);
            	mysqli_close($conn);
            	$_SESSION['userid'] = $cid;
            	$_SESSION['password'] = $_POST['password'];
            	$_SESSION['username'] = $_POST['username'];
            	echo "<script>alert(\"Welcome, $_POST[username]! Your ID is $cid. Keep it in mind please.\");</script>";
            	header("Refresh:0; url=main.php");
            }
		}
	}
	else
	{
		echo "<script>alert(\"Something wrong... Try again later.\"); history.go(-1);</script>";
	}
?>