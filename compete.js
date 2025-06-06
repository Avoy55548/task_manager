document.addEventListener('DOMContentLoaded', function() {
    function getCurrentTime() {
        const today = new Date();
        const options = { hour: '2-digit', minute: '2-digit', hour12: true };
        return today.toLocaleTimeString('en-US', options);
    }

    document.querySelectorAll('.complete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            btn.disabled = true;

            // Update task and nav numbers
            const navNumElem = document.getElementById("nav-num");
            const taskNumElem = document.getElementById("task-num");
            if(navNumElem && taskNumElem) {
                const navNum = parseInt(navNumElem.innerText);
                const taskNum = parseInt(taskNumElem.innerText);

                if (taskNum === 1) {
                    alert("Congrats!! You have completed all the current task");
                }
                navNumElem.innerText = navNum + 1;
                taskNumElem.innerText = taskNum - 1;
            }

            // Get task title from data attribute
            const taskTitle = btn.getAttribute('data-task-title');

            // Add to activity log
            const log = document.getElementById("activity-log");
            if(log) {
                const p = document.createElement("p");
                const time = getCurrentTime();
                p.innerText = `You have completed the task ${taskTitle} at ${time}`;
                p.classList.add(
                    "bg-gray-100", "p-4", "mb-4", "rounded-lg", "font-medium"
                );
                log.appendChild(p);
            }
        });
    });

    // Only add event listener if the element exists
    const clearBtn = document.getElementById("clear-btn");
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            const log = document.getElementById("activity-log");
            if (log) log.innerText = '';
        });
    }

    
});