<section class="profilo-header">
    <h2>Ciao, <?php echo e($templateParams["nomeuser"]); ?>!</h2>
    <div class="user-avatar">
        <?php if($templateParams["imguser"] == ""): ?>
                    <p><?php echo strtoupper(substr($templateParams["nomeuser"], 0, 1)) . strtoupper(substr($templateParams["cognomeuser"], 0, 1)); ?></p>
                <?php else: ?>
                    <img class="avatar-img" src="<?php echo UPLOAD_DIR . $templateParams["imguser"]; ?>" alt=""/>
                <?php endif; ?>
    </div>
</section>

<section class="gest-utente">
    <h3>Gestisci il tuo account</h3>

    <?php if(isset($templateParams["messaggio"])): ?>
        <div class="alert alert-success"><?php echo $templateParams["messaggio"]; ?></div>
    <?php endif; ?>
    <?php if(isset($templateParams["errore"])): ?>
        <div class="alert alert-error"><?php echo $templateParams["errore"]; ?></div>
    <?php endif; ?>

    <div class="changeanag">
        <h4>Variazione dati personali</h4>
        <form action="profilo.php" method="POST" id="chgana" enctype="multipart/form-data">
            <ul>
                <li>
                    <label for="nome">Nome: </label>
                    <input type="text" name="nome" id="nome" value="<?php echo e($templateParams["nomeuser"]); ?>" required/>
                </li>
                <li>
                    <label for="cognome">Cognome: </label>
                    <input type="text" name="cognome" id="cognome" value="<?php echo e($templateParams["cognomeuser"]); ?>" required/>
                </li>
                <li>
                    <label for="imguser">Nuova Immagine Profilo: </label>
                    <input type="file" id="imguser" name="imguser" accept="image/*"/>
                </li>
                <li>
                    <input type="submit" name="submit-anag" value="Aggiorna Profilo"/>
                </li>
            </ul>
        </form>
        <button id="closeana" class="btn-toggle" onclick="closeForm('closeana', 'chgana', 'openana')">ʌ</button>
        <button id="openana" class="btn-toggle" onclick="openForm('closeana', 'chgana', 'openana')">v</button>
    </div>

    <div class="changepwd">
        <h4>Cambia Password</h4>
        <form action="profilo.php" method="POST" id="chgpwd">
            <ul>
                <li>
                    <label for="oldPwd">Vecchia password: </label>
                    <input type="password" name="oldPwd" id="oldPwd" required/>
                </li>
                <li>
                    <label for="newPwd">Nuova password: </label>
                    <input type="password" name="newPwd" id="newPwd" required/>
                    <img class="guardapwd" onclick="openEye('newPwd','guardaNewPwd')" id="guardaNewPwd" src="upload/occhiochiuso.png" alt="guardapassword"/>
                </li>
                <li>
                    <label for="reNewPwd">Conferma nuova: </label>
                    <input type="password" name="reNewPwd" id="reNewPwd" required onkeyup="controlPwd()"/>
                    <img class="guardapwd" onclick="openEye('reNewPwd','guardaReNewPwd')" id="guardaReNewPwd" src="upload/occhiochiuso.png" alt="guardapassword"/>
                </li>
                <li>
                    <input type="submit" name="submit-pwd" value="Cambia Password"/>
                </li>
            </ul>
        </form>
        <button id="closepwd" class="btn-toggle" onclick="closeForm('closepwd', 'chgpwd', 'openpwd')">ʌ</button>
        <button id="openpwd" class="btn-toggle" onclick="openForm('closepwd', 'chgpwd', 'openpwd')">v</button>
    </div>

    <div class="changecdl">
        <h4>Cambia Corso di Laurea</h4>
        <form id="chgcdl" action="profilo.php" method="POST">
            <ul>
                <li>
                    <label for="newCdl">Nuovo Corso: </label>
                    <select name="newCdl" id="newCdl" required>
                        <option value="">-- Seleziona --</option>
                        <?php foreach($templateParams["cdl"] as $cdl): ?>
                            <option value="<?php echo $cdl["idcdl"]; ?>" <?php if($cdl["idcdl"] == $templateParams["user_cdl"]) echo "selected"; ?>>
                                <?php echo e($cdl["nomecdl"]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </li>
                <li>
                    <input type="submit" name="submit-cdl" value="Cambia Corso"/>
                </li>
            </ul>
        </form>
        <button id="closecdl" class="btn-toggle" onclick="closeForm('closecdl', 'chgcdl', 'opencdl')">ʌ</button>
        <button id="opencdl" class="btn-toggle" onclick="openForm('closecdl', 'chgcdl', 'opencdl')">v</button>
    </div>

    <div class="deletecdl">
        <h4 class="danger-title">Zona Pericolosa: Elimina Account</h4>
        <form id="delacc" action="profilo.php" method="POST" onsubmit="return confirm('Sei DAVVERO sicuro? Questa azione cancellerà il tuo profilo.');">
            <ul>
                <li>
                    <label for="pwd-del">Inserisci Password per confermare: </label>
                    <input type="password" name="pwd-del" id="pwd-del" required/>
                    <img class="guardapwd" onclick="openEye('pwd-del','guardapwddel')" id="guardapwddel" src="upload/occhiochiuso.png" alt="guardapassword"/>
                </li>
                <li>
                    <input type="submit" name="submit-delete" value="Elimina Account definitivamente"/>
                </li>
            </ul>
        </form>
        <button id="closedel" class="btn-toggle" onclick="closeForm('closedel', 'delacc', 'opendel')">ʌ</button>
        <button id="opendel" class="btn-toggle" onclick="openForm('closedel', 'delacc', 'opendel')">v</button>
    </div>
</section>

<section class="gestione-preferenze">
    <h3>Le tue Preferenze di Studio</h3>
    <div class="elenco-preferenze" id="elenco-preferenze">
        <div class="elenco-prefcard">
            <?php if(empty($templateParams["preferenza"])): ?>
                <p>Non hai ancora impostato preferenze di studio.</p>
            <?php else: ?>
                <?php foreach($templateParams["preferenza"] as $pref): ?>
                    <div class="prefcard">
                        <div class="prefimg">
                            <img src="img/esami/<?php echo ($pref["imgesame"] ? $pref["imgesame"] : "default-exam.png"); ?>" alt="Esame"/>
                        </div>
                        <div class="prefdesc" id="prefdesc<?php echo $pref["idcdl"] . $pref["idesame"] . $pref["idpreferenza"]; ?>">
                            <h4><?php echo e($pref["nomeesame"]); ?></h4>
                            <ul>
                                <li><strong>Luogo:</strong> <?php echo e($pref["luogo"]); ?></li>
                                <li><strong>Disponibilità:</strong> dalle <?php echo $pref["daora"]; ?> alle <?php echo $pref["aora"]; ?></li>
                                <li><strong>Lingua: </strong><?php echo $pref["descrizionelingua"]; ?></li>
                                <li>
                                    <button onclick='addFormModifica(<?php echo $pref["idcdl"]; ?>, <?php echo $pref["idesame"]; ?>, <?php echo $pref["idpreferenza"]; ?>, "<?php echo $pref["daora"]; ?>", "<?php echo $pref["aora"]; ?>", "#prefdesc<?php echo $pref["idcdl"] . $pref["idesame"] . $pref["idpreferenza"] . " ul";?>", "#form-mod-pref<?php echo $pref["idcdl"] . $pref["idesame"] . $pref["idpreferenza"]; ?>")'>Modifica</button>
                                </li>
                                <li>
                                    <form action="profilo.php" method="POST">
                                        <input type="hidden" name="idcdl" value="<?php echo $pref["idcdl"]; ?>"/>
                                        <input type="hidden" name="idesame" value="<?php echo $pref["idesame"]; ?>"/>
                                        <input type="hidden" name="idpreferenza" value="<?php echo $pref["idpreferenza"]; ?>"/>
                                        <input type="submit" name="elimina-pref" value="Rimuovi" class="btn-small-delete"/>
                                    </form>
                                </li>
                            </ul>
                            <div class="form-mod-pref" id="form-mod-pref<?php echo $pref["idcdl"] . $pref["idesame"] . $pref["idpreferenza"]; ?>">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="prefcard">
                <div class="prefdesc">
                    <h4>Inserisci una nuova preferenza</h4>
                    <form id="form-new-pref" action="" method="POST">
                        <ul>
                            <li>
                                <label for="idesamepref">Esame:</label>
                                <select id="idesamepref" name="idesame">
                                    <?php foreach($templateParams["esame"] as $esame): ?>
                                        <option value="<?php echo $esame["idesame"]; ?>"><?php echo $esame["nomeesame"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </li>
                            <li>
                                <label for="luogo">Luogo: </label>
                                <select name="luogo" id="luogo">
                                    <option value="Online">Online</option>
                                    <option value="Fisico">In Fisico</option>
                                </select>
                            </li>
                            <li>
                                <label for="ora-da">Da ora: </label>
                                <input type="time" name="ora-da" id="ora-da" value="00:00"/>
                            </li>
                            <li>
                                <label for="ora-a">Da ora: </label>
                                <input type="time" name="ora-a" id="ora-a" value="23:59"/>
                            </li>
                            <li>
                                <label for="idlingua">Lingua: </label>
                                <select name="idlingua" id="idlingua">
                                    <option value="IT">Italiano</option>
                                    <option value="EN">English</option>
                                </select>
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
</section>

<section class="iscr-sg">
    <h3>I tuoi Study Group Attivi</h3>
    <div class="el-sgcard">
        <?php if(empty($templateParams["studygroupiscritto"])): ?>
            <p>Non sei ancora iscritto a nessuno Study Group. <a href="studygroup-list.php">Cercane uno!</a></p>
        <?php else: ?>
            <?php foreach($templateParams["studygroupiscritto"] as $sg): ?>
                <div class="sgcard">
                    <div class="sgimg">
                        <img src="img/esami/<?php echo ($sg["imgesame"] ? $sg["imgesame"] : "group.png"); ?>" alt=""/>
                    </div>
                    <div class="sgdesc">
                        <h4><?php echo e($sg["nomeesame"]); ?></h4>
                        <p><em>Tema: <?php echo e($sg["tema"]); ?></em></p>
                        <ul>
                            <li><strong>Dove:</strong> <?php echo e($sg["luogo"]); ?>
                                <?php if($sg["luogo"] == "Online"): ?>
                                    - <a href="<?php echo e($sg["dettaglioluogo"]); ?>" target="_blank">Link Aula</a>
                                <?php endif; ?>
                            </li>
                            <li><strong>Quando:</strong> <?php echo $sg["data"]; ?> alle <?php echo $sg["ora"]; ?></li>
                            <li>
                                <a href="studygroup.php?idcdl=<?php echo $sg["idcdl"]; ?>&idesame=<?php echo $sg["idesame"]; ?>&idstudygroup=<?php echo $sg["idstudygroup"]; ?>" class="btn-enter">Entra in Bacheca</a>
                            </li>
                            <li>
                                <a href="https://www.google.com/calendar/event?action=TEMPLATE&text=StudyBo: <?php echo urlencode($sg['nomeesame']); ?>&dates=<?php echo str_replace(['-', ':'], '', $sg['data'].'T'.$sg['ora']); ?>Z&details=Tema: <?php echo urlencode($sg['tema']); ?>" target="_blank" class="btn-cal">Aggiungi a Calendar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<script src="js/script.js"></script>
