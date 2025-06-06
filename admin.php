<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $dob = trim($_POST['dob']);
    $location = trim($_POST['location']);
    $city = trim($_POST['city']);

    // Only insert if passwords match
    if ($password === $confirm_password) {
        $con = mysqli_connect("localhost", "root", "", "taskmanagement");
        if ($type === 'HR') {
            $sql = "INSERT INTO hr (Name, Email, Password, DOB, Location, City) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $password, $dob, $location, $city);
            mysqli_stmt_execute($stmt);
        } elseif ($type === 'Employee') {
            $sql = "INSERT INTO employee (Name, Email, Password, DOB, Location, City) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $password, $dob, $location, $city);
            mysqli_stmt_execute($stmt);
        }
        mysqli_close($con);
        header("Location: admin.php?success=1");
        exit();
    } else {
        header("Location: admin.php?error=password");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
</head>
<body class="font-poppins">
    <div class="p-4 text-2xl font-bold text-white bg-gradient-to-r from-blue-500 to-purple-500 rounded-b-xl shadow-md text-center tracking-wide mb-4">
        Welcome Admin!
        <a href="logout.php" class="btn btn-error btn-sm float-right mt-2 mr-4" style="position:absolute;right:30px;top:10px;">Logout</a>
    </div>
    <section class="max-w-2xl mx-auto py-10 flex flex-col items-center gap-8">
        <div class="flex gap-4 mb-6">
            <button class="btn btn-primary" onclick="setFormType('HR')">Add HR</button>
            <button class="btn btn-primary" onclick="setFormType('Employee')">Add Employee</button>
        </div>
        <form method="post" class="w-full max-w-md bg-white p-6 rounded-lg shadow">
            <h2 id="formTitle" class="text-xl font-bold mb-4 text-blue-700">Add HR</h2>
            <input type="hidden" name="type" id="typeInput" value="HR" />
            <input type="text" name="name" placeholder="Name" required class="input input-bordered w-full mb-3" />
            <input type="email" name="email" placeholder="Email" required class="input input-bordered w-full mb-3" />
            <input type="password" name="password" placeholder="Password" required class="input input-bordered w-full mb-3" />
            <input type="password" name="confirm_password" placeholder="Confirm Password" required class="input input-bordered w-full mb-3" />
            <input type="date" name="dob" placeholder="DOB" required class="input input-bordered w-full mb-3" />
            <input type="text" name="location" placeholder="Location" required class="input input-bordered w-full mb-3" />
            <input type="text" name="city" placeholder="City" required class="input input-bordered w-full mb-3" />
            <button type="submit" class="btn btn-primary w-full mt-2">Submit</button>
        </form>
    </section>
    <script>
        function setFormType(type) {
            document.getElementById('formTitle').innerText = 'Add ' + type;
            document.getElementById('typeInput').value = type;
        }
    </script>
</body>
</html>