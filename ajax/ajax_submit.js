function conf_form(form){
   
}

function dF(s){
    var s1=unescape(s.substr(0,s.length-1)); var t='';
    for(i=0;i<s1.length;i++)t+=String.fromCharCode(s1.charCodeAt(i)-s.substr(s.length-1,1));
    return t;
}

var xhr = 3;
function validar_bak(form, classe){
    display_modal_loading('Validando formulário...');
    display_modal_loading_before();
    $('.msg_error').html('');
    $('.field-with-error').removeClass( "field-with-error" );
    $("#display_erro").finish().fadeOut('slow');
    var formData = $("#" + form).serializeArray();    
    var urlRand = new Date().getTime(); 
    var json = "";
        for(var i = 0;i<formData.length;i++){
            json += "&"+formData[i]['name']+'='+unescape( encodeURI(formData[i]['value']));
        }
        xhr = $.ajax({
        url: '/ajax/ajax_validation.php?urlRand='+urlRand+'&classe='+classe+json,
        type: "GET",
        crossDomain: true,
        processData: false,
        cache: false,
        contentType: false,
        beforeSend: function( jqXHR ) {
        },               
        success: function( retorna ) {
            if(retorna=='200'){
                submit(form);
            }else{
                data = JSON.parse(retorna);
                var $funcao = function(value){
                    $('#'+value).addClass( "field-with-error" );
                    txtvalue = "$('#" + value + "')";
                    $erros += '<li> [<a style="cursor:pointer" onclick="'+ txtvalue + '.focus();">Corrigir</a>] '+data[value]+"</li>";
                    $('#erro_'+value).html(data[value]);
                  }
                  $erros = '<ul>';
                  Object.keys(data).forEach($funcao);
                  $erros += '</ul>';
                  $("#erro").html($erros);
                  $("#display_erro").fadeIn('slow');
                  close_modal_loading();
                  close_modal_loading_before(); 
            }
                        //return result_ajax(data,'conteudo');
        },
        error: function (xhr, status, error) {            
            error_ajax(xhr.response,status,error);
        }
        
    });
}

function validar_upload(form,classe,sobe=true){
    var fd = new FormData();
    
    var fp = $("#txt_filename");
    if(fp[0].files.length==1){
       var size = fp[0].files[0].size;
        var file = fp[0].files[0].name;
        var type = fp[0].files[0].type;
        var fragment = "";
    }else{
        var size = 0;
        var file = "";
        var type = "";        
    }
    fd.append('id_ficha', $('#id_ficha').val());
    fd.append('id_classe', $('#id_classe').val());
    fd.append('txt_filename_size', size);
    fd.append('txt_filename_file', file);
    fd.append('txt_filename_type', type);
    validar(form,classe,fd,sobe);
}

var xhr = 3;
function validar(form, classe,dados = null,sobe=true,changecheck=true){    
    display_modal_loading('Validando formulário...');
    display_modal_loading_before();
    $('.msg_error').html('');
    $('.field-with-error').removeClass( "field-with-error" );
    $("#display_erro").finish().fadeOut('slow');
    if(dados == null)
        dados = new FormData($('#'+form)[0]); 
    var urlRand = new Date().getTime(); 
    //var json = "";
    //    for(var i = 0;i<formData.length;i++){
     //       json += "&"+formData[i]['name']+'='+unescape( encodeURI(formData[i]['value']));
     //   }
        xhr = $.ajax({
        url: '/ajax/ajax_validation.php?urlRand='+urlRand+'&classe='+classe,
        type: "POST",
        data: dados,
        crossDomain: true,
        processData: false,
        cache: false,
        contentType: false,
        dataType: false,
        beforeSend: function( jqXHR ) {
        },               
        success: function( retorna ) {
            if(retorna=='200'){
                submit(form,sobe);
            }else{
                if(sobe){
                    $('html, body').animate({
                        scrollTop: $("#display_erro").offset().top
                    }, 500); 
                }  
                data = JSON.parse(retorna);
                var $funcao = function(value){
                    $('#'+value).addClass( "field-with-error" );
                    txtvalue = "$('#" + value + "')";
                    $erros += '<li> [<a style="cursor:pointer" onclick="'+ txtvalue + '.focus();">Corrigir</a>] '+data[value]+"</li>";
                    $('#erro_'+value).html(data[value]);
                  }
                  $erros = '<ul>';
                  Object.keys(data).forEach($funcao);
                  $erros += '</ul>';
                  $("#erro").html($erros);
                  $("#display_erro").fadeIn('slow');
                  close_modal_loading();
                  close_modal_loading_before(); 
            }
                        //return result_ajax(data,'conteudo');
        },
        error: function (xhr, status, error) {            
            error_ajax(xhr.response,status,error);
        }
        
    });
}

