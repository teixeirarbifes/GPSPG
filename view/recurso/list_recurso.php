
	<table width=100% height=100%>
		<tr><td>
	<div id="lista_recursos" style="width: 600px;"></div>
</td>
<td style="width:100%;vertical-align:top">	
</BR>
</BR>
	<!--iframe id=iframe src="https://www.w3schools.com" title="W3Schools Free Online Web Tutorials" width=100% height=100%></iframe-->
	</td>
	</tr>
	</table>

	<script>

		$(document).ready(function () {
			var lista_recursos_list = 'index.php?controller=recursocontroller&method=list_recurso&action=list&jtStartIndex=1&jtPageSize=10&jtSorting=tab_recurso.id_recurso';
		    //Prepare jTable
			$('#lista_recursos').jtable({
				title: 'Lista de recursos apresentados',
				resizable: true,
				paging: true,
				pageList: 'normal', //possible values: 'minimal', 'normal'
				pageSize: 10,
				messages: {
					pagingInfo: 'Mostrando {0}-{1} of {2}',
					pageSizeChangeLabel: 'Número de linhas',
					gotoPageLabel: 'Ir para pág.'
				},
	            selecting: true, //Enable selecting
				actions: {
					listAction: lista_recursos_list,
					//createAction: 'PersonActions.php?action=create',
					//updateAction: 'PersonActions.php?action=update',
					//deleteAction: 'PersonActions.php?action=delete'
				},
				fields: {	
					id_recurso: {
						key: true,
						create: false,
						edit: false,
						list: false,
					},
					txt_protocolo: {
						title: 'Protocolo',
						width: '10%'
					},
					dt_submissao: {
						title: 'Data Submissao',
						width: '40%',						
					}
				},
				selectionChanged: function (event,data) {
					go_link('/?controller=recursocontroller&method=form_perfil','detalhe');
				}
			});

			//Load person list from server
			$('#lista_inscricoes').jtable('load');
		});

	</script>

<div id="detalhe" style="width: 600px;"></div>