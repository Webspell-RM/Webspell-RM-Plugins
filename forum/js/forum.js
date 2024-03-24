function ForumVoteSave(msg, topicc, value,loggedin,userID) {
  if(confirm(msg)){
    $.ajax({
      type: "GET",
      dataType: "text",
      url: "./includes/plugins/forum/ajax.php?action=votebox",
      data:{
        topic: topicc,
        v: value,
        loggedin: loggedin,
        userID: userID
      },
      error: function(){
        alert("Backend nicht erreichbar, bitte Seite neu laden.");
      },
      beforeSend: function(){
        setTimeout(function(){
          document.getElementById("ForumVoteIt").innerHTML = "<div align='center'><img src='./includes/plugins/forum/images/icons/lade.gif' /><br />Please wait ...</div>";
        }, 5000);
      },
      success: function(response) {
        document.getElementById("ForumVoteIt").innerHTML = "<div align='center'><img src='./includes/plugins/forum/images/icons/lade.gif' /><br />Please wait ...</div>";
        setTimeout("document.location.reload();",3000);
        //alert("Antwort: " + response);
      }
    });
    return false;
  }
}


function ForumThankSave(topicc, value,loggedin,userID) {
  $(function(){
    $.ajax({
      type: "GET",
      dataType: "text",
      url: "./includes/plugins/forum/ajax.php?action=thankbox",
      data:{
        topic: topicc,
        v: value,
        loggedin: loggedin,
        userID: userID
      },
      error: function(){
        alert("Backend nicht erreichbar, bitte Seite neu laden.");
      },
      beforeSend: function(){
        setTimeout(function(response){
          /*document.getElementById("ForumThankIt"+topicc).textcontent = "<i style='font-size:14px;color:green;'>gespeichert.. bitte warten !</i>";*/
          document.getElementById('klick').onclick = function () {
          document.getElementById('ForumThankIt'+topicc).innerHTML = "<i style='font-size:14px;color:green;'>gespeichert.. bitte warten !</i>";
        }
        }, 1000);
        setTimeout(function(){
           location.reload();
        }, 1000); 
      },
      success: function(response) {
        /*document.getElementById("ForumThankIt"+topicc).innerHTML = "<img src='./includes/plugins/forum/images/icons/lade.gif' />";*/
        document.getElementById('klick').onclick = function () {
        document.getElementById('ForumThankIt'+topicc).innerHTML = "<img src='./includes/plugins/forum/images/icons/lade.gif' />";
      }
        //alert("Antwort: " + response);
      }
    });
    return false;
  });
}