function onSubmit(token) {
  //document.getElementById("demo-form").submit();
  alert('OK');
}

function onClick(e) {
  e.preventDefault();
  grecaptcha.ready(function() {
    grecaptcha.execute('6LeV_3IcAAAAAC1I6QZ6yuwFtgAgzji5hOfqk1Nq', {action: 'submit'}).then(function(token) {
        alert('aa');
    });
  });
}