<?php
header("Content-Type: application/json"); 
require_once('connect.php');
$conn->select_db("lamp2proj1");

if(isset($_GET['action']) && !empty($_GET['action']))
{ 
	if($_GET['action'] == 'list' && $_SERVER['REQUEST_METHOD'] == 'GET')
	{
		listTasks($conn);
	}
	else if($_GET['action'] == 'new' && $_SERVER['REQUEST_METHOD'] == 'POST')
	{
		newTask($conn);
	}  
}
else if(isset($_GET['sortby']))
{
	if($_GET['sortby'] == 'priority' && $_SERVER['REQUEST_METHOD'] == 'GET')
	{
		prioritySort($conn);
	}
	else if($_GET['sortby'] == 'datecreated' && $_SERVER['REQUEST_METHOD'] == 'GET')
	{
		listTasks($conn);
	}
}
else if(isset($_GET['includecompleted']))
{
	if($_GET['includecompleted'] == 'false' && $_SERVER['REQUEST_METHOD'] == 'GET')
	{
		listTasks($conn);
	}
	else if($_GET['includecompleted'] == 'true' && $_SERVER['REQUEST_METHOD'] == 'GET')
	{
		listTasksAll($conn);
	}
}

function listTasksAll($conn)
{
	$taskArr = array();
	$query = "SELECT id, description, priority, dateCreated, dateCompleted FROM task ORDER BY dateCreated ASC";
	if($result = $conn->query($query))
	{
		while($task = mysqli_fetch_assoc($result))
		{
			array_push($taskArr, $task);
		}
		echo json_encode($taskArr);
	}
}

function listTasks($conn)
{
	$taskArr = array();
	$query = "SELECT id, description, priority, dateCreated, dateCompleted FROM task WHERE dateCompleted = '0000-00-00 00:00:00' ORDER BY dateCreated ASC";
	if($result = $conn->query($query))
	{
		while($task = mysqli_fetch_assoc($result))
		{
			array_push($taskArr, $task);
		}
		echo json_encode($taskArr);
	}
}

function newTask($conn)
{
	$description = $_POST['description'];
	$priority = $_POST['priority'];
	$taskArr = array();
	
	$insert = $conn->query("INSERT INTO task(description, priority, dateCreated, completed, dateCompleted) VALUES ('" . $description ."','" . $priority . "' , NOW(), 0, '0000-00-00 00:00:00')");
	$conn->query($insert);
	
		$countQry = sprintf("SELECT count(id) AS count FROM task");
		$countRS = mysqli_query($conn, $countQry);
		$lastID = $countRS->fetch_object()->count;
	
		$query = "SELECT id, description, priority, dateCreated, dateCompleted FROM task WHERE id = $lastID ORDER BY dateCreated ASC";
		if($result = $conn->query($query))
		{
			while($task = mysqli_fetch_assoc($result))
			{
				array_push($taskArr, $task);
			}
			echo json_encode($taskArr);
		}
}

function prioritySort($conn)
{
	$taskArr = array();
	$query = "SELECT id, description, priority, dateCreated, dateCompleted FROM task WHERE dateCompleted = '0000-00-00 00:00:00' ORDER BY priority ASC";
	if($result = $conn->query($query))
	{
		while($task = mysqli_fetch_assoc($result))
		{
			array_push($taskArr, $task);
		}
		echo json_encode($taskArr);
	}
}
  
?>