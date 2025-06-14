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

$con = mysqli_connect("localhost", "root", "", "taskmanagement");
$hrList = [];
$empList = [];
if ($con) {
    $hrResult = mysqli_query($con, "SELECT * FROM hr");
    while ($row = mysqli_fetch_assoc($hrResult)) {
        $hrList[] = $row;
    }
    $empResult = mysqli_query($con, "SELECT * FROM employee");
    while ($row = mysqli_fetch_assoc($empResult)) {
        $empList[] = $row;
    }
    mysqli_close($con);
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
        <form method="post" action="admin.php" class="w-full max-w-md bg-white p-6 rounded-lg shadow" onsubmit="return handleAdminSubmit(event)">
            <h2 id="formTitle" class="text-xl font-bold mb-4 text-blue-700">Add HR</h2>
            <input type="hidden" name="type" id="typeInput" value="HR" />
            <input type="text" id="name" name="name" placeholder="Name" required class="input input-bordered w-full mb-1" />
            <span id="nameError" class="text-red-600 text-xs"></span>
            <input type="email" id="email" name="email" placeholder="Email" required class="input input-bordered w-full mb-1" />
            <span id="emailError" class="text-red-600 text-xs"></span>
            <input type="password" id="password" name="password" placeholder="Password" required class="input input-bordered w-full mb-1" />
            <span id="password1Error" class="text-red-600 text-xs"></span>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required class="input input-bordered w-full mb-1" />
            <span id="password2Error" class="text-red-600 text-xs"></span>
            <input type="date" id="dob" name="dob" placeholder="DOB" required class="input input-bordered w-full mb-1" />
            <span id="dobError" class="text-red-600 text-xs"></span>
            <input type="text" id="location" name="location" placeholder="Location" required class="input input-bordered w-full mb-1" />
            <span id="locationError" class="text-red-600 text-xs"></span>
            <input type="text" id="city" name="city" placeholder="City" required class="input input-bordered w-full mb-1" />
            <span id="cityError" class="text-red-600 text-xs"></span>
            <button type="submit" class="btn btn-primary w-full mt-2">Submit</button>
        </form>
        <?php if (isset($_GET['success'])): ?>
            <div class="text-green-600 font-semibold mb-4">User added successfully!</div>
        <?php endif; ?>
        
        <div id="hrTable" style="display:block;">
            <h3 class="text-lg font-bold mb-2">HR Table</h3>
            <div class="overflow-x-auto">
                <table class="table w-full mb-8">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>DOB</th>
                            <th>Location</th>
                            <th>City</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hrList as $hr): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($hr['Name']); ?></td>
                            <td><?php echo htmlspecialchars($hr['Email']); ?></td>
                            <td><?php echo htmlspecialchars($hr['DOB']); ?></td>
                            <td><?php echo htmlspecialchars($hr['Location']); ?></td>
                            <td><?php echo htmlspecialchars($hr['City']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="empTable" style="display:none;">
            <h3 class="text-lg font-bold mb-2">Employee Table</h3>
            <div class="overflow-x-auto">
                <table class="table w-full mb-8">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>DOB</th>
                            <th>Location</th>
                            <th>City</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empList as $emp): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($emp['Name']); ?></td>
                            <td><?php echo htmlspecialchars($emp['Email']); ?></td>
                            <td><?php echo htmlspecialchars($emp['DOB']); ?></td>
                            <td><?php echo htmlspecialchars($emp['Location']); ?></td>
                            <td><?php echo htmlspecialchars($emp['City']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <script src="verification.js"></script>
    <script>
    function handleAdminSubmit(event) {
        if (!validateAdminForm()) {
            event.preventDefault();
            return false;
        }
        // If valid, let the form submit to verification.php
        return true;
    }
    function setFormType(type) {
        document.getElementById('formTitle').innerText = 'Add ' + type;
        document.getElementById('typeInput').value = type;
        if (type === 'HR') {
            document.getElementById('hrTable').style.display = 'block';
            document.getElementById('empTable').style.display = 'none';
        } else {
            document.getElementById('hrTable').style.display = 'none';
            document.getElementById('empTable').style.display = 'block';
        }
    }
    </script>
</body>
</html>