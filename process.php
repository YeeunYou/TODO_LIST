<?php
header("Content-Type: application/json"); 
if(isset($_GET['action']))
{ 
	require_once('connect.php');
	$conn->select_db("lamp2proj1");

	if($_SERVER['REQUEST_METHOD'] == 'GET')
	{
		if($_GET['action'] == 'list')
		{
			listTasks($conn);
		}
		else if($_GET['sortby'] == 'priority')
		{
			byPriority($conn);
		}
	}
	else if($_SERVER['REQUEST_METHOD'] == 'POS')
	{
		if($_GET['action'] == 'new')
		{
			newTask($conn);
		}
	} 
	mysqli_close($conn);
}

function listTasks($conn)
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
?>