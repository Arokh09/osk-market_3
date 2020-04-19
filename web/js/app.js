;(() => {
	$(document).ready(function(){
		$('#datatable').DataTable({
			'ajax': 'index.php?r=site/get-users',
			'columns': [
				{'data': 'name'},
				{'data': 'city.name'},
				{'data': 'skills[, ]'},
				{'data': null}
			],
			"createdRow": function (row, data, index) {
				$('td', row).eq(3).html(`
					<button class="btn btn-danger"
						onclick="removeUser(${data.id}, '${data.name}')">
						<span class="glyphicon glyphicon-trash"></span>
					</button>
				`);
			}
		});

		document.querySelector('#addUserButton')
		.addEventListener('click', () => addUser());
	});

	addUser = () => {
		$.ajax({
			type: 'POST',
			url: 'index.php?r=site/add-user',
			data: {
				csrfParam: yii.getCsrfToken()
			},
			success: response => {
				$('#datatable').DataTable().ajax.reload();
				alert(`Пользователь "${response}" добавлен!`);
			},
			error: () => alert('Ошибка добавления пользователя!')
		});
	}



	removeUser = (userId, userName) => {
		if(confirm(`Удалить пользователя "${userName}"?`)){
			$.ajax({
				type: 'POST',
				url: 'index.php?r=site/remove-user',
				data: {
					csrfParam: yii.getCsrfToken(),
					id: userId
				},
				success: () => {
					$('#datatable').DataTable().ajax.reload();
					alert('Пользователь удален!');
				},
				error: () => alert('Ошибка удаления пользователя!')
			});
		}
	}
})();
