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

function openMenu(ʌ, form, v, utente, nome){
    switch (utente) { 
        case 0:
            menu =`
            <ul>
                <li>Ciao ${nome}
                <li><a href="profilo.php">Area personale</a></li>
                <li><a href="#">Home Page</a></li>
                <li><a href="elenco-cdl.php">Corsi di Laurea</a></li>
                <li><a href="#">Study Group</a></li>
                <li><a href="#">Risorse</a></li>
                <li><a href="contatti.php">Contatti</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
            `;
            break;
        case 1:
            menu =`
            <ul>
                <li>Ciao ${nome}
                <li><a href="#">Home Page</a></li>
                <li><a href="#">Gestione CDL</a></li>
                <li><a href="#">Gestione Esami</a></li>
                <li><a href="#">Gestione Study Group</a></li>
                <li><a href="#">Gestione Utenti</a></li>
                <li><a href="#">Logout</a></li>
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
    document.getElementById("nav").innerHTML=menu;
    openForm(ʌ, form, v);
}
