
	
	<h2><?=$processo->txt_processo?></h2>
	<div id="PeopleTableContainer" style="width: 600px;"></div>
	<script>

		$(document).ready(function () {
			
		    //Prepare jTable
			$('#PeopleTableContainer').jtable({
				title: 'Lista de inscritos do processo seletivo',
				paging: true,
	            sorting: true,
	            defaultSorting: 'Name ASC',
	            selecting: true, //Enable selecting
				actions: {
					listAction: 'index.php?controller=inscricaocontroller&method=list_inscricao&action=list&inscrito=1&id_processo=<?=$processo->id_processo?>',
					//createAction: 'PersonActions.php?action=create',
					//updateAction: 'PersonActions.php?action=update',
					//deleteAction: 'PersonActions.php?action=delete'
				},
				fields: {	
					id_inscricao: {
						key: true,
						create: false,
						edit: false,
						list: false,
					},
					id_inscricao2: {
						title: 'Inscrição',
						width: '5%'
					},
					txt_nome: {
						title: 'Candidato',
						width: '40%',						
					},
					key_inscricao: {
						title: 'Protocolo',
						width: '20%'
					},
					dt_enviado: {
						title: 'Envio',
						width: '30%',
						type: 'date',
						create: false,
						edit: false
					}
				},
				selectionChanged: function () {
					go_link('/?controller=usuarioscontroller&method=form_perfil	');
				}
			});

			//Load person list from server
			$('#PeopleTableContainer').jtable('load');
		});

	</script>

<div id="detalhe" style="width: 600px;"></div>