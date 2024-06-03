<?php
	session_start();
	$todoList = array();
	$doneList = array(); // New array to store completed tasks

	if (isset($_SESSION["todoList"])) $todoList = $_SESSION["todoList"];
	if (isset($_SESSION["doneList"])) $doneList = $_SESSION["doneList"]; // Load done tasks from session

	function appendData($data)
	{
		return $data; // No changes needed here, data is already a string
	}
	
	function deleteData($toDelete, $todoList)
	{
		if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && $_GET['task'] === $toDelete)
		{
			foreach ($todoList as $todo => $taskName)
			{
				if ($taskName === $toDelete)
				{
					unset($todoList[$todo]);
					return $todoList;
				}
			}
		}
 
	else if (isset($_GET['task']) && $_GET['task'] === $toDelete)
		{
			echo '<div class="alert alert-warning">Are you sure you want to delete "' . $toDelete . '"?';
			echo '<a href="todo.php?confirm=true&task=' . $toDelete . '" class="btn btn-danger ml-2">Yes</a>';
			echo '<a href="todo.php" class="btn btn-secondary ml-2">No</a></div>';
		}
		return $todoList; // No changes made, return original list
	}

// New Function to Mark Tasks as Done
	function markDone($taskToMark, &$todoList, &$doneList) // Pass by reference for modification
	{
		// Find the index of the task in the array (if it exists)
		$taskIndex = array_search($taskToMark, $todoList);
		// Check if the task was found (index won't be false)
		if ($taskIndex !== false)
		{
			// Add the task to the done list with a "DONE - " prefix
			$doneList[] = "DONE - " . $todoList[$taskIndex];
			// Remove the task from the todo list
			unset($todoList[$taskIndex]);
		}
		// Return is not required here, functions modify by reference
	}
	
	if($_SERVER["REQUEST_METHOD"] =="POST")
	{
		if (empty( $_POST["task"] ))
		{
			echo '<script>alert("Error: there is no data to add in array")</script>';
			exit;
		}
 
		array_push($todoList, appendData($_POST["task"]));
		$_SESSION["todoList"] = $todoList;
	}

	if (isset($_GET['task']) && isset($_GET['markDone']))
	{
		// Check if the mark parameter is "true" using comparison operator
		if ($_GET['markDone'] === "true")
		{
			markDone($_GET['task'], $todoList, $doneList);
			$_SESSION["todoList"] = $todoList;
			$_SESSION["doneList"] = $doneList; // Save done list to session as well
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simple To-Do List</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1 class="text-center">To-Do List</h1>
    <div class="card">
      <div class="card-header">Add a new task</div>
      <div class="card-body">
        <form method="post" action="">
          <div class="form-group">
            <input type="text" class="form-control" name="task" placeholder="Enter your task here">
          </div>
          <button type="submit" class="btn btn-primary">Add Task</button>
        </form>
      </div>
    </div>
        <div class="card mt-4">
            <div class="card-header">Tasks</div>
            <ul class="list-group list-group-flush">
            <?php
                foreach ($todoList as $task) {
                    echo '<div class="d-flex p-2 bd-highlight w-100 justify-content-between"> <li class="list-group-item w-100">' . $task . '
					</li><a href="todo.php?toDelete=true&task=' . $task . '" class="btn btn-danger">Delete</a></div>';
                    echo '<div class="d-flex p-2 bd-highlight w-100 justify-content-between"> <li class="list-group-item w-100">' . $task . ' </li><a href="todo.php?markDone=true&task=' . $task . '" class="btn btn-success">Mark Done</a></div>';
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
