(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  var lang = window.navigator.language || window.navigator.userLanguage;
  var locale = "";
  if (lang.indexOf("it") !== -1) {
    locale = "it_IT";
  } else if (lang.indexOf("en") !== -1) {
    locale = "en_EN";
  } else if (lang.indexOf("de") !== -1) {
    locale = "de_DE";
  } else {
    locale = "en_EN"; // Default to English if language is not detected
  }
  js.src = "//connect.facebook.net/" + locale + "/sdk.js#xfbml=1&version=v2.9";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

document.addEventListener("turbolinks:load", function() {
  if(window.FB) {
    FB.XFBML.parse();
  }
});
