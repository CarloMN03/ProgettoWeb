
function generaMessaggi(messaggi) {
    let list = "";
    for (let i = 0; i < messaggi["lista"].length; i++) {
        list += `
            <li class="msg-item" style="margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                <div class="card-msg">
                    <div class="user-msg">
                        <strong style="color: var(--brand);">${messaggi["lista"][i]["username"]}</strong> 
                        <small class="text-muted"> - ${messaggi["lista"][i]["datamsg"]} ${messaggi["lista"][i]["oramsg"]}</small>
                    </div>
                    <div class="testo-msg" style="margin-top: 5px;">
                        <p>${messaggi["lista"][i]["testomessaggio"]}</p>
                    </div>
                </div>
            </li>`;
    }
    return list;
}

/**
 * Recupera i messaggi e li mette dentro la <ul> esistente nel PHP.
 */
async function getMessaggi() {
    const url = 'api-listamessaggi.php';
    try {
        const response = await fetch(url);
        const json = await response.json();
        
        // Selezioniamo la lista che è già presente nel  messaggio.php
        const listaUl = document.querySelector(".lista-messaggi");
        if (listaUl) {
            listaUl.innerHTML = generaMessaggi(json);
        }
    } catch (error) {
        console.log("Errore caricamento:", error.message);
    }
}

/**
 * Gestisce l'invio del messaggio senza ricaricare la pagina.
 */
async function sendForm(testomsg) {
    const url = 'api-listamessaggi.php';
    const formData = new FormData();
    formData.append('testomsg', testomsg);
    try {
        const response = await fetch(url, {
            method: "POST",
            body: formData
        });
        if (response.ok) {
            document.querySelector("#testomsg").value = ""; // Svuota il campo
            getMessaggi(); // Aggiorna la lista
        }
    } catch (error) {
        console.log("Errore invio:", error.message);
    }
}

// Inizializzazione al caricamento della pagina
document.addEventListener("DOMContentLoaded", () => {
    getMessaggi(); // Carica subito i messaggi
    
    // Aggiornamento automatico ogni 5 secondi
    setInterval(getMessaggi, 5000);

    // Gestione del form (già presente nel PHP)
    const form = document.querySelector("#crea-msg");
    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            const input = document.querySelector("#testomsg");
            if (input.value.trim() !== "") {
                sendForm(input.value);
            }
        });
    }
});