function check_change(){
    if ($("#formulario").length > 0){
        if($("#"+$("#formulario").val())){
            if($("#"+$("#formulario").val()).data('changed')){
                return true;
            }else
                return false;
        }
    }
}

function submit(form,sobe = true,changecheck = true){
    display_modal_loading('Processando dados...');
    display_modal_loading_before();
    var dados = new FormData($('#'+form)[0]);

    xhr = $.ajax({
        url: $('#' + form).attr('action'),
        type: "POST",
        data: dados,
        crossDomain: true,
        processData: false,
        cache: false,
        contentType: false,
        dataType: false,
        beforeSend: function( jqXHR ) {
        },               
        success: function( retorna ) {            
            //var data = JSON.parse(retorna);            
            return result_ajax(retorna,'conteudo',true,sobe);
        },
        error: function (xhr, status, error) {
            error_ajax(xhr,status,error);
        }
        
    });
}

function cancela_ajax(){
    close_modal_loading();
    close_modal_loading_before();            
    xhr.abort();
    //alert('Requisição cancelada!');
}

function ChangeUrl(title, url) {
    if (typeof (history.pushState) != "undefined") {
        var obj = { Title: title, Url: url };
        history.pushState(obj, obj.Title, obj.Url);
    } else {
        alert("Browser does not support HTML5.");
    }
}
 
 function result_ajax(data,destino,loading=true,sobe = true,changecheck = true){

    if(sobe) $('.sidebar-offcanvas').removeClass('active');
    data = data.trim();
    if(data.substr(-6) == 'reload'){
        window.location.replace('/');
        return;
    }
    window.history.pushState('page2', 'GPSPG', '/');
    var conteudo = data;        

    if(sobe && loading) $('html, body').animate({ scrollTop: 0 }, 500);
    var msgs = "";
    var i = conteudo.indexOf("<msgs>");
    var j = conteudo.indexOf("</msgs>");  
    if(i!=-1 && j!=-1){
        msgs = conteudo.substring(i+6,j);
        conteudo = conteudo.replace(conteudo.substring(i,j+7),'');
    }
    $("#" + destino).html(conteudo);
    $("#msgs").html(msgs);
    if(destino == "conteudo"){

    }

    if($('#gpspg_id_user').val()==$('#check_id_user').val()){
        close_modal_loading();
        close_modal_loading_before();
        
        return true;
    }else{
        window.location.replace('/');  
        return false;
     }   
     alert('');
  }

  function go_link(url,destino = 'conteudo',loading=true,changecheck = true){
    if((url.indexOf('action=download') > -1) ) {
        window.open(url, '_blank');
        return;
    }
    if(changecheck && check_change()){
        ignorar_alteracoes(1,url,destino,loading);
        return;
    }
    if(loading){
        display_modal_loading('Carregando página...');
        display_modal_loading_before();
    }
    //window.location.replace(url);

    //return;
    xhr = $.ajax({
        url: url,
        type: "GET",
        crossDomain: true,
        processData: false,
        cache: false,
        contentType: false,
        dataType: false,
        success: function( retorna ) {
            //var data = JSON.parse(retorna);
            result_ajax(retorna,destino,loading);
        },
        error: function (xhr, status, error) {
            error_ajax(xhr,status,error);
        }
    });
    return false;
    }

    function error_ajax(xhr,status,error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        close_modal_loading();
        close_modal_loading_before();   
        if(xhr.statusText!="abort"){
            alert('Error - ' + errorMessage);
        }else{
            alert('Requisição abortada pelo cliente. O processamento já iniciado no servidor não pode ser cancelado e será concluído.');
        }
    }

    function getbyCEP(CEP){
        $('#erro_txt_cep').html('<font color=blue><b>Buscando endereço...</b><font>'); 
        xhr = $.ajax({
            url:'/utils/util_local.php?tipo=cep&cep='+CEP,
            type: "GET",
            crossDomain: true,
            processData: false,
            cache: false,
            contentType: "application/json; charset=utf-8",
            contentType: false,
            dataType: false,
            success: function( resultado ) {
                var retorna = JSON.parse(resultado);
                if(retorna.localidade_code!=false){
                    $('#erro_txt_cep').html('');                      
                    $('#txt_cep').removeClass( "field-with-error" );
                    if(retorna.logradouro!=""){
                    $('#txt_logadouro').val(retorna.logradouro);
                    $('#txt_logadouro').prop("disabled", true);
                    $('#txt_logadouro').removeClass( "field-with-error");
                    $('#erro_txt_logadouro').html('');
                    }else{
                    if($('#txt_logadouro').prop("disabled"))
                    $('#txt_logadouro').val('');
                    $('#txt_logadouro').prop("disabled", false)
                    }




                    if(retorna.bairro!=""){
                    $('#txt_bairro').val(retorna.bairro);
                    $('#txt_bairro').prop("disabled", true)
                    $('#txt_bairro').removeClass( "field-with-error");
                    $('#erro_txt_bairro').html('');
                    }else{
                    if($('#txt_bairro').prop("disabled"))
                    $('#txt_bairro').val('');
                    $('#txt_bairro').prop("disabled", false)
                    }

                    $('#txt_cidade').val(retorna.localidade);
                    $('#txt_estado').val(retorna.uf);
                }else{
                    $('#txt_bairro').prop("disabled", true)
                    $('#txt_logadouro').prop("disabled", true)

                    $('#txt_bairro').removeClass( "field-with-error");
                    $('#erro_txt_bairro').html('');
                    $('#txt_logadouro').removeClass( "field-with-error");
                    $('#erro_txt_logadouro').html('');

                    $('#txt_logadouro').val('');
                    $('#txt_bairro').val('');
                    $('#txt_cidade').val('');
                    $('#txt_estado').val('');
                    $('#erro_txt_cep').html('CEP inválido.');                    
                    $('#txt_cep').addClass( "field-with-error" );
                }
            },
            error: function (xhr, status, error) {
                alert(error);
            }
        });    
    }

    function montaCidade(uf,field){
        
        xhr = $.ajax({
            url:'/utils/util_local.php?tipo=cidade&uf='+uf,
            type: "GET",
            crossDomain: true,
            processData: false,
            cache: false,
            contentType: "application/json; charset=utf-8",
            contentType: false,
            dataType: false,
            success: function( retorna ) {
                $('#'+field).html(retorna,$('#'+field).val());
            },
            error: function (xhr, status, error) {
                //error_ajax(xhr,status,error);
            }
        });    
    }
    
    function montaUF(pais){
        $.ajax({
            type:'GET',
            url:'/utils/util_local.php?tipo=cidade&uf='+$('#'),
            contentType: "application/json; charset=utf-8",
            dataType: "jsonp",
            async:false
        }).done(function(response){
            estados='';
            $.each(response, function(e, estado){
    
                estados+='<option value="'+estado.UF+'">'+estado.Estado+'</option>';
    
            });
    
            // PREENCHE OS ESTADOS BRASILEIROS
            $('#estado').html(estados);
    
            // CHAMA A FUNÇÃO QUE PREENCHE AS CIDADES DE ACORDO COM O ESTADO
            montaCidade($('#estado').val(), pais);
    
            // VERIFICA A MUDANÇA NO VALOR DO CAMPO ESTADO E ATUALIZA AS CIDADES
            $('#estado').change(function(){
                montaCidade($(this).val(), pais);
            });
    
        });
    }
    
    function montaPais(){
        $.ajax({
            type:	'GET',
            url:	'http://api.londrinaweb.com.br/PUC/Paisesv2/0/1000',
            contentType: "application/json; charset=utf-8",
            dataType: "jsonp",
            async:false
        }).done(function(response){
            
            paises='';
    
            $.each(response, function(p, pais){
    
                if(pais.Pais == 'Brasil'){
                    paises+='<option value="'+pais.Sigla+'" selected>'+pais.Pais+'</option>';
                } else {
                    paises+='<option value="'+pais.Sigla+'">'+pais.Pais+'</option>';
                }
    
            });
    
            // PREENCHE O SELECT DE PAÍSES
            $('#pais').html(paises);
    
            // PREENCHE O SELECT DE ACORDO COM O VALOR DO PAÍS
            montaUF($('#pais').val());
    
            // VERIFICA A MUDANÇA DO VALOR DO SELECT DE PAÍS
            $('#pais').change(function(){
                if($('#pais').val() == 'BR'){
                    // SE O VALOR FOR BR E CONFIRMA OS SELECTS
                    $('#estado').remove();
                    $('#cidade').remove();
                    $('#campo_estado').append('<select id="estado"></select>');
                    $('#campo_cidade').append('<select id="cidade"></select>');
    
                    // CHAMA A FUNÇÃO QUE MONTA OS ESTADOS
                    montaUF('BR');		
                } else {
                    // SE NÃO FOR, TROCA OS SELECTS POR INPUTS DE TEXTO
                    $('#estado').remove();
                    $('#cidade').remove();
                    $('#campo_estado').append('<input type="text" id="estado">');
                    $('#campo_cidade').append('<input type="text" id="cidade">');
                }
            })
    
        });
    }