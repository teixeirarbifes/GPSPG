modal_id = 0;
function excluir_grade_pessoal(data){
    display_modal("Confirmação de exclusão","Deseja realmente excluir o documento '<b>"+data['txt_classe']+"</b>'?",callback_grade_pessoal,"Sim, pode excluir!","Não, deixe como está!");
    modal_id = data['id_doc'];
};

function callback_grade_pessoal(evento){
    if(evento==1){
        var excluir = document.getElementById('excluir');        
        excluir.action = "?controller=documentoscontroller&method=excluir_doc&tipo=1&excluir=1&id=" + modal_id;
        submit('excluir',false);
        close_modal();
    }else if(evento==2){
        close_modal();
    };
};

function excluir_grade_curriculo(data){
    display_modal("Confirmação de exclusão","Deseja realmente excluir o documento '<b>"+data['txt_classe']+"</b>'?",callback_grade_curriculo,"Sim, pode excluir!","Não, deixe como está!");
    modal_id = data['id_doc'];
};

function callback_grade_curriculo(evento){
    if(evento==1){
        var excluir = document.getElementById('excluir');        
        excluir.action = "?controller=documentoscontroller&method=excluir_doc&tipo=2&excluir=1&id=" + modal_id;
        submit('excluir',false);
        close_modal();
    }else if(evento==2){
        close_modal();
    };
};