// Oggetto con le traduzioni per diverse lingue
const translations = {
    "en": {
        "confirmVote": "Are you sure you want to vote?",
        "backendError": "Backend not reachable, please reload the page.",
        "loading": "Please wait ...",
        "saved": "Saved... please wait!"
    },
    "it": {
        "confirmVote": "Sei sicuro di voler votare?",
        "backendError": "Backend non raggiungibile, ricarica la pagina.",
        "loading": "Attendere prego...",
        "saved": "Salvato... attendere!"
    },
    "de": {
        "confirmVote": "Bist du sicher, dass du abstimmen möchtest?",
        "backendError": "Backend nicht erreichbar, bitte Seite neu laden.",
        "loading": "Bitte warten ...",
        "saved": "Gespeichert... bitte warten!"
    }
};

// Funzione per ottenere la traduzione in base alla lingua del browser
function getTranslation(key) {
    let lang = navigator.language.substring(0, 2); // Prende solo i primi due caratteri (es: "it", "en")
    return translations[lang] ? translations[lang][key] : translations["en"][key]; // Default inglese
}

// Funzione per il voto nel forum
function ForumVoteSave(msg, topicc, value, loggedin, userID) {
    if (confirm(getTranslation("confirmVote"))) {
        $.ajax({
            type: "GET",
            dataType: "text",
            url: "./includes/plugins/forum/ajax.php?action=votebox",
            data: {
                topic: topicc,
                v: value,
                loggedin: loggedin,
                userID: userID
            },
            error: function () {
                alert(getTranslation("backendError"));
            },
            beforeSend: function () {
                setTimeout(function () {
                    document.getElementById("ForumVoteIt").innerHTML =
                        "<div align='center'><img src='./includes/plugins/forum/images/icons/lade.gif' /><br />" +
                        getTranslation("loading") + "</div>";
                }, 5000);
            },
            success: function (response) {
                document.getElementById("ForumVoteIt").innerHTML =
                    "<div align='center'><img src='./includes/plugins/forum/images/icons/lade.gif' /><br />" +
                    getTranslation("loading") + "</div>";
                setTimeout("document.location.reload();", 3000);
            }
        });
        return false;
    }
}

// Funzione per il "grazie" nel forum
function ForumThankSave(topicc, value, loggedin, userID) {
    $(function () {
        $.ajax({
            type: "GET",
            dataType: "text",
            url: "./includes/plugins/forum/ajax.php?action=thankbox",
            data: {
                topic: topicc,
                v: value,
                loggedin: loggedin,
                userID: userID
            },
            error: function () {
                alert(getTranslation("backendError"));
            },
            beforeSend: function () {
                setTimeout(function () {
                    let elem = document.getElementById("ForumThankIt" + topicc);
                    if (elem) {
                        elem.innerHTML = "<i style='font-size:14px;color:green;'>" + getTranslation("saved") + "</i>";
                    }
                }, 1000);
                setTimeout(function () {
                    location.reload();
                }, 1000);
            },
            success: function (response) {
                let elem = document.getElementById("ForumThankIt" + topicc);
                if (elem) {
                    elem.innerHTML = "<img src='./includes/plugins/forum/images/icons/lade.gif' />";
                }
            }
        });
        return false;
    });
}
