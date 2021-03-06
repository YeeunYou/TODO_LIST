<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script> 
	$(document).ready(function(){
		listTasks();

		$("#newForm").submit(function(event){
			event.preventDefault();
			//HTML required attribute does the validation but in case it is altered or fail to display error message, do alert
			if($("#description").val() == '')
			{
				alert("Please fill out description field!");
			}
			else if($("#priority").val() == '')
			{
				alert("Please select priority for the task!");
			}
			else if(($("#description").val() != '') && ($("#priority").val() != ''))
			{
				$.post("process.php?action=new", $(this).serialize(), function(data){
					checkradio();
				});
				$("#description").val('');
				$("#priority").val('');
			}
		}); 
 
		$("input:radio[name=display], input:radio[name=sort]").change(function(event){
			event.preventDefault();
			checkradio();
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
				var cvJSON = JSON.stringify(checkVal);
				completeTask(cvJSON);
			}
		});

		$("#delete").click(function(event){
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
				var cvJSON = JSON.stringify(checkVal);
				deleteTask(cvJSON);
			}
		});
	});

	function checkradio()
	{
		if($('input[name=display]:checked').val() == 'incomplete' && $('input[name=sort]:checked').val() == 'byPriority')
			{
				incPri();	
			}
			else if($('input[name=display]:checked').val() == 'incomplete' && $('input[name=sort]:checked').val() == 'byDate')
			{
				incDate();
			}
			else if($('input[name=display]:checked').val() == 'all' && $('input[name=sort]:checked').val() == 'byPriority')
			{
				allPri();
			}
			else if($('input[name=display]:checked').val() == 'all' && $('input[name=sort]:checked').val() == 'byDate')
			{
				allDate();
			}
	}

	function completeTask(json)
	{
		$.post("process.php?action=complete", {completeData:json}, function(data){
			refreshTable();
			for(var i =0; i<data.length; i++)
			{
				addTask(data[i]);
			}
			checkradio();
		});
	}

	function deleteTask(json)
	{
		$.post("process.php?action=delete", {deleteData:json}, function(data){
			refreshTable();
			for(var i =0; i<data.length; i++)
			{
				addTask(data[i]);
			}
			checkradio();
		});
	}

	function allDate()
	{
		$.get("process.php?action=list&sortby=datecreated&includecomplete=true", function(data){
			refreshTable();
			for(var i = 0; i < data.length; i++)
			{
				addTask(data[i]);
			}
		});
	}

	function allPri()
	{
		$.get("process.php?action=list&sortby=priority&includecomplete=true", function(data){
			refreshTable();
			for(var i = 0; i < data.length; i++)
			{
				addTask(data[i]);
			}
		});
	}

	function incDate()
	{
		listTasks();
	}

	function incPri()
	{
		$.get("process.php?action=list&sortby=priority&includecomplete=false", function(data){
			refreshTable();
			for(var i = 0; i < data.length; i++)
			{
				addTask(data[i]);
			}
		});
	}

	function addTask(data)
	{
		$("#taskList").append("<tr><td><input type='CheckBox' name='doSth[]' id='" + data.id + "' /></td><td>" + data.description + "</td><td>" + data.priority +"</td><td>" + data.dateCreated +"</td><td>" + data.dateCompleted + "</td></tr>");
	}

	function listTasks()
	{
		$('#incomplete, #byDate').attr('checked', true);
		$.get("process.php?action=list&sortby=datecreated&includecomplete=false", function(data){
			refreshTable();
			for(var i = 0; i < data.length; i++)
			{
				addTask(data[i]);
			}
		});
	}

	function refreshTable()
	{
		$("#taskList tr").remove();
			$("#taskList").append("<tr><th>CheckBox</th><th>Description</th><th>Priority</th><th>Date Created</th><th>Date Completed</th></tr>");
	}
</script>
<head>
<body>
<h1>My task list</h1>
	<table id="whatDisplay" border="1" border-collapse="collapse">
		<th>Task to Display</th>
		<tr><td><input type="radio" id="incomplete" value="incomplete" name="display">Show incomplete only</td></tr>
		<tr><td><input type="radio" id="all" value="all" name="display">Show all</td></tr>
	</table>
<br/>
	<table id="howDisplay"  border="1" border-collapse="collapse">
		<th>Sort Order</th>
		<tr><td><input type="radio" id="byDate" value="byDate" name="sort">Sort by date created</td></tr>
		<tr><td><input type="radio" id="byPriority" value="byPriority" name="sort">Sort by priority</td></tr>
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
