<h1>Contatos</h1>
<hr>
<div class="container">
    <table class="table table-bordered table-striped" style="top:40px;">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th><a href="?controller=contatoscontroller&method=criar" class="btn btn-success btn-sm">Novo</a></th>
            </tr>
        </thead>
        <tbody>
            <form id="excluir" action="?controller=contatoscontroller&method=excluir&id={$contato->id}" method="post">
                    <input type="hidden" name="confirm" value="1"/>
            </form>
            <?php
            if ($contatos) {
                $item = 0;
                foreach ($contatos as $contato) {
                    $item = $item + 1;
                    ?>
                    <tr>
                        <td><?php echo $contato->nome; ?></td>
                        <td><?php echo $contato->telefone; ?></td>
                        <td><?php echo $contato->email; ?></td>
                        <td>
                            <a href="?controller=contatoscontroller&method=editar&id=<?php echo $contato->id; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="?controller=contatoscontroller&method=excluir&id=<?php echo $contato->id; ?>" class="btn btn-danger btn-sm">Excluir</a>
                            <input type="button" onclick="var excluir = document.getElementById('excluir'); excluir.action = excluir.action.replace('{$contato->id}',<?php echo $contato->id; ?>); excluir.submit();"/>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="5">Nenhum registro encontrado</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script src="../ajax/ajax_submit.js"></script>
<script>
    conf_form('form');
    conf_form('excluir');
</script>