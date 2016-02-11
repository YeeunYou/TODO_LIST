<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>
<script>
	function addPost(data)
	{
		$("#taskList").append("<tr><td><input type='CheckBox' name='doSth[]' id='" + data.id + "' /></td><td>" + data.description + "</td><td>" + data.priority +"</td><td>" + data.dateCreated +"</td><td>" + data.dateCompleted + "</td></tr>");
	}

	function listTasks()
	{
		$.get("process.php?action=list", function(data){
			for(var i = 0; i < data.length; i++)
			{
				addPost(data[i]);
			}
		});
	}

	function newTask()
	{
		
	}


	$(document).ready(function(){
		listTasks();

		$("#newForm").submit(function(event){
			event.preventDefault(); 
			if($("#newForm").valid())
			{ 
				alert($(this).serialize());
 				$.post("process.php?action=new", $(this).serialize(), newTask);
 				$("#description").val('');
 				$("#priority").val('');
			}
		}); 

		$("#complete").click(function(event){
			event.preventDefault();
			var checkNum = $('input[name="doSth[]"]:checked').length;
			if(!checkNum)
			{
				alert("Please select at least one task!");
			}
			else
			{
				var checkVal = [];
				$("input[type=checkbox]:checked").each(function(i){
					checkVal[i] = $(this).attr('id');
				});
				//alert(checkVal);
				
			}
		});
	});
</script>
<head>
<body>
<h1>My task list</h1>
	<table id="whatDisplay" border="1" border-collapse="collapse">
		<th>Task to Display</th>
		<tr><td><input type="radio" name="incomplete">Show incomplete only</td></tr>
		<tr><td><input type="radio" name="all">Show all</td></tr>
	</table>
<br/>
	<table id="howDisplay"  border="1" border-collapse="collapse">
		<th>Sort Order</th>
		<tr><td><input type="radio" name="dateCreated">Sort by date created</td></tr>
		<tr><td><input type="radio" name="priority">Sort by priority</td></tr>
	</table>
<br/> 
	<table id="taskList" style="border: 1px solid black" border-collapse="collapse">
		<tr>
			<th>CheckBox</th>
			<th>Description</th>
			<th>Priority</th>
			<th>Date Created</th>
			<th>Date Completed</th>
		</tr>
	</table>
	<br/>
	<input type="submit" name="complete" id="complete" value="Mark as Complete" />
	<input type="submit" name="delete" id="delete" value="Delete task" />
<br/>

<form id="newForm" autocomplete="off" >
	<h3>Add a new task</h3>
	Task: <input type="text" id="description" name="description"  required /><br/><br/>
	Priority: <select id="priority" name="priority"  required>
		<option disabled selected value="" ></option>
		<option  value="1">1</option>
		<option  value="2">2</option>
		<option  value="3">3</option>
		<option  value="4">4</option>
		<option  value="5">5</option>
	</select><br/><br/>
	<input type="submit" name="add" value="New Task">
</form>
</body>
