$(function(){
    if($('#form').length > 0){
        $('#form').submit(function(){
        var dados = new FormData(this);
    
        $.ajax({
            url: 'dados.php',
            type: "POST",
            data: dados,
            crossDomain: true,
            processData: false,
            cache: false,
            contentType: false,
            dataType: false,
            success: function( retorna ) {
                var data = JSON.parse(retorna);
                alert(JSON.stringify(data));
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
        return false;
        });
    }
  });