<?php
header("Content-Type: application/json");
if(isset($_GET['action']))
{
	require_once('connect.php');
	$conn->select_db("lamp2proj1");

	if($_GET['action'] == 'list' && $_SERVER['REQUEST_METHOD'] == 'GET')
	{
		listTasks($conn);
	}
	else if($_GET['action'] == 'new' && $_SERVER['REQUEST_METHOD'] == 'GET')
	{
		newTask($conn);
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
	/*$insert = $conn->query("INSERT INTO task(description, priority, dateCreated, completed, dateCompleted) VALUES ('$description', '$priority', NOW(), 0, '0000-00-00 00:00:00')");
	$select ="SELECT id, description, priority, dateCreated, dateCompleted  FROM task WHERE description='$description'";
	if($result = $conn->query($select))
	{
		while ($row = mysqli_fetch_assoc($result))
		{ 
			
		}
	}*/
} 
?>