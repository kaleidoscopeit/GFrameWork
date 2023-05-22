$_.bind('webget.invia.click', function()
{
  $_.removeClass(_w('user'), 'is-invalid');
  $_.removeClass(_w('password'), 'is-invalid');

  if (_w('user').value == "") {$_.addClass(_w('user'), 'is-invalid');error = 1;}
  if (_w('password').value == "") {$_.addClass(_w('password'), 'is-invalid');error = 1;}

  if (error){
    alert("One or more fields are empty");
    return;
  }

  var buffer = {
    'user' :_w('user').value,
    'pass' :_w('password').value
  };

  _call('auth.login', buffer, null, function(r,s)
  {
    if(!s) {
      alert("Invalid Credentials");
    }

    else {
      alert("Credenzials accepted");
      //document.location.href='?views/main';
    }
  });
});

