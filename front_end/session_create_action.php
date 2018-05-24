<?php
	session_start();
	if ($_POST['submit'] == 'Create group session')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            $tmp_sid = "@tmp_sid_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL SESSION_CREATE($_SESSION[userid], '$_SESSION[password]', '$_POST[sessname]', '$_POST[password]', $tmp_sid, $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted;");
            $row = mysqli_fetch_array($result);
            if ($row[$tmp_granted] <= 0)
            {
            	mysqli_free_result($result);
            	mysqli_close($conn);
            	echo "<script>alert(\"Fail to create group session...\");</script>";
            	header("Refresh:0; url=main.php");
            }
            else
            {
	            $result = mysqli_query($conn, "SELECT $tmp_sid;");
	            $row = mysqli_fetch_array($result);
	            mysqli_free_result($result);
	            mysqli_close($conn);
	            echo "<script>alert(\"Group session created! Session ID is $row[$tmp_sid]\");</script>";
	            header("Refresh:0; url=main.php");
        	}
        }
    }
    elseif ($_POST['submit'] == 'Create private session')
    {
    	$conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            $tmp_sid = "@tmp_sid_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL SESSION_CREATE_FRIEND($_SESSION[userid], '$_SESSION[password]', $_POST[userid], $tmp_sid, $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted;");
            $row = mysqli_fetch_array($result);
            if ($row[$tmp_granted] <= 0)
            {
            	mysqli_free_result($result);
            	mysqli_close($conn);
            	echo "<script>alert(\"Fail to create private session...\");</script>";
            	header("Refresh:0; url=main.php");
            }
            else
            {
	            $result = mysqli_query($conn, "SELECT $tmp_sid;");
	            $row = mysqli_fetch_array($result);
	            mysqli_free_result($result);
	            mysqli_close($conn);
	            echo "<script>alert(\"Private session created! Session ID is $row[$tmp_sid]\");</script>";
	            header("Refresh:0; url=main.php");
        	}
        }
    }
	else
	{
		echo "<script>alert(\"Something wrong... Try again later.\"); history.go(-1);</script>";
	}
?>