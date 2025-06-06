<?php
session_start();

if (isset($_POST["loginName"]) && isset($_POST["loginPassword"])) {
    $loginName = trim($_POST["loginName"]);
    $loginPassword = trim($_POST["loginPassword"]);

    $con = mysqli_connect("localhost", "root", "", "taskmanagement");
    if (!$con) {
        header("Location: index.php?error=1");
        exit();
    }

    $tables = ['admin', 'hr', 'employee'];
    foreach ($tables as $table) {
        $sql = "SELECT Name FROM $table WHERE Name = ? AND Password = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $loginName, $loginPassword);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $_SESSION["loginName"] = $loginName;
            $_SESSION["userName"] = $row['Name'];
            $_SESSION["userType"] = $table;
            if ($table === 'hr') {
                header("Location: hr.php");
            } else {
                header("Location: mainPage.php");
            }
            exit();
        }
    }
    // If no match found
    header("Location: index.php?error=1");
    exit();
}
// If accessed directly, redirect to login page
header("Location: index.php");
exit();
?>