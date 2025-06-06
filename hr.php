<?php
session_start();
if (!isset($_SESSION["userName"]) || $_SESSION["userType"] !== "hr") {
    header("Location: index.php?login_needed=1");
    exit();
}

$con = mysqli_connect("localhost", "root", "", "taskmanagement");

// Add Task
if (isset($_POST['add_task'])) {
    $title = trim($_POST['task_title']);
    $desc = trim($_POST['task_desc']);
    $deadline = trim($_POST['deadline']);
    $sql = "INSERT INTO task (Task_Title, Task_Desc, Deadline) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $title, $desc, $deadline);
    mysqli_stmt_execute($stmt);
    header("Location: hr.php");
    exit();
}

// Delete Task
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM task WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: hr.php");
    exit();
}

// Edit Task
if (isset($_POST['edit_task'])) {
    $id = intval($_POST['task_id']);
    $title = trim($_POST['task_title']);
    $desc = trim($_POST['task_desc']);
    $deadline = trim($_POST['deadline']);
    $sql = "UPDATE task SET Task_Title=?, Task_Desc=?, Deadline=? WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $title, $desc, $deadline, $id);
    mysqli_stmt_execute($stmt);
    header("Location: hr.php");
    exit();
}

// Fetch tasks
$tasks = [];
if ($con) {
    $sql = "SELECT id, Task_Title, Task_Desc, Deadline FROM task";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $tasks[] = $row;
    }
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HR Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
</head>
<body class="font-poppins">
    <div class="p-4 text-2xl font-bold text-white bg-gradient-to-r from-blue-500 to-purple-500 rounded-b-xl shadow-md text-center tracking-wide mb-4">
      Welcome HR, <span style="color: #ffd700; text-shadow: 1px 1px 6px #333;"><?php echo htmlspecialchars($_SESSION["userName"]); ?></span>!
      <a href="logout.php" class="btn btn-error btn-sm float-right mt-2 mr-4" style="position:absolute;right:30px;top:10px;">Logout</a>
    </div>
    <section class="max-w-7xl min-h-screen mx-auto py-6">
        <!-- Add Task Form -->
        <div class="mb-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4 text-blue-700">Add New Task</h2>
            <form method="post" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <input type="text" name="task_title" placeholder="Task Title" required class="input input-bordered w-full max-w-xs" />
                <input type="text" name="task_desc" placeholder="Task Description" required class="input input-bordered w-full max-w-xs" />
                <input type="date" name="deadline" required class="input input-bordered w-full max-w-xs" />
                <button type="submit" name="add_task" class="btn btn-primary">Add Task</button>
            </form>
        </div>
        <!-- Task List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12">
            <?php foreach ($tasks as $task): ?>
            <div class="p-5 rounded-lg bg-gray-200 w-72 h-64 flex flex-col justify-between">
                <!-- Task Info -->
                <div id="task-info-<?php echo $task['id']; ?>">
                    <p class="font-bold text-lg"><?php echo htmlspecialchars($task['Task_Title']); ?></p>
                    <p class="text-sm bg-white py-2 rounded-lg mt-2"><?php echo htmlspecialchars($task['Task_Desc']); ?></p>
                    <p class="mt-2">Deadline: <span class="font-bold"><?php echo htmlspecialchars($task['Deadline']); ?></span></p>
                    <div class="flex gap-2 mt-4">
                        <button class="btn btn-warning btn-sm" onclick="showEditForm(<?php echo $task['id']; ?>)">Edit</button>
                        <a href="hr.php?delete=<?php echo $task['id']; ?>" class="btn btn-error btn-sm" onclick="return confirm('Delete this task?')">Delete</a>
                    </div>
                </div>
                <!-- Edit Form-->
                <div id="edit-form-<?php echo $task['id']; ?>" style="display:none;">
                    <form method="post" class="mt-4">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <input type="text" name="task_title" value="<?php echo htmlspecialchars($task['Task_Title']); ?>" required class="input input-bordered w-full max-w-xs mb-2" />
                        <input type="text" name="task_desc" value="<?php echo htmlspecialchars($task['Task_Desc']); ?>" required class="input input-bordered w-full max-w-xs mb-2" />
                        <input type="date" name="deadline" value="<?php echo htmlspecialchars($task['Deadline']); ?>" required class="input input-bordered w-full max-w-xs mb-2" />
                        <button type="submit" name="edit_task" class="btn btn-success btn-sm">Save</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="hideEditForm(<?php echo $task['id']; ?>)">Cancel</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <script>
        function showEditForm(id) {
            document.getElementById('task-info-' + id).style.display = 'none';
            document.getElementById('edit-form-' + id).style.display = 'block';
        }
        function hideEditForm(id) {
            document.getElementById('edit-form-' + id).style.display = 'none';
            document.getElementById('task-info-' + id).style.display = 'block';
        }
    </script>
</body>
</html>