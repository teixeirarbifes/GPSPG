
	<h2><?=$processo->txt_processo?></h2>
	<table width=100% height=100%>
		<tr><td>
	<div id="lista_inscricoes" style="width: 600px;"></div>
</td>
<td style="width:100%;vertical-align:top">	
</BR>
</BR>
	<iframe id=iframe src="https://www.w3schools.com" title="W3Schools Free Online Web Tutorials" width=100% height=100%></iframe>
		<script>
 $(function() {
   window.updateIframe = function() {
     var h = $(window).height();
     $("#iframe").height(h - 250);
   }
   function reportWindowSize() {
		window.updateIframe();
	}
   window.onresize = reportWindowSize;
   window.resize(window.updateIframe);
 });
 </script>

	</td>
	</tr>
	</table>

	<script>

		$(document).ready(function () {
			var lista_inscricoes_list = 'index.php?controller=inscricaocontroller&method=list_inscricao&action=list&inscrito=0&id_processo=<?=$processo->id_processo?>&jtStartIndex=1&jtPageSize=10&jtSorting=tab_inscricao.id_inscricao';
		    //Prepare jTable
			$('#lista_inscricoes').jtable({
				title: 'Lista de inscritos do processo seletivo',
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
					listAction: lista_inscricoes_list,
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
					go_link('/?controller=usuarioscontroller&method=form_perfil	','detalhe');
				}
			});

			//Load person list from server
			$('#lista_inscricoes').jtable('load');
		});

	</script>

<div id="detalhe" style="width: 600px;"></div>