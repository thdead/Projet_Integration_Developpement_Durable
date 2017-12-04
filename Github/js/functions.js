var test;
function ajax(el){
  var rid= null;
  if(el.value){rid = el.value;} //determine the residence
  action = el.name; //determine the action
  test = el;
  // && resnum != null
 if(action != null && rid != null){
    var xhr = new XMLHttpRequest();
    url = 'ajax/residenceActions.php?action='+action+'&rid='+rid;
    console.log(url);
    xhr.open("GET",url,true);
    xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
      if(xhr.status == 200 && xhr.readyState == 4){
        response_handling(xhr.responseText);
      }
    }
    if(rid){xhr.send(null);}
    else{xhr.send(null)}
    // xhr.send('action='+action+'&rid='+resnum);
  }
  return false;
}
//put the information into the page.
function response_handling(json){
  var resp = JSON.parse(json);
  var action = Object.keys(resp)[0];
  console.log(action);
  switch(action){
    case 'details': document.getElementById('details').innerHTML = resp[action];
    break;
    case 'deleted': deletedR(resp[action]);
    break;
    default:  document.getElementById('content').innerHTML = resp;
    //By default, put the result in content.
  }
}
function relocate(){
  location.href="addResidence.php";
}
function deleteR(el){
  swal({
  title: 'Êtes-vous sûr de vouloir supprimer cette résidence?',
  text: "Cette action sera irréversible!",
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Oui!'
}).then(function (result) {
  if (result.value) {
    ajax(el);
  }
})
}
function deletedR(resp){
  if(resp){
    swal({
      title: 'Résidence supprimé!',
      text: 'Votre résidence a été supprimé avec succès.',
      type: 'success'
    }).then(function(){
      console.log("OK");
      location.reload();
    });
  }else{
    swal(
  'Échec de suppression...',
  'La suppression de cette résidence a échoué.',
  'error'
  );
  }
}
