function searchTable() {
	let keyword = document.getElementById('keyword').value.toUpperCase(),
		datasetTable = document.getElementById('datasetTable'),
		tableFields = datasetTable.getElementsByTagName('tr');

	for (let i = 1; i < tableFields.length; i++) {
		let rawField = tableFields[i].getElementsByTagName('td');
		let found = false;
		
		for (let j = 0; j < rawField.length; j++) {
			if (rawField[j] && rawField[j].innerText.toUpperCase().indexOf(keyword) > -1) {
				found = true;
				break;
			}
		}
		
		tableFields[i].style.display = found ? '' : 'none';
	}
}

function fetchData(){
	$.ajax({
		url: 'index.php',
		type: 'GET',
		data: { action: 'fetchData' },
		success: function(response) {
			let data = JSON.parse(response);
			//empty and refill the table
			$('#datasetTable tbody').empty();
			for (var i = 0; i < data.length; i++){
				var newRowContent = "<tr><td>" + data[i].task + "</td><td>" + data[i].title + "</td><td>" + data[i].description + "</td><td style='color:" + data[i].colorCode + "'>" + data[i].colorCode + "</td></tr>";
				$('#datasetTable tbody').append(newRowContent);
			}
		},
		error: function(xhr, status, error) {
			console.error('Error fetching data:', error);
		}
	});
}

function openModal() {
	$('#modalDiv').toggle();
}

function closeModal() {
	$('#imageDiv').html('');
	$('#modalDiv').toggle();
}

function selectImage(input){
	let file = input.files[0];
	let reader = new FileReader();

	reader.onload = function() {
	  let img = document.createElement('img');
	  img.src = reader.result;
	  $('#imageDiv').html('');
	  $('#imageDiv').append(img);
	}

	reader.readAsDataURL(file);
}

//Fetch data every hour
setInterval(fetchData, 60 * 60 * 1000);
