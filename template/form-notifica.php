<section class="notifica">
    <h2>Centro Notifiche</h2>
    <?php if(isset($templateParams["aggiorna-destnotificapref"])): ?>
            <?php echo $templateParams["aggiorna-destnotificapref"]; ?>
        <?php endif; ?>
        <?php if(isset($templateParams["aggiorna-notifica"])): ?>
            <?php echo $templateParams["aggiorna-notifica"]; ?>
        <?php endif; ?>
        <?php if(isset($templateParams["aggiorna-destnotifica"])): ?>
            <?php echo $templateParams["aggiorna-destnotifica"]; ?>
        <?php endif; ?>
        <?php if(isset($templateParams["risposta-notificaris"])): ?>
            <?php echo $templateParams["risposta-notificaris"]; ?>
        <?php endif; ?>
        <?php if(isset($templateParams["aggiorna-risorsa"])): ?>
            <?php echo $templateParams["aggiorna-risorsa"]; ?>
        <?php endif; ?>
    <div class="tab-notifica">
        <table>
            <thead>
                <tr>
                    <th id="tipo">Tipologia</th>
                    <th id="descrizione">Descrizione</th>
                    <th id="mittente">Mittente</th>
                    <th id="azioni">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($templateParams["notificaris"] as $notificaris): ?>
                    <tr>
                        <td header="tipo">Risorsa</td>
                        <td header="descrizione"><a href="<?php echo UPLOAD_DIR . $notificaris["filerisorsa"]; ?>" target="_blank"><?php echo $notificaris["nomeris"]; ?></a><?php if($templateParams["amministratore"] == 0 && $notificaris["autorizzata"] == 1): ?>
                            - Autorizzata - Note: <?php echo $notificaris["commento"]; ?>
                        <?php elseif($templateParams["amministratore"] == 0 && $notificaris["autorizzata"] == 0): ?>
                            - Non autorizzata - Note: <?php echo $notificaris["commento"]; ?>
                        <?php endif; ?>
                        </td>
                        <td header="mittente"><?php echo $notificaris["mittente"]; ?></td>
                        <td header="azioni">
                            <form action="notifica.php" method="POST">
                                <?php if($templateParams["amministratore"] == 1): ?>
                                    <label for="autorizza<?php echo $notificaris["idcdl"] . $notificaris["idesame"] . $notificaris["idstudygroup"] . $notificaris["idrisorsa"] . $notificaris["idnotifica"]; ?>">Autorizza</label>
                                    <select name="autorizza" id="autorizza<?php echo $notificaris["idcdl"] . $notificaris["idesame"] . $notificaris["idstudygroup"] . $notificaris["idrisorsa"] . $notificaris["idnotifica"]; ?>">
                                        <option value="1">SI</option>
                                        <option value="0">NO</option>
                                    </select>
                                    <label for="note<?php echo $notificaris["idcdl"] . $notificaris["idesame"] . $notificaris["idstudygroup"] . $notificaris["idrisorsa"] . $notificaris["idnotifica"]; ?>">Note: </label>
                                    <textarea cols="40" rows="2" name="note" id="note<?php echo $notificaris["idcdl"] . $notificaris["idesame"] . $notificaris["idstudygroup"] . $notificaris["idrisorsa"] . $notificaris["idnotifica"]; ?>"></textarea>
                                    
                                    <input type="submit" name="submitris" value="Invia"/>
                                <?php else: ?>
                                    <input type="submit" name="submitris" value="Conferma Lettura"/>
                                <?php endif; ?>
                                <input type="hidden" name="mittente" value="<?php echo $notificaris["mittente"]; ?>"/>
                                <input type="hidden" name="cdl" value="<?php echo $notificaris["idcdl"]; ?>"/>
                                <input type="hidden" name="esame" value="<?php echo $notificaris["idesame"]; ?>"/>
                                <input type="hidden" name="studygroup" value="<?php echo $notificaris["idstudygroup"]; ?>"/>
                                <input type="hidden" name="risorsa" value="<?php echo $notificaris["idrisorsa"]; ?>"/>
                                <input type="hidden" name="notifica" value="<?php echo $notificaris["idnotifica"]; ?>"/>
                                
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php foreach($templateParams["notificavarsg"] as $notificavarsg): ?>
                    <tr>
                        <td header="tipo">Variazione SG</td>
                        <td header="descrizione">Nuovi dati: <?php echo $notificavarsg["nomecdl"] . " - " . $notificavarsg["nomeesame"] . ": " . $notificavarsg["tema"] . " - " . $notificavarsg["luogo"] . " - " . $notificavarsg["dettaglioluogo"] . " - " . $notificavarsg["data"] . " - " . $notificavarsg["ora"]; ?></td>
                        <td header="mittente"><?php echo $notificavarsg["mittente"]; ?></td>
                        <td header="azioni">
                            <form action="" method="POST">
                                <input type="hidden" name="cdl" value="<?php echo $notificavarsg["idcdl"]; ?>"/>
                                <input type="hidden" name="esame" value="<?php echo $notificavarsg["idesame"]; ?>"/>
                                <input type="hidden" name="studygroup" value="<?php echo $notificavarsg["idstudygroup"]; ?>"/>
                                <input type="hidden" name="notifica" value="<?php echo $notificavarsg["idnotifica"]; ?>"/>
                                <input type="submit" name="submitvarsg" value="Conferma Lettura"/>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php foreach($templateParams["notificapreferenza"] as $notificapref): ?>
                    <tr>
                        <td header="tipo">Preferenza su SG</td>
                        <td header="descrizione">Dati Study Group: <?php echo $notificapref["nomecdl"] . " - " . $notificapref["nomeesame"] . ": " . $notificapref["nomeesame"] . " - " . $notificapref["tema"] . " - " . $notificapref["luogo"] . " - " . $notificapref["dettaglioluogo"] . " - " . $notificapref["data"] . " - " . $notificapref["ora"] . " - "; ?><a href="studygroup.php?idcdl=<?php echo $notificapref["idcdl"]; ?>&idesame=<?php echo $notificapref["idesame"]; ?>&idstudygroup=<?php echo $notificapref["idstudygroup"]; ?>">Accedi a SG</a></td>
                        <td header="mittente"><?php echo $notificapref["mittente"]; ?></td>
                        <td header="azioni">
                            <form action="" method="POST">
                                <input type="hidden" name="cdl" value="<?php echo $notificapref["idcdl"]; ?>"/>
                                <input type="hidden" name="esame" value="<?php echo $notificapref["idesame"]; ?>"/>
                                <input type="hidden" name="studygroup" value="<?php echo $notificapref["idstudygroup"]; ?>"/>
                                <input type="hidden" name="notifica" value="<?php echo $notificapref["idnotifica"]; ?>"/>
                                <input type="submit" name="submitpref" value="Conferma Lettura"/>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>