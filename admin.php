<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "taskmanagement");

// --- HR Actions ---
if (isset($_POST['insert_hr'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $dob = trim($_POST['dob']);
    $location = trim($_POST['location']);
    $city = trim($_POST['city']);
    if ($password === $confirm_password) {
        $sql = "INSERT INTO hr (Name, Email, Password, DOB, Location, City) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $password, $dob, $location, $city);
        mysqli_stmt_execute($stmt);
        header("Location: admin.php?tab=hr&success=1");
        exit();
    } else {
        header("Location: admin.php?tab=hr&error=password");
        exit();
    }
}
if (isset($_POST['edit_hr_id'])) {
    $id = intval($_POST['edit_hr_id']);
    $result = mysqli_query($con, "SELECT * FROM hr WHERE id=$id");
    $editHR = mysqli_fetch_assoc($result);
}
if (isset($_POST['update_hr'])) {
    $id = intval($_POST['update_hr_id']);
    $name = trim($_POST['update_hr_name']);
    $email = trim($_POST['update_hr_email']);
    $dob = trim($_POST['update_hr_dob']);
    $location = trim($_POST['update_hr_location']);
    $city = trim($_POST['update_hr_city']);
    $sql = "UPDATE hr SET Name=?, Email=?, DOB=?, Location=?, City=? WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sssssi", $name, $email, $dob, $location, $city, $id);
    mysqli_stmt_execute($stmt);
    header("Location: admin.php?tab=hr&success=1");
    exit();
}
if (isset($_POST['delete_hr_id'])) {
    $id = intval($_POST['delete_hr_id']);
    mysqli_query($con, "DELETE FROM hr WHERE id=$id");
    header("Location: admin.php?tab=hr&success=1");
    exit();
}

// --- Employee Actions ---
if (isset($_POST['insert_employee'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $dob = trim($_POST['dob']);
    $location = trim($_POST['location']);
    $city = trim($_POST['city']);
    if ($password === $confirm_password) {
        $sql = "INSERT INTO employee (Name, Email, Password, DOB, Location, City) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $password, $dob, $location, $city);
        mysqli_stmt_execute($stmt);
        header("Location: admin.php?tab=employee&success=1");
        exit();
    } else {
        header("Location: admin.php?tab=employee&error=password");
        exit();
    }
}
if (isset($_POST['edit_employee_id'])) {
    $id = intval($_POST['edit_employee_id']);
    $result = mysqli_query($con, "SELECT * FROM employee WHERE id=$id");
    $editEmployee = mysqli_fetch_assoc($result);
}
if (isset($_POST['update_employee'])) {
    $id = intval($_POST['update_employee_id']);
    $name = trim($_POST['update_employee_name']);
    $email = trim($_POST['update_employee_email']);
    $dob = trim($_POST['update_employee_dob']);
    $location = trim($_POST['update_employee_location']);
    $city = trim($_POST['update_employee_city']);
    $sql = "UPDATE employee SET Name=?, Email=?, DOB=?, Location=?, City=? WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sssssi", $name, $email, $dob, $location, $city, $id);
    mysqli_stmt_execute($stmt);
    header("Location: admin.php?tab=employee&success=1");
    exit();
}
if (isset($_POST['delete_employee_id'])) {
    $id = intval($_POST['delete_employee_id']);
    mysqli_query($con, "DELETE FROM employee WHERE id=$id");
    header("Location: admin.php?tab=employee&success=1");
    exit();
}

// Fetch data
$hrList = [];
$empList = [];
$hrResult = mysqli_query($con, "SELECT * FROM hr");
while ($row = mysqli_fetch_assoc($hrResult)) $hrList[] = $row;
$empResult = mysqli_query($con, "SELECT * FROM employee");
while ($row = mysqli_fetch_assoc($empResult)) $empList[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="verification.js"></script>
    <style>
        .tab-btn.active { background: #6366f1; color: #fff; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>
    <div class="p-4 text-2xl font-bold text-white bg-gradient-to-r from-blue-500 to-purple-500 rounded-b-xl shadow-md text-center tracking-wide mb-4">
        Welcome Admin!
        <a href="logout.php" class="btn btn-error btn-sm float-right mt-2 mr-4" style="position:absolute;right:30px;top:10px;">Logout</a>
    </div>
    <div class="max-w-4xl mx-auto py-10">
        <div class="flex gap-4 mb-6">
            <button class="tab-btn btn btn-primary" id="hrTabBtn" onclick="showTab('hr')">HR</button>
            <button class="tab-btn btn btn-primary" id="employeeTabBtn" onclick="showTab('employee')">Employee</button>
        </div>

        <!-- HR Section -->
        <div id="hrTab" class="tab-content">
            <h2 class="text-xl font-bold mb-4 text-blue-700">Manage HR</h2>
            <?php if (isset($_GET['tab']) && $_GET['tab'] === 'hr' && isset($_GET['success'])): ?>
                <div class="text-green-600 font-semibold mb-4">Operation successful!</div>
            <?php endif; ?>
            <?php if (isset($_GET['tab']) && $_GET['tab'] === 'hr' && isset($_GET['error']) && $_GET['error'] === 'password'): ?>
                <div class="text-red-600 font-semibold mb-4">Passwords do not match!</div>
            <?php endif; ?>
            <!-- Add HR Form -->
            <form method="post" action="admin.php?tab=hr" onsubmit="return validateAdminForm();" class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-lg border border-blue-100 mb-8">
                <h3 class="text-2xl font-bold mb-6 text-blue-700 text-center">Add HR</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-1">Name</label>
                        <input type="text" id="name" name="name" class="input input-bordered w-full" required>
                        <span id="nameError" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" id="email" name="email" class="input input-bordered w-full" required>
                        <span id="emailError" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium mb-1">Password</label>
                        <input type="password" id="password" name="password" class="input input-bordered w-full" required>
                        <span id="password1Error" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium mb-1">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="input input-bordered w-full" required>
                        <span id="password2Error" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="dob" class="block text-sm font-medium mb-1">Date of Birth</label>
                        <input type="date" id="dob" name="dob" class="input input-bordered w-full" required>
                        <span id="dobError" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium mb-1">Location</label>
                        <input type="text" id="location" name="location" class="input input-bordered w-full" required>
                        <span id="locationError" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium mb-1">City</label>
                        <input type="text" id="city" name="city" class="input input-bordered w-full" required>
                        <span id="cityError" class="text-red-600 text-xs"></span>
                    </div>
                </div>
                <button type="submit" name="insert_hr" class="btn btn-primary w-full mt-6">Add HR</button>
            </form>
            <!-- HR Table -->
            <div class="overflow-x-auto rounded-lg shadow mt-8">
                <table class="table w-full bg-white">
                    <thead class="bg-blue-100 text-blue-800">
                        <tr>
                            <th>Name</th><th>Email</th><th>DOB</th><th>Location</th><th>City</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hrList as $hr): ?>
                        <?php if (isset($editHR) && $editHR['id'] == $hr['id']): ?>
                        <!-- Edit HR Row -->
                        <tr style="background: #f3e8ff;">
                            <form method="post">
                                <input type="hidden" name="update_hr_id" value="<?= $editHR['id'] ?>">
                                <td><input type="text" name="update_hr_name" value="<?= htmlspecialchars($editHR['Name']) ?>" required class="input input-bordered input-xs w-full" /></td>
                                <td><input type="email" name="update_hr_email" value="<?= htmlspecialchars($editHR['Email']) ?>" required class="input input-bordered input-xs w-full" /></td>
                                <td><input type="date" name="update_hr_dob" value="<?= htmlspecialchars($editHR['DOB']) ?>" required class="input input-bordered input-xs w-full" /></td>
                                <td><input type="text" name="update_hr_location" value="<?= htmlspecialchars($editHR['Location']) ?>" required class="input input-bordered input-xs w-full" /></td>
                                <td><input type="text" name="update_hr_city" value="<?= htmlspecialchars($editHR['City']) ?>" required class="input input-bordered input-xs w-full" /></td>
                                <td>
                                    <button type="submit" name="update_hr" class="btn btn-success btn-xs">Save</button>
                                    <a href="admin.php?tab=hr" class="btn btn-secondary btn-xs">Cancel</a>
                                </td>
                            </form>
                        </tr>
                        <?php else: ?>
                        <tr>
                            <td><?= htmlspecialchars($hr['Name']) ?></td>
                            <td><?= htmlspecialchars($hr['Email']) ?></td>
                            <td><?= htmlspecialchars($hr['DOB']) ?></td>
                            <td><?= htmlspecialchars($hr['Location']) ?></td>
                            <td><?= htmlspecialchars($hr['City']) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="edit_hr_id" value="<?= $hr['id'] ?>">
                                    <button type="submit" name="edit_hr" class="btn btn-warning btn-xs">Edit</button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete_hr_id" value="<?= $hr['id'] ?>">
                                    <button type="submit" name="delete_hr" class="btn btn-error btn-xs" onclick="return confirm('Delete this HR?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Employee Section -->
        <div id="employeeTab" class="tab-content">
            <h2 class="text-xl font-bold mb-4 text-purple-700">Manage Employee</h2>
            <?php if (isset($_GET['tab']) && $_GET['tab'] === 'employee' && isset($_GET['success'])): ?>
                <div class="text-green-600 font-semibold mb-4">Operation successful!</div>
            <?php endif; ?>
            <?php if (isset($_GET['tab']) && $_GET['tab'] === 'employee' && isset($_GET['error']) && $_GET['error'] === 'password'): ?>
                <div class="text-red-600 font-semibold mb-4">Passwords do not match!</div>
            <?php endif; ?>
            <!-- Add Employee Form -->
            <form method="post" action="admin.php?tab=employee" onsubmit="return validateEmployeeForm();" class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-lg border border-purple-100 mb-8">
                <h3 class="text-2xl font-bold mb-6 text-purple-700 text-center">Add Employee</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="emp_name" class="block text-sm font-medium mb-1">Name</label>
                        <input type="text" id="emp_name" name="name" class="input input-bordered w-full" required>
                        <span id="emp_nameError" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="emp_email" class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" id="emp_email" name="email" class="input input-bordered w-full" required>
                        <span id="emp_emailError" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="emp_password" class="block text-sm font-medium mb-1">Password</label>
                        <input type="password" id="emp_password" name="password" class="input input-bordered w-full" required>
                        <span id="emp_password1Error" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="emp_confirm_password" class="block text-sm font-medium mb-1">Confirm Password</label>
                        <input type="password" id="emp_confirm_password" name="confirm_password" class="input input-bordered w-full" required>
                        <span id="emp_password2Error" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="emp_dob" class="block text-sm font-medium mb-1">Date of Birth</label>
                        <input type="date" id="emp_dob" name="dob" class="input input-bordered w-full" required>
                        <span id="emp_dobError" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="emp_location" class="block text-sm font-medium mb-1">Location</label>
                        <input type="text" id="emp_location" name="location" class="input input-bordered w-full" required>
                        <span id="emp_locationError" class="text-red-600 text-xs"></span>
                    </div>
                    <div>
                        <label for="emp_city" class="block text-sm font-medium mb-1">City</label>
                        <input type="text" id="emp_city" name="city" class="input input-bordered w-full" required>
                        <span id="emp_cityError" class="text-red-600 text-xs"></span>
                    </div>
                </div>
                <button type="submit" name="insert_employee" class="btn btn-primary w-full mt-6">Add Employee</button>
            </form>
            <!-- Employee Table -->
            <div class="overflow-x-auto rounded-lg shadow mt-8">
                <table class="table w-full bg-white">
                    <thead class="bg-purple-100 text-purple-800">
                        <tr>
                            <th>Name</th><th>Email</th><th>DOB</th><th>Location</th><th>City</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empList as $emp): ?>
                        <?php if (isset($editEmployee) && $editEmployee['id'] == $emp['id']): ?>
                        <!-- Edit Employee Row -->
                        <tr style="background: #ede9fe;">
                            <form method="post">
                                <input type="hidden" name="update_employee_id" value="<?= $editEmployee['id'] ?>">
                                <td><input type="text" name="update_employee_name" value="<?= htmlspecialchars($editEmployee['Name']) ?>" required class="input input-bordered input-xs w-full" /></td>
                                <td><input type="email" name="update_employee_email" value="<?= htmlspecialchars($editEmployee['Email']) ?>" required class="input input-bordered input-xs w-full" /></td>
                                <td><input type="date" name="update_employee_dob" value="<?= htmlspecialchars($editEmployee['DOB']) ?>" required class="input input-bordered input-xs w-full" /></td>
                                <td><input type="text" name="update_employee_location" value="<?= htmlspecialchars($editEmployee['Location']) ?>" required class="input input-bordered input-xs w-full" /></td>
                                <td><input type="text" name="update_employee_city" value="<?= htmlspecialchars($editEmployee['City']) ?>" required class="input input-bordered input-xs w-full" /></td>
                                <td>
                                    <button type="submit" name="update_employee" class="btn btn-success btn-xs">Save</button>
                                    <a href="admin.php?tab=employee" class="btn btn-secondary btn-xs">Cancel</a>
                                </td>
                            </form>
                        </tr>
                        <?php else: ?>
                        <tr>
                            <td><?= htmlspecialchars($emp['Name']) ?></td>
                            <td><?= htmlspecialchars($emp['Email']) ?></td>
                            <td><?= htmlspecialchars($emp['DOB']) ?></td>
                            <td><?= htmlspecialchars($emp['Location']) ?></td>
                            <td><?= htmlspecialchars($emp['City']) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="edit_employee_id" value="<?= $emp['id'] ?>">
                                    <button type="submit" name="edit_employee" class="btn btn-warning btn-xs">Edit</button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete_employee_id" value="<?= $emp['id'] ?>">
                                    <button type="submit" name="delete_employee" class="btn btn-error btn-xs" onclick="return confirm('Delete this Employee?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        // Tab switching logic
        function showTab(tab) {
            document.getElementById('hrTab').classList.remove('active');
            document.getElementById('employeeTab').classList.remove('active');
            document.getElementById('hrTabBtn').classList.remove('active');
            document.getElementById('employeeTabBtn').classList.remove('active');
            if (tab === 'hr') {
                document.getElementById('hrTab').classList.add('active');
                document.getElementById('hrTabBtn').classList.add('active');
            } else {
                document.getElementById('employeeTab').classList.add('active');
                document.getElementById('employeeTabBtn').classList.add('active');
            }
        }
        // On page load, show correct tab
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab') || 'hr';
            showTab(tab);
        }
    </script>
</body>
</html>