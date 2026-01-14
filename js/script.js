function closeForm(ʌ, form, v){
    document.getElementById(ʌ).style.display = "none";
    document.getElementById(form).style.display = "none";
    document.getElementById(v).style.display = "initial";
}

function openForm(ʌ, form, v){
    document.getElementById(ʌ).style.display = "initial";
    document.getElementById(form).style.display = "inherit";
    document.getElementById(v).style.display = "none";
}

function modsg(){
    document.getElementById('sg-anag').style.display = "initial";
    document.getElementById('sg-desc').style.display = "none";
}

function openMenu(ʌ, form, v, utente, nome){
    switch (utente) { 
        case 0:
            menu =`
            <ul>
                <li>Ciao ${nome.substr(0, 1).toUpperCase()}${nome.substr(1)}
                <li><a href="profilo.php">Area personale</a></li>
                <li><a href="#">Home Page</a></li>
                <li><a href="elenco-cdl.php">Corsi di Laurea</a></li>
                <li><a href="#">Study Group</a></li>
                <li><a href="risorse.php">Risorse</a></li>
                <li><a href="contatti.php">Contatti</a></li>
                <li><a href="profilo.php?logout=1">Logout</a></li>
            </ul>
            `;
            break;
        case 1:
            menu =`
            <ul>
                <li>Ciao ${nome.substr(0, 1).toUpperCase()}${nome.substr(1)}
                <li><a href="#">Home Page</a></li>
                <li><a href="#">Gestione CDL</a></li>
                <li><a href="#">Gestione Esami</a></li>
                <li><a href="#">Gestione Study Group</a></li>
                <li><a href="#">Gestione Utenti</a></li>
                <li><a href="profilo.php?logout=1">Logout</a></li>
            </ul>
            `;
            break;
        default:
            menu =`
            <ul>
                <li><a href="#">Home Page</a></li>
                <li><a href="#">Accedi/Registrati</a></li>
                <li><a href="elenco-cdl.php">Corsi di Laurea</a></li>
                <li><a href="#">Study Group</a></li>
                <li><a href="#">Risorse</a></li>
                <li><a href="contatti.php">Contatti</a></li>
            </ul>
            `;
    }
    document.getElementById("nav-principale").innerHTML=menu;
    openForm(ʌ, form, v);
}
function loadbutton(cdl, esame, sg, partecipante){
    switch (partecipante) {
        case 0:
            div =`
            <ul>
                <li><button onclick="modsg()">Modifica</button></li>
                <li><form action="#" method="POST"><input type="submit" name="disiscrivi" id="disiscrivi" value="Disiscrivi"/></form></li>
            </ul>
            `;
        case 1:
            div =`
            <ul>
                <li><form action="studygroup.php?idcdl=${cdl}&idesame=${esame}&idstudygroup=${sg}" method="POST"><input type="submit" name="iscrivi" id="iscrivi" value="Iscrivi"></form></li>
            </ul>
            `;
    }
    document.getElementById("button-sg").innerHTML=div;
}

function addDesc(idform){
    const luogo = document.getElementById("luogo").value;
    if(luogo == "Online"){
        descLuogo=`
        <label for="dettaglioluogo" id="dettaglioluogo">Link per accesso: <a href="https://meet.google.com/landing" target="_blank"><img src="upload/meet.png" id="meet" alt=""/></a><a href="https://discord.com/channels/@me" target="_blank"><img src="upload/discord.png" id="discord" alt=""/></a><a href="https://www.microsoft.com/it-it/microsoft-teams/group-chat-software" target="_blank"><img src="upload/teams.png" id="teams" alt=""/></a></label><input type='text' name='dettaglioluogo' id='dettaglioluogo'/>
        `
    } else if(luogo == "Fisico"){
        descLuogo=`
        <label for="dettaglioluogo" id="dettaglioluogo">Indirizzo: </label><input type='text' name='dettaglioluogo' id='dettaglioluogo'/>    
        `;
    } else {
        descLuogo=`
        `;
    }
    const selector1 = idform;
    const selector2 = "ul li:nth-of-type(3)";
    const selector = selector1.concat(" ", selector2);
    document.querySelector(selector).innerHTML=descLuogo;
}

