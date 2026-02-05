<section class="gest-utente">
    <div class="intestazione-user">
        <?php if($templateParams["imguser"] == ""): ?>
            <p class="sg-avatar">
                <?php echo strtoupper(substr($templateParams["nomeuser"], 0, 1)) . strtoupper(substr($templateParams["cognomeuser"], 0, 1)); ?>
            </p>
        <?php else: ?>
            <img class="imguser" src="<?php echo UPLOAD_DIR . $templateParams["imguser"]; ?>" alt="Immagine profilo"/>
        <?php endif; ?>
        <h2>Ciao <?php echo strtoupper(substr($templateParams["nomeuser"], 0, 1)) . substr($templateParams["nomeuser"], 1)?></h2>
    </div>

    <h3>Gestisci il tuo account</h3>

    <div class="changeanag">
        <h4>Variazione dati personali</h4>
        <?php if(isset($msg)): ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php endif; ?>
        
        <form action="#" method="POST" id="chgana" enctype="multipart/form-data" class="acc-form">
            <ul>
                <li>
                    <label for="nome" class="acc-label">Nome: </label>
                    <input type="text" name="nome" id="nome" class="sb-input" value="<?php echo $templateParams["nomeuser"]; ?>"/>
                </li>
                <li>
                    <label for="cognome" class="acc-label">Cognome: </label>
                    <input type="text" name="cognome" id="cognome" class="sb-input" value="<?php echo $templateParams["cognomeuser"]; ?>"/>
                </li>
                <li>
                    <label for="imguser" class="acc-label">Immagine Profilo: </label>
                    <input type="file" id="imguser" name="imguser" class="sb-input sb-file"/>
                </li>
                <li>
                    <input type="submit" name="submit" value="Invia" class="btn-primary"/>
                </li>
            </ul>
        </form>
        <button id="closeana" class="btn-toggle" onclick="closeForm('closeana', 'chgana', 'openana')">ʌ</button>
        <button id="openana" class="btn-toggle" onclick="openForm('closeana', 'chgana', 'openana')">v</button>
    </div>

    <div class="changepwd">
        <h4>Cambia Password</h4>
        <?php if(isset($templateParams["errorepassword"])): ?>
            <div class="alert alert-error"><?php echo $templateParams["errorepassword"]; ?></div>
        <?php endif; ?>
        
        <form action="#" method="POST" id="chgpwd" class="acc-form">
            <ul>
                <li>
                    <label for="oldPwd" class="acc-label">Vecchia password: </label>
                    <input type="password" name="oldPwd" id="oldPwd" class="sb-input" required/>
                </li>
                <li>
                    <label for="newPwd" class="acc-label">Nuova password: </label>
                    <div class="input-container"> <input type="password" name="newPwd" id="newPwd" class="sb-input" required/>
                        <img class="guardapwd" onclick="openEye('newPwd','guardaNewPwd')" id="guardaNewPwd" src="upload/occhiochiuso.png" alt="Mostra password" />
                    </div>
                </li>
                <li>
                    <label for="reNewPwd" class="acc-label">Conferma nuova password: </label>
                    <div class="input-container">
                        <input type="password" name="reNewPwd" id="reNewPwd" class="sb-input" required onkeyup="controlPwd()"/>
                        <img class="guardapwd" onclick="openEye('reNewPwd','guardaReNewPwd')" id="guardaReNewPwd" src="upload/occhiochiuso.png" alt="Mostra password" />
                    </div>
                </li>
                <li>
                    <input type="submit" value="Invia" class="btn-primary"/>
                </li>
            </ul>
        </form>
        <button id="closepwd" class="btn-toggle" onclick="closeForm('closepwd', 'chgpwd', 'openpwd')">ʌ</button>
        <button id="openpwd" class="btn-toggle" onclick="openForm('closepwd', 'chgpwd', 'openpwd')">v</button>
    </div>

    <div class="changecdl">
        <h4>Cambia Corso di Laurea</h4>
        <?php if(isset($templateParams["errorecdl"])): ?>
            <div class="alert alert-error"><?php echo $templateParams["errorecdl"]; ?></div>
        <?php endif; ?>
        
        <form id="chgcdl" action="#" method="POST" class="acc-form">
            <ul>
                <li>
                    <label for="newCdl" class="acc-label">Scegli il nuovo Corso di Laurea: </label>
                    <select name="newCdl" id="newCdl" class="sb-input">
                        <option value=""></option>
                        <?php foreach($templateParams["cdl"] as $cdl): ?>
                            <option value="<?php echo $cdl["idcdl"]; ?>"><?php echo $cdl["nomecdl"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                <li>
                    <input type="submit" value="Invia" class="btn-primary"/>
                </li>
            </ul>
        </form>
        <button id="closecdl" class="btn-toggle" onclick="closeForm('closecdl', 'chgcdl', 'opencdl')">ʌ</button>
        <button id="opencdl" class="btn-toggle" onclick="openForm('closecdl', 'chgcdl', 'opencdl')">v</button>
    </div>

    <div class="deletecdl">
        <h4>Elimina Account</h4>
        <?php if(isset($templateParams["erroredelete"])): ?>
            <div class="alert alert-error"><?php echo $templateParams["erroredelete"]; ?></div>
        <?php endif; ?>
        
        <form id="delacc" action="#" method="POST" class="acc-form">
            <ul>
                <li>
                    <label for="pwd" class="acc-label">Password: </label>
                    <div class="input-container">
                        <input type="password" name="pwd" id="pwd" class="sb-input"/>
                        <img class="guardapwd" onclick="openEye('pwd','guardapwddel')" id="guardapwddel" src="upload/occhiochiuso.png" alt="Mostra password" />
                    </div>
                </li>
                <li>
                    <input type="submit" value="Elimina Account" class="btn-delete"/>
                </li>
            </ul>
        </form>
        <button id="closedel" class="btn-toggle" onclick="closeForm('closedel', 'delacc', 'opendel')">ʌ</button>
        <button id="opendel" class="btn-toggle" onclick="openForm('closedel', 'delacc', 'opendel')">v</button>
    </div>

    <div class="gestione-preferenze">
        <h4>Gestisci le preferenze</h4>
        <div class="elenco-preferenze" id="elenco-preferenze">
            <h3>Elenco preferenze inserite</h3>
            <?php if(isset($templateParams["ritorno-elimina-preferenza"])): ?>
                <div class="alert alert-success"><?php echo $templateParams["ritorno-elimina-preferenza"]; ?></div>
            <?php endif; ?>
            
            <div class="elenco-prefcard">
                <?php foreach($templateParams["preferenza"] as $preferenza): ?>
                    <div class="prefcard">
                        <div class="prefimg">
                            <img src="<?php echo UPLOAD_DIR . $preferenza["imgesame"]; ?>" alt="Immagine esame"/>
                        </div>
                        <div class="prefdesc">
                            <h4><?php echo $preferenza["nomeesame"]; ?></h4>
                            <ul>
                                <li><strong>Luogo:</strong> <?php echo $preferenza["luogo"]; ?></li>
                                <li><strong>Orario:</strong> <?php echo $preferenza["daora"]; ?> - <?php echo $preferenza["aora"]; ?></li>
                                <li><strong>Lingua:</strong> <?php echo $preferenza["descrizionelingua"]; ?></li>
                            </ul>
                            <div class="admin-actions">
                                <button class="btn-primary btn-small" onclick='addFormModifica(...)'>Modifica</button>
                                <form action="" method="POST" class="inline-form">
                                    <input type="hidden" name="idcdl" value="<?php echo $preferenza["idcdl"]; ?>"/>
                                    <input type="hidden" name="idesame" value="<?php echo $preferenza["idesame"]; ?>"/>
                                    <input type="submit" name="elimina-pref" value="Elimina" class="btn-small-delete"/>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="prefcard card-crea-nuovo">
                    <div class="prefimg">
                        <img src="<?php echo UPLOAD_DIR; ?>plus.png" alt="Aggiungi"/>
                    </div>
                    <div class="prefdesc">
                        <h4>Aggiungi preferenza</h4>
                        <p class="card-meta">Inserisci un nuovo esame ai tuoi preferiti.</p>
                    </div>
                </div>
            </div>
        </div>
        <button id="closepref" class="btn-toggle" onclick="closeForm('closepref', 'elenco-preferenze', 'openpref')">ʌ</button>
        <button id="openpref" class="btn-toggle" onclick="openForm('closepref', 'elenco-preferenze', 'openpref')">v</button>
    </div>
</section>

<section class="iscr-sg">
    <h3>Study Group ai quali sei iscritto</h3>
    <div class="el-sgcard">
        <?php foreach($templateParams["studygroupiscritto"] as $studygroupiscritto): ?>
            <div class="sgcard">
                <div class="sgimg">
                    <img src="<?php echo UPLOAD_DIR . $studygroupiscritto["imgesame"]; ?>" alt="Esame"/>
                </div>
                <div class="sgdesc">
                    <h4><?php echo $studygroupiscritto["nomeesame"]; ?></h4>
                    <ul class="card-meta">
                        <li><strong>Tema:</strong> <?php echo $studygroupiscritto["tema"]; ?></li>
                        <li><strong>Data:</strong> <?php echo $studygroupiscritto["data"]; ?></li>
                    </ul>
                    <a href="studygroup.php?idcdl=<?php echo $studygroupiscritto["idcdl"]; ?>&idesame=<?php echo $studygroupiscritto["idesame"]; ?>&idstudygroup=<?php echo $studygroupiscritto["idstudygroup"]; ?>" class="btn-primary">Accedi</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script src="js/script.js"></script>