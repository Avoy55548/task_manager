<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "taskmanagement");

// Insert HR
if (isset($_POST['insert_hr'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $dob = trim($_POST['dob']);
    $location = trim($_POST['location']);
    $city = trim($_POST['city']);

    // Only insert if passwords match
    if ($password === $confirm_password) {
        $sql = "INSERT INTO hr (Name, Email, Password, DOB, Location, City) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $password, $dob, $location, $city);
        mysqli_stmt_execute($stmt);
        header("Location: admin.php?success=1");
        exit();
    } else {
        header("Location: admin.php?error=password");
        exit();
    }
}

// Update HR
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
    header("Location: admin.php?success=1");
    exit();
}

// Delete HR
if (isset($_POST['delete_hr_id'])) {
    $id = intval($_POST['delete_hr_id']);
    mysqli_query($con, "DELETE FROM hr WHERE id=$id");
    header("Location: admin.php?success=1");
    exit();
}

// Edit HR (show edit form)
$editHR = null;
if (isset($_POST['edit_hr_id'])) {
    $id = intval($_POST['edit_hr_id']);
    $result = mysqli_query($con, "SELECT * FROM hr WHERE id=$id");
    $editHR = mysqli_fetch_assoc($result);
}

// Insert Employee
if (isset($_POST['insert_employee'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $dob = trim($_POST['dob']);
    $location = trim($_POST['location']);
    $city = trim($_POST['city']);

    // Only insert if passwords match
    if ($password === $confirm_password) {
        $sql = "INSERT INTO employee (Name, Email, Password, DOB, Location, City) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $password, $dob, $location, $city);
        mysqli_stmt_execute($stmt);
        header("Location: admin.php?success=1");
        exit();
    } else {
        header("Location: admin.php?error=password");
        exit();
    }
}

// Delete Employee
if (isset($_POST['delete_employee_id'])) {
    $id = intval($_POST['delete_employee_id']);
    mysqli_query($con, "DELETE FROM employee WHERE id=$id");
    header("Location: admin.php?success=1");
    exit();
}

// Edit Employee (show edit form)
$editEmployee = null;
if (isset($_POST['edit_employee_id'])) {
    $id = intval($_POST['edit_employee_id']);
    $result = mysqli_query($con, "SELECT * FROM employee WHERE id=$id");
    $editEmployee = mysqli_fetch_assoc($result);
}

// Update Employee
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
    header("Location: admin.php?success=1");
    exit();
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
        <form method="post" action="admin.php" onsubmit="return validateAdminForm();">
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
            <button type="submit" name="insert_hr" class="btn btn-primary w-full mt-2">Submit</button>
        </form>
        <?php if (isset($_GET['success'])): ?>
            <div class="text-green-600 font-semibold mb-4">User added successfully!</div>
        <?php endif; ?>
        
        <!-- HR Table -->
        <div id="hrTable" style="display:block;">
            <h3 class="text-lg font-bold mb-4 text-blue-700">HR Table</h3>
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="table w-full bg-white">
                    <thead class="bg-blue-100 text-blue-800">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">DOB</th>
                            <th class="px-4 py-2">Location</th>
                            <th class="px-4 py-2">City</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hrList as $hr): ?>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($hr['Name']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($hr['Email']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($hr['DOB']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($hr['Location']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($hr['City']); ?></td>
                            <td class="px-4 py-2">
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
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Employee Table -->
        <div id="empTable" style="display:none;">
            <h3 class="text-lg font-bold mb-4 text-purple-700">Employee Table</h3>
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="table w-full bg-white">
                    <thead class="bg-purple-100 text-purple-800">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">DOB</th>
                            <th class="px-4 py-2">Location</th>
                            <th class="px-4 py-2">City</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empList as $emp): ?>
                        <tr class="hover:bg-purple-50 transition">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($emp['Name']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($emp['Email']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($emp['DOB']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($emp['Location']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($emp['City']); ?></td>
                            <td class="px-4 py-2">
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
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit HR Form (conditionally displayed) -->
        <?php if ($editHR): ?>
        <form method="post" class="w-full max-w-md bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4 text-blue-700">Edit HR</h2>
            <input type="hidden" name="update_hr_id" value="<?= $editHR['id'] ?>">
            <input type="text" name="update_hr_name" value="<?= htmlspecialchars($editHR['Name']) ?>" required class="input input-bordered w-full mb-1" />
            <span id="update_nameError" class="text-red-600 text-xs"></span>
            <input type="email" name="update_hr_email" value="<?= htmlspecialchars($editHR['Email']) ?>" required class="input input-bordered w-full mb-1" />
            <span id="update_emailError" class="text-red-600 text-xs"></span>
            <input type="password" name="update_hr_password" placeholder="New Password (leave blank to keep current)" class="input input-bordered w-full mb-1" />
            <span id="update_passwordError" class="text-red-600 text-xs"></span>
            <input type="password" name="update_hr_confirm_password" placeholder="Confirm New Password" class="input input-bordered w-full mb-1" />
            <span id="update_password2Error" class="text-red-600 text-xs"></span>
            <input type="date" name="update_hr_dob" value="<?= htmlspecialchars($editHR['DOB']) ?>" required class="input input-bordered w-full mb-1" />
            <span id="update_dobError" class="text-red-600 text-xs"></span>
            <input type="text" name="update_hr_location" value="<?= htmlspecialchars($editHR['Location']) ?>" required class="input input-bordered w-full mb-1" />
            <span id="update_locationError" class="text-red-600 text-xs"></span>
            <input type="text" name="update_hr_city" value="<?= htmlspecialchars($editHR['City']) ?>" required class="input input-bordered w-full mb-1" />
            <span id="update_cityError" class="text-red-600 text-xs"></span>
            <button type="submit" name="update_hr" class="btn btn-primary w-full mt-2">Update HR</button>
        </form>
        <?php endif; ?>

        <!-- Edit Employee Form (conditionally displayed) -->
        <?php if ($editEmployee): ?>
        <div style="margin: 20px 0; padding: 16px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; max-width: 400px;">
            <h4 style="color: #7c3aed; margin-bottom: 12px;">Update Employee</h4>
            <form method="post">
                <input type="hidden" name="update_employee_id" value="<?= $editEmployee['id'] ?>">
                <label style="display:block; margin-bottom:6px;">Name:
                    <input type="text" name="update_employee_name" value="<?= htmlspecialchars($editEmployee['Name']) ?>" required style="width:100%; margin-bottom:10px; padding:6px;">
                </label>
                <label style="display:block; margin-bottom:6px;">Email:
                    <input type="email" name="update_employee_email" value="<?= htmlspecialchars($editEmployee['Email']) ?>" required style="width:100%; margin-bottom:10px; padding:6px;">
                </label>
                <label style="display:block; margin-bottom:6px;">DOB:
                    <input type="date" name="update_employee_dob" value="<?= htmlspecialchars($editEmployee['DOB']) ?>" required style="width:100%; margin-bottom:10px; padding:6px;">
                </label>
                <label style="display:block; margin-bottom:6px;">Location:
                    <input type="text" name="update_employee_location" value="<?= htmlspecialchars($editEmployee['Location']) ?>" required style="width:100%; margin-bottom:10px; padding:6px;">
                </label>
                <label style="display:block; margin-bottom:6px;">City:
                    <input type="text" name="update_employee_city" value="<?= htmlspecialchars($editEmployee['City']) ?>" required style="width:100%; margin-bottom:10px; padding:6px;">
                </label>
                <button type="submit" name="update_employee" style="background:#7c3aed; color:#fff; border:none; padding:8px 18px; border-radius:4px; margin-top:8px;">Update Employee</button>
            </form>
        </div>
        <?php endif; ?>
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

    function validateForm() {
        // ...validation...
        return isValid; // Must return true to allow submission
    }
    </script>
</body>
</html>