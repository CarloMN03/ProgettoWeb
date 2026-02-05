document.addEventListener("DOMContentLoaded", () => {
    // --- GESTIONE MENU ---
    const btn = document.getElementById("btn-hamburger");
    const menu = document.getElementById("menu-principale");
    const overlay = document.getElementById("menu-overlay");

    if (btn && menu) {
        btn.onclick = () => {
            const isHidden = menu.hasAttribute("hidden");
            if (isHidden) {
                menu.removeAttribute("hidden");
                if (overlay) overlay.removeAttribute("hidden");
                document.body.classList.add("menu-open");
            } else {
                menu.setAttribute("hidden", "");
                if (overlay) overlay.setAttribute("hidden", "");
                document.body.classList.remove("menu-open");
            }
        };
    }

    if (overlay) {
        overlay.onclick = () => {
            menu.setAttribute("hidden", "");
            overlay.setAttribute("hidden", "");
            document.body.classList.remove("menu-open");
        };
    }

    const modules = ['chgana', 'chgpwd', 'chgcdl', 'delacc'];
    const closeBtns = ['closeana', 'closepwd', 'closecdl', 'closedel'];

    modules.forEach(id => {
        const el = document.getElementById(id);
        if(el) el.style.display = "none";
    });
    closeBtns.forEach(id => {
        const el = document.getElementById(id);
        if(el) el.style.display = "none";
    });
});

// Funzioni per i pulsanti del profilo
function openForm(closeId, formId, openId) {
    const f = document.getElementById(formId);
    const c = document.getElementById(closeId);
    const o = document.getElementById(openId);
    if(f && c && o) {
        f.style.display = "block";
        c.style.display = "block";
        o.style.display = "none";
    }
}

function closeForm(closeId, formId, openId) {
    const f = document.getElementById(formId);
    const c = document.getElementById(closeId);
    const o = document.getElementById(openId);
    if(f && c && o) {
        f.style.display = "none";
        c.style.display = "none";
        o.style.display = "block";
    }
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

function openEye(inputid, imgid){
    const occhio = document.querySelector("#" + imgid).src.split('/').reverse()[0];
    if(occhio == "occhiochiuso.png") {
        document.getElementById(imgid).src = "upload/occhioaperto.png";
        document.getElementById(inputid).type = "text";
    } else {
        document.getElementById(imgid).src = "upload/occhiochiuso.png";
        document.getElementById(inputid).type = "password";
    }
}

function addFormModifica(cdl, esame, pref, daora, aora, idul, idform){
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
                                    <input type="submit" name="mod-pref" value="Invia Modifica"/>
                                </li>
                            </ul>
                        </form>
    `;

    document.querySelector(idform).style.display = "initial";
    document.querySelector(idform).innerHTML = formPref;
    document.querySelector(idul).style.display = "none";
}

function addDesc(idform){
    const luogo = document.getElementById("luogo").value;
    const label = document.querySelector(".descrizioneluogo");
    if (label == null){
        const li = document.createElement("li");
        document.querySelector(idform + " ul li:nth-of-type(2)").insertAdjacentElement("afterend", li);
    }
    if(luogo == "Online"){
        descLuogo=`
        <label for="dettaglioluogo" class="descrizioneluogo">Link per accesso: <a href="https://meet.google.com/landing" target="_blank"><img src="upload/meet.png" id="meet" alt=""/></a><a href="https://discord.com/channels/@me" target="_blank"><img src="upload/discord.png" id="discord" alt=""/></a><a href="https://www.microsoft.com/it-it/microsoft-teams/group-chat-software" target="_blank"><img src="upload/teams.png" id="teams" alt=""/></a></label><input type='text' name='dettaglioluogo' id='dettaglioluogo'/>
        `
    } else if(luogo == "Fisico"){
        descLuogo=`
        <label for="dettaglioluogo" class="descrizioneluogo">Indirizzo: </label><input type='text' name='dettaglioluogo' id='dettaglioluogo'/>    
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
    document.querySelector(".card-text ul").style.display = "none";
}

function modsg(){
    document.getElementById('sg-anag').style.display = "initial";
    document.getElementById('sg-desc').style.display = "none";
}

function controlPwd(){
    const password1 = document.querySelector("#newPwd").value;
    const password2 = document.querySelector("#reNewPwd").value;
    const p = document.querySelector("#chgpwd ul li p");

    if(p == null){
        const newp = document.createElement("p");
        document.querySelector("#guardaReNewPwd").insertAdjacentElement("afterend", newp);
    }

    if(password1 != password2){
        messaggiopwd = `
        Attenzione! Password differente da quella digitata prima.
    `;
    } else {
        messaggiopwd = `
    `;
    }

    document.querySelector("#chgpwd ul li p").innerHTML = messaggiopwd;
};
