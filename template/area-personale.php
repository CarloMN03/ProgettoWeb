        <section class="gest-utente">
            <div class="intestazione-user">
                <?php if($templateParams["imguser"] == ""): ?>
                    <p><?php echo strtoupper(substr($templateParams["nomeuser"], 0, 1)) . strtoupper(substr($templateParams["cognomeuser"], 0, 1)); ?></p>
                <?php else: ?>
                    <img class="imguser" src="<?php echo UPLOAD_DIR . $templateParams["imguser"]; ?>" alt=""/>
                <?php endif; ?>
                <h2>Ciao <?php echo strtoupper(substr($templateParams["nomeuser"], 0, 1)) . substr($templateParams["nomeuser"], 1)?></h2>
            </div>
            <h3>Gestisci il tuo account</h3>
            <div class="changeanag">
                <h4>Variazione dati personali</h4>
                <?php if(isset($msg)): ?>
                <h4><?php echo $msg; ?></h4>
                <?php endif; ?>
                <form action="#" method="POST" id="chgana" enctype="multipart/form-data">
                    <ul>
                        <li>
                            <label for="nome">Nome: </label><input type="text" name="nome", id="nome" value="<?php echo $templateParams["nomeuser"]; ?>"/>
                        </li>
                        <li>
                            <label for="cognome">Cognome: </label><input type="text" name="cognome" id="cognome" value="<?php echo $templateParams["cognomeuser"]; ?>"/>
                        </li>
                        <li><label for="imguser">Immagine Profilo: </label><input type="file" id="imguser" name="imguser"/>
                        </li>
                        <li>
                            <input type="submit" name="submit" value="Invia"/>
                        </li>
                    </ul>
                </form>
                <button id="closeana" value="ʌ" onclick="closeForm('closeana', 'chgana', 'openana')"><img src="<?php echo UPLOAD_DIR; ?>angle_up.png" alt="riduci"/></button>
                <button id="openana" value="v" onclick="openForm('closeana', 'chgana', 'openana')"><img src="<?php echo UPLOAD_DIR; ?>angle_down.png" alt="espandi"/></button>
            </div>
            <div class="changepwd">
                <h4>Cambia Password</h4>
                <?php if(isset($templateParams["errorepassword"])): ?>
                <p><?php echo $templateParams["errorepassword"]; ?></p>
                <?php endif; ?>
                <form action="#" method="POST" id="chgpwd">
                    <ul>
                        <li>
                            <label for="oldPwd">Vecchia password: </label><input type="password" name="oldPwd" id="oldPwd" required/>
                        </li>
                        <li>
                            <label for="newPwd">Nuova password: </label><input type="password" name="newPwd" id="newPwd" required/><img class="guardapwd" onclick="openEye('newPwd','guardaNewPwd')" id="guardaNewPwd" src="upload/occhiochiuso.png" alt="guardapassword" />
                        </li>
                        <li>
                            <label for="reNewPwd">Conferma nuova password: </label><input type="password" name="reNewPwd" id="reNewPwd" required onkeyup="controlPwd()"/><img class="guardapwd" onclick="openEye('reNewPwd','guardaReNewPwd')" id="guardaReNewPwd" src="upload/occhiochiuso.png" alt="guardapassword" />
                            <p></p>
                        </li>
                        <li>
                            <input type="submit" value="Invia"/>
                        </li>
                    </ul>
                </form>
                <button id="closepwd" value="ʌ" onclick="closeForm('closepwd', 'chgpwd', 'openpwd')"><img src="<?php echo UPLOAD_DIR; ?>angle_up.png" alt="riduci"/></button>
                <button id="openpwd" value="v" onclick="openForm('closepwd', 'chgpwd', 'openpwd')"><img src="<?php echo UPLOAD_DIR; ?>angle_down.png" alt="espandi"/></button>
            </div>
            <div class="changecdl">
                <h4>Cambia Corso di Laurea</h4>
                <?php if(isset($templateParams["errorecdl"])): ?>
                <p><?php echo $templateParams["errorecdl"]; ?></p>
                <?php endif; ?>
                <form id="chgcdl" action="#" method="POST">
                    <ul>
                        <li>
                            <label for="newCdl">Scegli il nuovo Corso di Laurea: </label><select name="newCdl" id="newCdl">
                                <option value=""></option>
                                <?php foreach($templateParams["cdl"] as $cdl): ?>
                                <option value="<?php echo $cdl["idcdl"]; ?>"><?php echo $cdl["nomecdl"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        <li>
                            <input type="submit" value="Invia"/>
                        </li>
                    </ul>
                </form>
                <button id="closecdl" value="ʌ" onclick="closeForm('closecdl', 'chgcdl', 'opencdl')"><img src="<?php echo UPLOAD_DIR; ?>angle_up.png" alt="riduci"/></button>
                <button id="opencdl" value="v" onclick="openForm('closecdl', 'chgcdl', 'opencdl')"><img src="<?php echo UPLOAD_DIR; ?>angle_down.png" alt="espandi"/></button>
            </div>
            <div class="deletecdl">
                <h4>Elimina Account</h4>
                <?php if(isset($templateParams["erroredelete"])): ?>
                <p><?php echo $templateParams["erroredelete"]; ?></p>
                <?php endif; ?>
                <form id="delacc" action="#" method="POST">
                    <ul>
                        <li>
                            <label for="pwd">Password: </label><input type="password" name="pwd" id="pwd"/><img class="guardapwd" onclick="openEye('pwd','guardapwddel')" id="guardapwddel" src="upload/occhiochiuso.png" alt="guardapassword" />
                        </li>
                        <li>
                            <input type="submit" value="Elimina Account"/>
                        </li>
                    </ul>
                </form>
                <button id="closedel" value="ʌ" onclick="closeForm('closedel', 'delacc', 'opendel')"><img src="<?php echo UPLOAD_DIR; ?>angle_up.png" alt="riduci"/></button>
                <button id="opendel" value="v" onclick="openForm('closedel', 'delacc', 'opendel')"><img src="<?php echo UPLOAD_DIR; ?>angle_down.png" alt="espandi"/></button>
            </div>
            <div class="gestione-preferenze">
                <h4>Gestisci le preferenze</h4>
                <div class="elenco-preferenze" id="elenco-preferenze">
                    <h3>Elenco preferenze inserite</h3>
                    <?php if(isset($templateParams["ritorno-elimina-preferenza"])): ?>
                        <p><?php echo $templateParams["ritorno-elimina-preferenza"]; ?></p>
                    <?php endif; ?>
                    <?php if(isset($templateParams["ritorno-modifica-preferenza"])): ?>
                        <p><?php echo $templateParams["ritorno-modifica-preferenza"]; ?></p>
                    <?php endif; ?>
                    <?php if(empty($templateParams["preferenza"])): ?>
                        <p><?php echo "Non hai inserito preferenze su Study Group"; ?><p>
                    <?php endif; ?>
                    <?php if(isset($templateParams["ritorno-aggiungi-preferenza"])): ?>
                        <p><?php echo $templateParams["ritorno-aggiungi-preferenza"]; ?><p>
                    <?php endif; ?>
                    <div class="elenco-prefcard">
                        <?php foreach($templateParams["preferenza"] as $preferenza): ?>
                            <div class="prefcard">
                                <div class="prefimg">
                                    <img src="<?php echo UPLOAD_DIR . $preferenza["imgesame"]; ?>" alt=""/>
                                </div><div class="prefdesc">
                                    <h4><?php echo $preferenza["nomecdl"]; ?></h4>
                                    <h4><?php echo $preferenza["nomeesame"]; ?></h4>
                                    <ul>
                                        <li>Luogo: <?php echo $preferenza["luogo"]; ?></li>
                                        <li>Da ora: <?php echo $preferenza["daora"]; ?></li>
                                        <li>A ora: <?php echo $preferenza["aora"]; ?></li>
                                        <li>Lingua: <?php echo $preferenza["descrizionelingua"]; ?></li>
                                        <li><button onclick='addFormModifica(<?php echo $preferenza["idcdl"]; ?>, <?php echo $preferenza["idesame"]; ?>, <?php echo $preferenza["idpreferenza"]; ?>, "<?php echo $preferenza["daora"]; ?>", "<?php echo $preferenza["aora"]; ?>")'>Modifica</button></li>
                                        <li><form action="" method="POST">
                                            <input type="hidden" name="idcdl" id="idcdl" value="<?php echo $preferenza["idcdl"]; ?>"/>
                                            <input type="hidden" name="idesame" id="idesame" value="<?php echo $preferenza["idesame"]; ?>"/>
                                            <input type="hidden" name="idpreferenza" id="idpreferenza" value="<?php echo $preferenza["idpreferenza"]; ?>"/>
                                            <input type="submit" name="elimina-pref" value="Elimina"/></form></li>
                                    </ul>  
                                </div>
                                <div class="form-mod-pref">
                                </div>
                            </div>
                        <?php endforeach; ?>
                            <div class="prefcard">
                                <div class="prefimg">
                                    <img src="<?php echo UPLOAD_DIR; ?>plus.png" alt=""/>
                                </div><div class="prefdesc">
                                    <h4>Inserisci una nuova preferenza</h4>
                                    <form id="form-new-pref" action="" method="POST">
                                        <ul>
                                            <li>
                                                <label for="idesame" id="idesame">Esame:</label><select name="idesame" id="idesame" name="idesame">
                                                    <?php foreach($templateParams["esame"] as $esame): ?>
                                                        <option value="<?php echo $esame["idesame"]; ?>"><?php echo $esame["nomeesame"]; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </li>
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
                                                <input type="submit" name="submit-pref" value="Invia Preferenza"/>
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                    </div>   
                </div>
                <button id="closepref" value="ʌ" onclick="closeForm('closepref', 'elenco-preferenze', 'openpref')"><img src="<?php echo UPLOAD_DIR; ?>angle_up.png" alt="riduci"/></button>
                <button id="openpref" value="v" onclick="openForm('closepref', 'elenco-preferenze', 'openpref')"><img src="<?php echo UPLOAD_DIR; ?>angle_down.png" alt="espandi"/></button>
            </div>
        </section>
        <section class="iscr-sg">
            <h3>Study Group ai quali sei iscritto</h3>
            <div class="el-sgcard">
                <?php if(isset($templateParams["ritorno-disiscrivi"])): ?>
                    <h4><?php echo $templateParams["ritorno-disiscrivi"]; ?></h4>
                <?php endif; ?>
                <?php foreach($templateParams["studygroupiscritto"] as $studygroupiscritto): ?>
                    <div class="sgcard">
                        <div class="sgimg">
                            <img src="<?php echo UPLOAD_DIR . $studygroupiscritto["imgesame"]; ?>" alt=""/>
                        </div><div class="sgdesc">
                            <h4><?php echo $studygroupiscritto["nomeesame"]; ?></h4>
                            <ul>
                                <li>Tema: <?php echo $studygroupiscritto["tema"]; ?></li>
                                <li>Luogo: <?php echo $studygroupiscritto["luogo"]; ?></li>
                                <li>Dettaglio luogo: <?php if($studygroupiscritto["luogo"]=="Online"): ?>
                                    <a href="<?php echo $studygroupiscritto["dettaglioluogo"]; ?>" target="_blank">Accedi al link</a>
                                <?php else: ?>
                                    <?php echo $studygroupiscritto["dettaglioluogo"]; ?>
                                <?php endif; ?>
                                </li>
                                <li>Lingua: <?php echo $studygroupiscritto["descrizionelingua"]; ?></li>
                                <li>Data: <?php echo $studygroupiscritto["data"]; ?></li>
                                <li>Ora: <?php echo $studygroupiscritto["ora"]; ?></li>
                                <li><a href="studygroup.php?idcdl=<?php echo $studygroupiscritto["idcdl"]; ?>&idesame=<?php echo $studygroupiscritto["idesame"]; ?>&idstudygroup=<?php echo $studygroupiscritto["idstudygroup"]; ?>">Accedi</a></li>
                                <li><a href="https://www.google.com/calendar/event?action=TEMPLATE&dates=<?php echo substr($studygroupiscritto["data"],0,4) . substr($studygroupiscritto["data"],5,2) . substr($studygroupiscritto["data"],8,2) . "T" . substr($studygroupiscritto["ora"],0,2) . substr($studygroupiscritto["ora"],3,2) . "00"; ?>%2F<?php echo substr($studygroupiscritto["data"],0,4) . substr($studygroupiscritto["data"],5,2) . substr($studygroupiscritto["data"],8,2) . "T" . substr($studygroupiscritto["ora"],0,2) . substr($studygroupiscritto["ora"],3,2) . "00"; ?>&text=Studygroup%20<?php echo $studygroupiscritto["nomeesame"]; ?>%20Tema:%20<?php echo $studygroupiscritto["tema"]; ?>&location=<?php echo $studygroupiscritto["dettaglioluogo"]; ?>&details=lingua:%20<?php echo $studygroupiscritto["descrizionelingua"]; ?>">Aggiungi a Google Calendar</a></li>
                            </ul>
                        </div>
                    </div>
            <?php endforeach; ?>
            </div>
        </section>
        <script src="js/script.js"></script>