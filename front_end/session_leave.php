<?php
    session_start();
    if ($_POST['submit'] == 'Leave')
    {
        $_SESSION['sessid'] = $_POST['id'];
        $_SESSION['sessname'] = $_POST['name'];
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\");</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL SESSION_LEAVE($_SESSION[userid], '$_SESSION[password]', $_POST[id], $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted");
            $row = mysqli_fetch_array($result);
            if ($row[$tmp_granted] <= 0)
            {
                echo "<script>alert(\"Fail to leave the session!\");</script>";
            }
            mysqli_free_result($result);
            mysqli_close($conn);
        }
        header("Refresh:0; url=main.php");
    }
    elseif ($_POST['submit'] == 'Change manager')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\");</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL SESSION_CHANGE_MANAGER($_SESSION[userid], '$_SESSION[password]', $_SESSION[sessid], $_POST[userid], $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted");
            $row = mysqli_fetch_array($result);
            if ($row[$tmp_granted] <= 0)
            {
                echo "<script>alert(\"Fail to change manager!\");</script>";
            }
            mysqli_free_result($result);
            mysqli_close($conn);
        }
        header("Refresh:0; url=session_main.php");
    }
    elseif ($_POST['submit'] == 'Purge the session')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\");</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL SESSION_DESTROY($_SESSION[userid], '$_SESSION[password]', $_SESSION[sessid], $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted");
            $row = mysqli_fetch_array($result);
            if ($row[$tmp_granted] <= 0)
            {
                echo "<script>alert(\"Fail to purge the session!\");</script>";
            }
            mysqli_free_result($result);
            mysqli_close($conn);
        }
        header("Refresh:0; url=main.php");
    }
    else
    {
        echo "<script>alert(\"Something wrong... Try again later.\");</script>";
    }
?>