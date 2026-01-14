<section class="sg">
    <h2>Study Group di <?php echo $templateParams["studygroup"][0]["nomeesame"]; ?> - Gestione</h2>
    <nav id="nav-sg">
        <ul>
            <li><a href="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Gestione</a></li><li><a href="risorsesg.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Risorse</a></li><li><a href="bacheca2.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Messaggi</a></li>
        </ul>
    </nav>
    <div class="contenuto-sg">
        <section class="dettaglio-sg">
        <?php if(isset($templateParams["ritorno-iscrivi"])): ?>
            <h3><?php echo $templateParams["ritorno-iscrivi"]; ?></h3>
        <?php endif; ?>
        <?php if(isset($templateParams["ritorno-variazionesg"])): ?>
            <h3><?php echo $templateParams["ritorno-variazionesg"]; ?></h3>
        <?php endif; ?>
        <div class="card-esame">
            <div class="card-img"><img src="<?php echo UPLOAD_DIR . $templateParams["studygroup"][0]["imgesame"]; ?>" alt=""/>
            </div>
            <div class="card-text">
                <div class="sg-desc" id="sg-desc">
                    <ul>
                        <li>Tema: <?php echo $templateParams["dettaglio-sg"][0]["tema"]; ?></li>
                        <li>Luogo: <?php echo $templateParams["dettaglio-sg"][0]["luogo"]; ?></li>
                        <li>Dettaglio luogo: <?php if($templateParams["dettaglio-sg"][0]["luogo"]=="Online"): ?>
                            <a href="<?php echo $templateParams["dettaglio-sg"][0]["dettaglioluogo"]; ?>" target="_blank">Accedi al link</a>
                        <?php else: ?>
                            <?php echo $templateParams["dettaglio-sg"][0]["dettaglioluogo"]; ?>
                        <?php endif; ?>
                        </li>
                        <li>Data: <?php echo $templateParams["dettaglio-sg"][0]["data"]; ?></li>
                        <li>Ora: <?php echo $templateParams["dettaglio-sg"][0]["ora"]; ?></li>
                        <li>Lingua: <?php echo $templateParams["dettaglio-sg"][0]["descrizionelingua"]; ?></li>
                        <li>Numero partecipanti: <?php echo $templateParams["numPartecipanti"][0]["numpart"]; ?></li>
                    </ul>
                    <div class="button-sg" id="button-sg">
                        <ul>
                            <li><button <?php echo $modifica; ?> onclick="modsg()">Modifica</button></li>
                            <li><form action="#" method="POST"><input type="<?php echo $disiscrivi; ?>" name="disiscrivi" id="disiscrivi" value="Disiscrivi"/></form></li>
                            <li><form <?php echo $iscrivi; ?>action="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>" method="POST"><input type="submit" name="iscrivi" id="iscrivi" value="Iscrivi"/></form></li>
                        </ul>
                    </div>
                </div>
                    <form id="sg-anag" action="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>" method="POST">
                        <ul>
                            <li><label for="tema">Tema: </label><input type="text" value="<?php echo $templateParams["dettaglio-sg"][0]["tema"]; ?>" id="tema" name="tema"/></li>
                            <li><label for="luogo">Luogo: </label><select name="luogo" id="luogo" onchange="addDesc('#sg-anag')"><option value="">--Seleziona un luogo--</option><option value="Online">Online</option><option value="Fisico">In fisico</option></select></li>
                            <li></li>
                            <li><label for="data">Data: </label><input type="text" value="<?php echo $templateParams["dettaglio-sg"][0]["data"]; ?>" id="data" name="data"/></li>
                            <li><label for="ora">Ora: </label><input type="text" value="<?php echo $templateParams["dettaglio-sg"][0]["ora"]; ?>" id="ora" name="ora"/></li>
                            <li><label for="lingua">Lingua: </label><select name="lingua" id="lingua"><?php foreach($templateParams["lingua"] as $lingua): ?>
                                    <option value="<?php echo $lingua["idlingua"]; ?>"><?php echo $lingua["descrizionelingua"]; ?></option>
                                <?php endforeach; ?>
                            </select></li>
                            <li><input type="submit" value="Invia"/></li>
                        </ul>
                    </form>
            </div>
        </div>
    </section>
    <section class="elenco-partecipanti">
        <h3>Elenco partecipanti</h3>
        <div class="partecipanti">
            <?php foreach($templateParams["partecipanti"] as $partecipante): ?>
                <div class="card-partecipanti">
                    <ul>
                        <li>
                            <?php if($partecipante["imguser"] == ""): ?>
                                <p><?php echo strtoupper(substr($partecipante["nome"], 0, 1)) . strtoupper(substr($partecipante["cognome"], 0, 1)); ?></p>
                            <?php else: ?>
                                <img class="imguser" src="<?php echo UPLOAD_DIR . $partecipante["imguser"]; ?>" alt=""/>
                            <?php endif; ?>
                        </li>
                        <li><h4><?php echo $partecipante["username"]; ?></h4></li>
                        <li><a href="mailto:<?php echo $partecipante["username"]; ?>@studio.unibo.it"><img class="email" src="./upload/busta.png" alt=""></a></li>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    </div>         
</section>