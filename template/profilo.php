<h2>Ciao <?php echo $templateParams["nomeuser"]?></h2>
        <section class="gest-utente">
            <h3>Gestisci il tuo account</h3>
            <div class="changepwd">
                <h4>Cambia Password</h4>
                <?php if(isset($templateParams["errorepassword"])): ?>
                <p><?php echo $templateParams["errorepassword"]; ?></p>
                <?php endif; ?>
                <form action="#" method="POST" id="chgpwd">
                    <ul>
                        <li>
                            <label for="oldPwd">Vecchia password: </label><input type="password" name="oldPwd" id="oldPwd"/>
                        </li>
                        <li>
                            <label for="newPwd">Nuova password: </label><input type="password" name="newPwd" id="newPwd"/>
                        </li>
                        <li>
                            <label for="reNewPwd">Conferma nuova password: </label><input type="password" name="reNewPwd" id="reNewPwd"/>
                        </li>
                        <li>
                            <input type="submit" value="Invia"/>
                        </li>
                    </ul>
                </form>
                <input type="button" id="closepwd" value="ʌ" onclick="closeForm('closepwd', 'chgpwd', 'openpwd')">
                <input type="button" id="openpwd" value="v" onclick="openForm('closepwd', 'chgpwd', 'openpwd')">
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
                <input type="button" id="closecdl" value="ʌ" onclick="closeForm('closecdl', 'chgcdl', 'opencdl')">
                <input type="button" id="opencdl" value="v" onclick="openForm('closecdl', 'chgcdl', 'opencdl')">
            </div>
            <div class="deletecdl">
                <h4>Elimina Account</h4>
                <?php if(isset($templateParams["erroredelete"])): ?>
                <p><?php echo $templateParams["erroredelete"]; ?></p>
                <?php endif; ?>
                <form id="delacc" action="#" method="POST">
                    <ul>
                        <li>
                            <label for="pwd">Password: </label><input type="password" name="pwd" id="pwd"/>
                        </li>
                        <li>
                            <input type="submit" value="Elimina Account"/>
                        </li>
                    </ul>
                </form>
                <input type="button" id="closedel" value="ʌ" onclick="closeForm('closedel', 'delacc', 'opendel')">
                <input type="button" id="opendel" value="v" onclick="openForm('closedel', 'delacc', 'opendel')">
            </div>
        </section>
        <section class="iscr-sg">
            <h3>Study Group ai quali sei iscritto</h3>
            <?php foreach($templateParams["studygroupiscritto"] as $studygroupiscritto): ?>
            <div class="sgcard">
                <div class="sgimg">
                    <img src="<?php echo UPLOAD_DIR . $studygroupiscritto["imgesame"]; ?>" alt=""/>
                </div><div class="sgdesc">
                    <h5><?php echo $studygroupiscritto["nomeesame"]; ?></h5>
                    <ul>
                        <li>Tema: <?php echo $studygroupiscritto["tema"]; ?></li>
                        <li>Luogo: <?php echo $studygroupiscritto["luogo"]; ?></li>
                        <li>Lingua: <?php echo $studygroupiscritto["descrizionelingua"]; ?></li>
                        <li>Data: <?php echo $studygroupiscritto["data"]; ?></li>
                        <li>Ora: <?php echo $studygroupiscritto["ora"]; ?></li>
                    </ul>
                </div>
                <div class="sginvito">
                    <p>Invita amici</p>
                </div>
                <div class="sgaccedi">
                    <p>Accedi a Study Group</p>
                </div>
                <div class="sgdisiscrivi">
                    <p>Disiscriviti</p>
                </div>
                <div class="sgcal">
                    <p><a href="https://www.google.com/calendar/event?action=TEMPLATE&dates=<?php echo substr($studygroupiscritto["data"],0,4) . substr($studygroupiscritto["data"],5,2) . substr($studygroupiscritto["data"],8,2) . "T" . substr($studygroupiscritto["ora"],0,2) . substr($studygroupiscritto["ora"],3,2) . "00"; ?>%2F<?php echo substr($studygroupiscritto["data"],0,4) . substr($studygroupiscritto["data"],5,2) . substr($studygroupiscritto["data"],8,2) . "T" . substr($studygroupiscritto["ora"],0,2) . substr($studygroupiscritto["ora"],3,2) . "00"; ?>&text=Studygroup%20<?php echo $studygroupiscritto["nomeesame"]; ?>%20Tema:%20<?php echo $studygroupiscritto["tema"]; ?>&location=<?php echo $studygroupiscritto["luogo"]; ?>&details=lingua:%20<?php echo $studygroupiscritto["descrizionelingua"]; ?>">Aggiungi a Google Calendar</a></p>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
        <script src="js/script.js"></script>