
<!-- The Modal -->
<div id="myModalLoading" class="modal" style="z-index:20000;display:none">

  <!-- Modal content -->
  <!--div class="modal-content">
    <span class="close">&times;</span>
    <span width=100% style="text-align: center;"><strong><span id="titulo"></span></strong></span>
    <hr>
    <span width=100% style="text-align: center;" id="content"></span>
    </br>
    <div class="row" style="justify-content: center;"> 
    <a id="bt_acao1" class="btn btn-danger btn-sm"><font color=white><span id="acao1"></span></font></a>    
    &nbsp;
    <a id="bt_acao2" class="btn btn-primary btn-sm"><font color=white><span id="acao2"></span></font></a>
    </div>
  </div-->

</div>
<div id="myModalLoading_before" class="modal" style="display:none;z-index:20000;background-color:rgba(0,0,0,0.11);">
</div>


<script>
// Get the modal

var timer = {
    timer: null,

    close: function(notimming) {
        if((!timming && !notimming) || notimming){
          if (timer.timer) {
              clearInterval(timer.timer);
              timer.timer = null;
              $('#carregando').finish().fadeOut('fast');
              timming = false;
          }        
        }
    },

    open: function(txt) {
        timer.close(true);
        timer.timer = setInterval(function() {
            timming = false;
            timer.close(false);
        }, 1000);
        timming = true;
        $('#carregando').finish().fadeIn('fast');
        $('#txt_carregando').html(txt);
    }
}

var timming = true;
function display_modal_loading_before() {
  var modal = document.getElementById("myModalLoading_before");
  modal.style.display = 'block';
}
function close_modal_loading_before(){
  var modal = document.getElementById("myModalLoading_before");
  modal.style.display = 'none';
}

function display_modal_loading(txt = "Carregando página...") {    
  $('#txt_carregando').html(txt);
  $('#carregando').finish().fadeIn('fast');
}

function close_modal_loading(){
  $('#carregando').finish().fadeOut('fast');  
}
</script>