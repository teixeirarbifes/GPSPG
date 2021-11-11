function validar_old($form,classe="",url="/ajax/ajax_validation.php") {
    var urlRand = new Date().getTime(); 
    var formData = $("#form").serializeArray();
    json = "urlRand="+urlRand+"&classe="+classe;

    for(var i = 0;i<formData.length;i++){
      json += "&"+formData[i]['name']+'='+unescape( encodeURIComponent(formData[i]['value']));
    }
    $("#display_erro").hide();
    $('input').removeClass( "field-with-error" );
    display_modal_loading('Validando formulário...');
    display_modal_loading_before();

    $.ajax({url: url+'?json='+json,
          cache: false,
          crossDomain: true,
          type: 'GET',
          processData: false,
          contentType: "application/json",
          dataType: "json",
          beforeSend: function( jqXHR ) {
            $('#ajaxloading').show();
            $('#bt_submit').addClass( "disabled" );
            $('#bt_limpar').addClass( "disabled" );
            $('#bt_cancelar').addClass( "disabled" );
            $('#bt_excluir').addClass( "disabled" );
          },
        success: function(result){
          if(result['code']==200){
             go_ajax(url=document.getElementById('form').action,form='form');
          }else{                                  
            var $funcao = function(value){
              $('#'+value).addClass( "field-with-error" );
              txtvalue = "$('#" + value + "')";
              $erros += '<li> [<a style="cursor:pointer" onclick="'+ txtvalue + '.focus();">Corrigir</a>] '+result['msg'][1][value]+"</li>";
            }
            $erros = '<ul>';
            Object.keys(result['msg'][1]).forEach($funcao);
            $erros += '</ul>';
            $("#erro").html($erros);
            $("#display_erro").fadeIn('slow');
            close_modal_loading();
            close_modal_loading_before();
          }
          $('#bt_submit').removeClass( "disabled" );
          $('#bt_limpar').removeClass( "disabled" );
          $('#bt_cancelar').removeClass( "disabled" );
          $('#bt_excluir').removeClass( "disabled" );
          $('#ajaxloading').fadeOut('slow');
        },
        error: function (jqXHR, exception) {
           //close_modal_loading();
           //close_modal_loading_before();
           // Note: Often ie will give no error msg. Aren't they helpful?
           alert('Ocorreu um erro validando...');
           alert('ERROR: jqXHR, exception', jqXHR, exception);
           alert(jqXHR.responseText);
           //$('#ajaxloading').hide();
        }
      });
   }

   function detectQueryString(url) {
    // regex pattern for detecting querystring
    var pattern = new RegExp(/\?.+=.*/g);
    return pattern.test(url);
   }

   function go_ajax(url,form=null,destino="conteudo",nomsg=false) {
    if(!nomsg){display_modal_loading(); display_modal_loading_before(); }
    var urlRand = new Date().getTime(); 
    var json = "urlRand="+urlRand;
    if(form!=null){      
      var formData = $("#" + form).serializeArray();
      for(var i = 0;i<formData.length;i++){
        json += "&"+formData[i]['name']+'='+unescape( encodeURIComponent(formData[i]['value']));
      }
    }
    
    //$("#display_erro").hide();
    //$('input').removeClass( "field-with-error" );

    if(detectQueryString(url)) url2 = url + "&" + json;
    else url2 = url + "?" + json;
    $.ajax({url: url2+"&ajax=1",
          cache: false,
          crossDomain: true,
          type: 'GET',
          processData: false,
          contentType: "application/json",
          dataType: "json",
          beforeSend: function( jqXHR ) {
            //$('#ajaxloading').show();
            //$('#bt_submit').addClass( "disabled" );
            //$('#bt_limpar').addClass( "disabled" );
            //$('#bt_cancelar').addClass( "disabled" );
            //$('#bt_excluir').addClass( "disabled" );
          },
        success: function(result){
          if(result['code']==200){
             //form.submit();
             alert(nomsg);
             if(!nomsg){
                
                go_ajax('/?controller=mensageirocontroller&method=listar_session',null,'msgs',true);              
             }
             if(result['id_user']==document.getElementById('check_id_user').value){
                $("#" + destino).html(result['msg']);
                if(!nomsg){
                  close_modal_loading();
                  close_modal_loading_before();
                }
             }else{
                window.location.replace('/');  
             }                  
          }
          //$('#bt_submit').removeClass( "disabled" );
          //$('#bt_limpar').removeClass( "disabled" );
          //$('#bt_cancelar').removeClass( "disabled" );
          //$('#bt_excluir').removeClass( "disabled" );
          //$('#ajaxloading').fadeOut('slow');
        },
        error: function (jqXHR, exception) {
           // Note: Often ie will give no error msg. Aren't they helpful?
           alert('Ocorreu um erro ao tentar carregar página. Carregando...');
           alert(jqXHR.responseText);
           window.location.replace(url2);           
           //$('#ajaxloading').hide();
        }
      });
   }