function addPreferenza(){
    const menu = `
                        <form action="" method="POST">
                            <ul>
                                <li>
                                    <label for="luogo">Luogo: </label><select name="luogo" id="luogo"><option value="Online">Online</option><option value="Fisico">In Fisico</option></select>
                                </li>
                                <li>
                                    <label for="ora-da">Da ora: </label><input type="time" name="ora-da" id="ora-da" value="00:00"/>
                                </li>
                                <li>
                                    <label for="ora-a">Da ora: </label><input type="time" name="ora-a" id="ora-a" value="23:59"/>
                                </li>
                                <li>
                                    <label for="idlingua">Lingua: </label><select name="idlingua" id="idlingua"><option value="IT">Italiano</option><option value="EN">English</option></select>
                                </li>
                                <li>
                                    <input type="submit" name="submit" value="Invia Preferenza"/>
                                </li>
                            </ul>
                        </form>
    `;

    document.querySelector(".form-pref").innerHTML = menu;
    document.querySelector("#card-text ul").style.display = "none";
}

async function checkNotifica(){
    const url = "api-notifica.php";
    
    try{
        const response = await fetch(url);
        if(!response.ok){
            throw new Error("Response status: " + response.status);
        }
        const json = await response.json();
        if(json == 1){
            const addNotifica = `<a href="notifica.php"><img src="./upload/notifica.png" alt="notifica"></a>`;
            document.querySelector(".ico-notifica").innerHTML = addNotifica;
        }
    }
    catch(error){
        console.log(error.message);
    }
}

checkNotifica();

setInterval(checkNotifica, 5000);

function addFormModifica(cdl, esame, pref, daora, aora){
    const formPref = `
                        <form action="" method="POST">
                            <ul>
                                <li>
                                    <label for="luogo">Luogo: </label><select name="luogo" id="luogo"><option value="Online">Online</option><option value="Fisico">In Fisico</option></select>
                                </li>
                                <li>
                                    <label for="ora-da">Da ora: </label><input type="time" name="ora-da" id="ora-da" value="${daora}"/>
                                </li>
                                <li>
                                    <label for="ora-a">Da ora: </label><input type="time" name="ora-a" id="ora-a" value="${aora}"/>
                                </li>
                                <li>
                                    <label for="idlingua">Lingua: </label><select name="idlingua" id="idlingua"><option value="IT">Italiano</option><option value="EN">English</option></select>
                                </li>
                                <li>
                                    <input type="hidden" name="idcdl" id="idcdl" value="${cdl}"/>
                                    <input type="hidden" name="idesame" id="idesame" value="${esame}"/>
                                    <input type="hidden" name="idpreferenza" id="idpreferenza" value="${pref}"/>
                                </li>
                                <li>
                                    <input type="submit" name="mod-pref" value="Invia Modifica"/>
                                </li>
                            </ul>
                        </form>
    `;

    document.querySelector(".form-mod-pref").style.display = "initial";
    document.querySelector(".form-mod-pref").innerHTML = formPref;
    document.querySelector(".prefdesc").style.display = "none";
}

function controlPwd(){
    const password1 = document.querySelector("#newPwd").value;
    const password2 = document.querySelector("#reNewPwd").value;

    if(password1 != password2){
        messaggiopwd = `
        Attenzione! Password differente da quella digitata prima.
    `;
    } else {
        messaggiopwd = `
    `;
    }

    document.querySelector("#chgpwd ul li p").innerHTML = messaggiopwd;
}

function openEye(inputid, imgid){
    const occhio = document.querySelector("#" + imgid).src.split('/').reverse()[0];
    if(occhio == "occhiochiuso.png") {
        document.getElementById(imgid).src = "upload/occhioaperto.png";
        document.getElementById(inputid).type = "text";
    } else {
        document.getElementById(imgid).src = "upload/occhiochiuso.png";
        document.getElementById(inputid).type = "password";
    }
    
    
};