<section class="cerca-sg">
    <div class="formsg">
        <h2>Cerca lo Study Group di interesse</h2>
    <form action="" method="GET">
        <ul>
            <?php if(!empty($_SESSION["username"])): ?>
                <input type="hidden" id="idcdl" name="idcdl" value="<?php echo $templateParams["idcdl"]; ?>"/>
                <li>
                    <label for="idesame" name="idesame">Esame: </label><select name="idesame" id="idesame">
                    <?php foreach($templateParams["esami"] as $esame): ?>
                        <option value="<?php echo $esame["idesame"]; ?>"><?php echo $esame["nomeesame"]; ?></option>  
                    <?php endforeach; ?>
                    </select>
                </li>
            <?php else: ?>
                <li>
                    <label for="idcdl" name="idcdl">Corso di Laurea: </label><select onchange="addEsami()" name="idcdl" id="idcdl">
                        <option value="">--Seleziona un cdl--</option>
                        <?php foreach($templateParams["cdl"] as $cdl): ?>
                            <option value="<?php echo $cdl["idcdl"]; ?>"><?php echo $cdl["nomecdl"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                <li>  
                </li>
            <?php endif; ?>
            <li>
                <label for="luogo" name="luogo">Luogo: </label><select name="luogo" id="luogo">
                    <option value="Online">Online</option>
                    <option value="Fisico">In Fisico</option>
                </select>
            </li>
            <li>
                <label for="daora" name="daora">Da ora: </label><input id="daora" name="daora" value="00:00" type="time"/>
            </li>
            <li>
                <label for="aora" name="aora">A ora: </label><input id="aora" name="aora" value="23:59" type="time"/>
            </li>
            <li>
                <label for="idlingua" name="idlingua">Lingua: </label><select name="idlingua" id="idlingua">
                    <?php foreach($templateParams["lingua"] as $lingua): ?>
                        <option value="<?php echo $lingua["idlingua"]; ?>"><?php echo $lingua["descrizionelingua"]; ?></option>
                    <?php endforeach; ?>
                </select>
            </li>
            <li><input type="submit" name="submit" value="Cerca"/></li>
        </ul>
    </form>
    </div>
</section>
<?php if(isset($templateParams["elencostudygroup"])): ?>
    <section>
        <div class="elencosg">
            <?php if(empty($templateParams["elencostudygroup"])): ?>
                <h3>Non sono presenti Study Group con le caratteristiche cercate!</h3>
                <p>Puoi crearne uno oppure aggiungere un esame ai tuoi preferiti effettuando l'accesso e accedendo alla pagina <a href="elenco-cdl.php">Corsi di Laurea</a></p>
            <?php endif; ?>
            <?php foreach($templateParams["elencostudygroup"] as $sg):?>
                <div class="sgcard">
                        <div class="sgimg">
                            <img src="<?php echo UPLOAD_DIR . $sg["imgesame"]; ?>" alt=""/>
                        </div><div class="sgdesc">
                            <h4><?php echo $sg["nomeesame"]; ?></h4>
                            <ul>
                                <li>Tema: <?php echo $sg["tema"]; ?></li>
                                <li>Luogo: <?php echo $sg["luogo"]; ?></li>
                                <li>Dettaglio luogo: <?php if($sg["luogo"]=="Online"): ?>
                                    <a href="<?php echo $sg["dettaglioluogo"]; ?>" target="_blank">Accedi al link</a>
                                <?php else: ?>
                                    <?php echo $sg["dettaglioluogo"]; ?>
                                <?php endif; ?>
                                </li>
                                <li>Lingua: <?php echo $sg["descrizionelingua"]; ?></li>
                                <li>Data: <?php echo $sg["data"]; ?></li>
                                <li>Ora: <?php echo $sg["ora"]; ?></li>
                                <li><a href="studygroup.php?idcdl=<?php echo $sg["idcdl"]; ?>&idesame=<?php echo $sg["idesame"]; ?>&idstudygroup=<?php echo $sg["idstudygroup"]; ?>">Accedi</a></li>
                            </ul>
                        </div>
                    </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>