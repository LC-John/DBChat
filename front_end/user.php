<?php
    session_start();
    if ($_POST['submit'] == 'Leave')
    {
        unset($_SESSION['userid']);
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        header("Refresh:0; url=index.php");
    }
    elseif ($_POST['submit'] == 'Rename')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL USER_RENAME($_SESSION[userid], '$_SESSION[password]', '$_POST[username]', $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted;");
            $row = mysqli_fetch_array($result);
            mysqli_free_result($result);
            if ($row[$tmp_granted] > 0)
            {
                echo "<script>alert(\"$_SESSION[username](ID=$_SESSION[userid]) renamed!\")</script>";
                $_SESSION['username'] = $_POST['username'];
            }
            else
            {
                echo "<script>alert(\"Fail to rename $_SESSION[username](ID=$_SESSION[userid])...\")</script>";
            }
        }
        mysqli_close($conn);
        header("Refresh:0; url=main.php");
    }
    elseif ($_POST['submit'] == 'Password')
    {
        $_POST['password'] = md5(sha1($_POST['password']));
        $_POST['old_password'] = md5(sha1($_POST['old_password']));
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        elseif ($_SESSION['password'] != $_POST['old_password']) {
            mysqli_close($conn);
            echo "<script>alert(\"Wrong password!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL USER_CHANGE_PASSWORD($_SESSION[userid], '$_SESSION[password]', '$_POST[password]', $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted;");
            $row = mysqli_fetch_array($result);
            mysqli_free_result($result);
            if ($row[$tmp_granted] > 0)
            {
                echo "<script>alert(\"$_SESSION[username](ID=$_SESSION[userid]) password changed!\")</script>";
                $_SESSION['password'] = $_POST['password'];
            }
            else
            {
                echo "<script>alert(\"Fail to change password for $_SESSION[username](ID=$_SESSION[userid])...\")</script>";
            }
        }
        mysqli_close($conn);
        header("Refresh:0; url=main.php");
    }
    elseif ($_POST['submit'] == 'Purge')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL USER_SIGN_OUT($_SESSION[userid], '$_SESSION[password]', $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted;");
            $row = mysqli_fetch_array($result);
            mysqli_free_result($result);
            if ($row[$tmp_granted] > 0)
            {
                echo "<script>alert(\"$_SESSION[username](ID=$_SESSION[userid]) purged! Return to home page.\")</script>";
            }
            else
            {
                echo "<script>alert(\"Fail to puege $_SESSION[username](ID=$_SESSION[userid])... Return to home page.\")</script>";
            }
        }
        unset($_SESSION['userid']);
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        mysqli_close($conn);
        header("Refresh:0; url=index.php");
    }
    else
    {
        echo "<script>alert(\"Something wrong... Try again later.\"); history.go(-1);</script>";
    }
?>