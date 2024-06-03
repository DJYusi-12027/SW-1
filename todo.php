<?php
	session_start();
	$todoList = array();
	$doneList = array(); // data structure for finished tasks

	if (isset($_SESSION["todoList"])) $todoList = $_SESSION["todoList"];
	if (isset($_SESSION["doneList"])) $doneList = $_SESSION["doneList"];

	function appendData($data)
	{
		return $data;
	}
	
	function deleteData($toDelete, $todoList) //edited to have more operators
	{
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'true' && $_GET['task'] === $toDelete)
	{
      foreach ($todoList as $index => $taskName)
	  {
        if ($taskName === $toDelete)
		{
          unset($todoList[$index]);
          return $todoList;
        }
      }
    } else if (isset($_GET['task']) && $_GET['task'] === $toDelete)
	{
      echo '<div class="alert alert-warning">Are you sure you want to delete "' . $toDelete . '"?';
      echo '<a href="todotest.php?confirm=true&task=' . $toDelete . '" class="btn btn-danger ml-2">Yes</a>';
      echo '<a href="todotest.php" class="btn btn-secondary ml-2">No</a></div>';
    }
    return $todoList;
  }

// added function
	function markDone($taskToMark, &$todoList, &$doneList)
	{
		$taskIndex = array_search($taskToMark, $todoList);
		{
			// predefined functions (date and unset)
			$currentTime = date("Y-m-d H:i:s");
			$doneList[] = "DONE - " . $currentTime . ": " . $todoList[$taskIndex];
			unset($todoList[$taskIndex]);
		}
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
		if ($_GET['markDone'] === "true")
		{
			markDone($_GET['task'], $todoList, $doneList);
			$_SESSION["todoList"] = $todoList;
			$_SESSION["doneList"] = $doneList;
			echo '<div class="alert alert-success">Task successfully marked as done!</div>'; // task done message
		}
	}
	
	if (isset($_GET['task']))
	{
		$todoList = deleteData($_GET['task'], $todoList);
		$_SESSION["todoList"] = $todoList; // deletes task when finished or deleted by choice
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
                foreach ($todoList as $task)
				{
                    echo '<div class="d-flex p-2 bd-highlight w-100 justify-content-between"> <li class="list-group-item w-100">' . $task . '
					</li><a href="todotest.php?delete=true&task=' . $task . '" class="btn btn-danger">Delete</a><a href="todotest.php?markDone=true&task=' . $task . '" class="btn btn-success">Mark Done</a></div>';
                }
            ?>
         </ul>
	</div>		
	<div class="card mt-5">
	<div class="card-header">Completed Tasks</div>
		<ul class="list-group list-group-flush">
			<?php
				foreach ($doneList as $doneTask)
				{
					echo '<div class="d-flex p-2 bd-highlight w-100 justify-content-between"> <li class="list-group-item w-100">' . $doneTask . '</li></div>';
				}
			?>
		</ul>
	</div>
	  
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
