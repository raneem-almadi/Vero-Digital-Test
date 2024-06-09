<?php

$connectionHeader = [
	"Authorization: Basic QVBJX0V4cGxvcmVyOjEyMzQ1NmlzQUxhbWVQYXNz",
	"Content-Type: application/json"
];
$userCredentials = [
	'username' => '365',
	'password' => '1'
];

include 'functions.php';

//initial load
$dataset = getDataFromServer($connectionHeader, $userCredentials);

// Check if the request is an AJAX request
if (isset($_GET['action']) && $_GET['action'] == 'fetchData') {
    echo json_encode(getDataFromServer($connectionHeader, $userCredentials));
    exit;
}

?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Vero Digital Solutions Test</title>
		<link rel="stylesheet" href="style.css">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script type="text/javascript" src="myScript.js"></script>
	</head>
	<body>
		<!-- Search Field -->
		<input type="text" id="keyword" onkeyup="searchTable()" placeholder="Search...">
		
		<!-- Table of Details -->
		<table id="datasetTable">
			<thead>
				<tr>
					<th>Task</th>
					<th>Title</th>
					<th>Description</th>
					<th>Color Code</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($dataset as $item){ ?>
				<tr>
					<td><?= $item->task ?></td>
					<td><?= $item->title ?></td>
					<td><?= $item->description ?></td>
					<td style="color:<?= $item->colorCode ?>"><?= $item->colorCode ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		

		<!-- Modal -->
		<button class="btn" onclick="openModal()">Modal</button>
		<div id="modalDiv">
		  <div class="modal-content">
			<input type="file" id="imageInput" onchange="selectImage(this)">
			<div id="imageDiv"></div>
			<button class="btn" onclick="closeModal()">Close</button>
		  </div>
		</div>
	</body>
</html>