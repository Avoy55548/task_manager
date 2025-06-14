<?php
session_start();
if (!isset($_SESSION["userName"])) {
    header("Location: index.php");
    exit();
}

// Fetch tasks from the database
$con = mysqli_connect("localhost", "root", "", "taskmanagement");
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
    <title>Task Manager</title>
    <script
      src="https://kit.fontawesome.com/09c942dff7.js"
      crossorigin="anonymous"
    ></script>
    <link
      href="https://cdn.jsdelivr.net/npm/daisyui@5"
      rel="stylesheet"
      type="text/css"
    />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />
  </head>
  <body class="font-poppins">
    <div class="p-4 text-2xl font-bold text-white bg-gradient-to-r from-blue-500 to-purple-500 rounded-b-xl shadow-md text-center tracking-wide mb-4">
      Welcome, <span style="color: #ffd700; text-shadow: 1px 1px 6px #333;"><?php echo htmlspecialchars($_SESSION["userName"]); ?></span>!
    </div>
    <section class="max-w-7xl min-h-screen mx-auto py-6">
      <!-- Navbar -->
      <section class="my-10">
        <div class="navbar bg-base-100 shadow-sm rounded-md">
          <div class="flex-1">
            <div class="flex gap-3">
              <img
                src="./assets/logo.png"
                width="51.24px"
                height="51.24px"
                alt=""
              />
              <a class="text-2xl"
                >Task <span class="font-extrabold">Manager</span></a
              >
            </div>
          </div>
          <div class="flex-none">
            <div class="flex gap-15">
              <div class="flex gap-1">
                <img src="./assets/checkbox.png" alt="" srcset="" />
                <p id="nav-num" class="text-2xl font-bold"><?php echo count($tasks); ?></p>
              </div>
              <div>
                <form action="logout.php" method="post" style="display:inline;">
                    <button type="submit" class="btn btn-primary logout-btn text-white">Logout</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Main part -->
      <section class="flex justify-between gap-20">
        <section
          class="mt-10 mb-5 flex flex-col gap-20 w-full items-stretch justify-between"
        >
          <!-- Task and Date Section -->
          <div class="flex gap-5 justify-between">
            <div class="flex-1">
              <div
                class="flex gap-5 rounded-md justify-center items-center py-6 px-5 bg-gray-200"
              >
                <img src="./assets/checkbox.png" alt="" srcset="" />
                <div>
                  <p>Task Assigned</p>
                  <p id="task-num" class="font-bold text-2xl"><?php echo count($tasks); ?></p>
                </div>
              </div>
            </div>
              <div
                class="py-4 px-6 flex gap-5 bg-gradient-to-r from-blue-500 to-purple-200 rounded-md"
              >
                <img src="./assets/board.png" alt="" srcset="" />
                <p class="text-white my-5">
                  Discover Something <br />New Today!
                </p>
              </div>

            <div class="flex gap-5 mx-10 rounded-md p-8 bg-gray-200">
              <img src="./assets/calender.png" alt="" srcset="" />
              <!-- date -->
              <h1 id="date" class="font-bold text-xl"></h1>
            </div>
          </div>

          <!-- Cards Section-->
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12">
            <?php foreach ($tasks as $task): ?>
            <div class="p-5 rounded-lg bg-gray-200 w-72 h-52">
              <p class="font-bold"><?php echo htmlspecialchars($task['Task_Title']); ?></p>
              <p class="text-sm bg-white py-2 rounded-lg"><?php echo htmlspecialchars($task['Task_Desc']); ?></p>
              <div class="flex justify-between mt-3">
                <p>
                  Deadline <br />
                  <span class="font-bold"><?php echo htmlspecialchars($task['Deadline']); ?></span>
                </p>
                <button 
                  class="btn btn-primary complete-btn"
                  data-task-title="<?php echo htmlspecialchars($task['Task_Title']); ?>"
                  data-task-id="<?php echo $task['id']; ?>"
                >
                  Completed
                </button>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Activity Log -->
        <section class="px-5 pt-6 rounded-xl">
          <!-- activity log -->
          <div class="flex gap-3">
            <img src="./assets/activity.png" alt="" srcset="" />
            <p class="px-3 font-bold text-xl">Activity Log</p>
          </div>
          <!-- clear btn -->
          <br />
          <div>
            <button id="clear-btn" class="btn btn-active btn-primary">
              Clear History
            </button>
          </div>
          <br /><br />
          <!-- Activity History -->
          <div id="activity-log"></div>
        </section>
      </section>
    </section>
    <script src="date.js"></script>
    <script src="compete.js"></script>
  </body>
</html>
