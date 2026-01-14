function generaMessaggi(messaggi){
    let list = `
    <section class="msg">
    <h2>Study Group di ${messaggi["studygroup"][0]["nomeesame"]} - Messaggi</h2>
    <nav id="nav-sg">
        <ul>
            <li><a href="studygroup.php?idcdl=${messaggi['idcdl']}&idesame=${messaggi['idesame']}&idstudygroup=${messaggi['idstudygroup']}">Gestione</a></li><li><a href="risorsesg.php?idcdl=${messaggi["idcdl"]}&idesame=${messaggi["idesame"]}&idstudygroup=${messaggi["idstudygroup"]}">Risorse</a></li><li><a href="bacheca2.php?idcdl=${messaggi["idcdl"]}&idesame=${messaggi["idesame"]}&idstudygroup=${messaggi["idstudygroup"]}">Messaggi</a></li>
        </ul>
    </nav>
    <div class="struttura-msg">
        <h3>Messaggi dello Study Group</h3>
        <ul>
    `
    for (let i = 0; i < messaggi["lista"].length; i++){
        list += `
                <li>
                    <div class="el-msg">
                        <div class="img-msg">
                `
                if(messaggi["lista"][i]["imguser"] == messaggi["upload"]) {
                    list += `
                        <p>${messaggi["lista"][i]["nome"].substring(0, 1).toUpperCase()}${messaggi["lista"][i]["cognome"].substring(0, 1).toUpperCase()}</p>
                        `   
                } else {
                    list += `
                        <img class="imguser" src="${messaggi["lista"][i]["imguser"]}" alt="" />
                    `
                }
                list += `
                    </div>
                        <div class="card-msg">
                            <div class="user-msg">
                                <h4>${messaggi["lista"][i]["username"]} - ${messaggi["lista"][i]["datamsg"]} - ${messaggi["lista"][i]["oramsg"]}</h4>
                            </div>
                            <div class="testo-msg">
                                <p>${messaggi["lista"][i]["testomessaggio"]}</p>
                            </div>
                        </div>
                    </div>
                </li>
                `
    }               
    list += `
            </ul>
        </div>
    `
    return list;                    
}

function generaForm(messaggi){
    let form = `
    <div class="nuovo-msg">
    <h3>Scrivi un nuovo messaggio</h3>
    `
    if(messaggi["user"] != null){
        form += `
            <h4></h4>
        `
    }
    form += `
        
            <form id="crea-msg" action="" method="POST">
                <div class="img-msg">
                ` 
                if(messaggi["user"][0]["imguser"] == messaggi["upload"]){
                    form += `
                    <p>${messaggi["user"][0]["nome"].substring(0, 1).toUpperCase()}${messaggi["user"][0]["cognome"].substring(0, 1).toUpperCase()}</p>
                    `
                } else {
                    form += `
                    <img class="imguser" src="${messaggi["user"][0]["imguser"]}" alt=""/>
                    `
                }
                form += `
                </div>  
                <div class="card-nuovomsg">
                    <ul>
                        <li><h4>${messaggi["username"]}</h4></li>
                        <li><label for="testomsg">Testo: </label><input type="text" id="testomsg"   name="testomsg"/></li>
                        <li><input type="submit" value="Invia"/></li>
                    </ul>
                </div>            
            </form>
        </div>
    </section>
    `
    return form;      
}

async function getMessaggi() {
    const url = 'api-listamessaggi.php';
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        const listamessaggi = generaMessaggi(json);
        
        const main = document.querySelector("main");
        main.innerHTML = listamessaggi;
    } catch (error) {
        console.log(error.message);
    }
}


async function getForm() {
    const url = 'api-listamessaggi.php';
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        const formmessaggi = generaForm(json);
        const main = document.querySelector(".struttura-msg");
        main.insertAdjacentHTML('afterend', formmessaggi);
        document.querySelector(".nuovo-msg").addEventListener("submit", function (event) {
            event.preventDefault();
            const testomsg = document.querySelector("#testomsg").value;
            sendForm(testomsg);
            getMessaggi();
            getForm();
        });
    } catch (error) {
        console.log(error.message);
    }
}

async function sendForm(testomsg) {
    const url = 'api-listamessaggi.php';
    const formData = new FormData();
    formData.append('testomsg', testomsg);
    try {
        const response = await fetch(url, {
            method: "POST",
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
    } catch (error) {
        console.log(error.message);
    }
}

getMessaggi();
getForm();


