

$.fn.extend({
    trackChanges: function() {
            $('input', this).change(function(){
                $(this.form).data('changed',true);
                alert('alterado');
            });    
    },
    isChanged: function() {
        return this.data('changed');
    }
})

function setup_check_change(){
    $("input").each(function() {
        $(this).change(function(){ $('#form').data('changed',true); })
    });
    $("select").each(function() {
        $(this).change(function(){ $('#form').data('changed',true); })
    });
}

var tipo = 0;
var url = "";
var destino = "";
var loading = false;

function callback_changes(evento){
    if(evento==1){
        close_modal();
        if(tipo==0){
            go_link(url,destino,loading,false);
        }
    }else if(evento==2){
        close_modal();
    }else{
        close_modal();
    }
}

function ignorar_alteracoes(tipo,vurl,vdestino,vloading){    
    url = vurl;
    destino = vdestino;
    loading = vloading;
    display_modal(
        "Alteraçoes não salvas","Os campos do formulário foram alterados e não foram salvos. </br></br><font color=red>Se continuar, irá perder as alterações não salvas.</font></br></br>Continuar assim mesmo?",
        callback_changes,
        "Continue, não quero salvar.",
        "Ops! Quero salvar primeiro., "
    );
}


