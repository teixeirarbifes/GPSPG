
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <span width=100% style="text-align: center;"><strong><span id="titulo"></span></strong></span>
    <hr>
    <span width=100% style="text-align: center;" id="content"></span>
    </br>
    <div class="row" style="justify-content: center;"> 
    <a id="bt_acao1" class="btn btn-sm btn-danger"><font color=white><span id="acao1"></span></font></a>    
    &nbsp;
    <a id="bt_acao2" class="btn btn-sm btn-success"><font color=black><span id="acao2"></span></font></a>
    </div>
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

var titulo = document.getElementById("titulo");
var content = document.getElementById("content");

var txt_acao1 = document.getElementById("acao1");
var txt_acao2 = document.getElementById("acao2");

var bt_acao1 = document.getElementById("bt_acao1");
var bt_acao2 = document.getElementById("bt_acao2");


// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

var myCallback = null;
// When the user clicks the button, open the modal 
//btn.onclick = function() {
//  display_modal("Confirmação de exclusão","Deseja realmente excluir?",callback,"Sim, pode exclua!","Não, quero cancelar!")
//}

//function callback(acao){
//    alert(acao);
//}

function display_modal(txt_titulo,txt,Callback,acao1="",acao2="",classe1="danger",classe2="success") {
  myCallback = Callback;
  titulo.innerHTML = txt_titulo;
  content.innerHTML = txt;
  bt_acao1.onclick = function(event){
      Callback(1);
  }
  bt_acao2.onclick = function(event){
    Callback(2);
  }
  bt_acao1.setAttribute("class","btn btn-sm btn-" + classe1);
  bt_acao2.setAttribute("class","btn btn-sm btn-" + classe2);
  txt_acao1.innerHTML = acao1;
  txt_acao2.innerHTML = acao2;
  if(acao2==""){
      bt_acao2.style.visibility = "hidden";
  }else{
    bt_acao2.style.visibility = "visible";
  }
  modal.style.display = "block";
}

function close_modal(){
    modal.style.display = "none";    
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  myCallback("Cancela");
  close_modal();
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>