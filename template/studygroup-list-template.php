<section class="sezione" aria-labelledby="sg-list-title">
    <div class="section-head">
        <h2 id="sg-list-title">Tutti gli Study Group</h2>
        <p class="section-hint">Scegli un gruppo per iniziare a studiare in compagnia.</p>
    </div>

    <div class=hero id="lista-sg">
        <h2>Cerca lo Study Group di interesse</h2>
        <form action="" method="POST">
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
                <?php elseif(!empty($_GET["idcdl"])): ?>
                    <input type="hidden" id="idcdl" name="idcdl" value="<?php echo $_GET["idcdl"]; ?>"/>
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

    <div class="griglia-corsi">
        <?php if(empty($templateParams["gruppi"])): ?>
            <p>Al momento non ci sono gruppi di studio disponibili.</p>
        <?php else: ?>
            <?php foreach($templateParams["gruppi"] as $g): ?>
                <article class="card-corso">
                    <div class="card-icon">
                        <img src="img/esami/<?= e($g['ImgEsame'] ? $g['ImgEsame'] : 'logo.libro.PNG') ?>" alt="">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><?= e($g['NomeEsame']) ?></h3>

                        <p class="card-meta"><strong>Corso:</strong> <?= e($g['NomeCdl'] ?? 'N/D') ?></p>
                        <p class="card-meta"><strong>Tema:</strong> <?= e($g['tema']) ?></p>

                        <ul class="sg-card-info">
                            <li>📍 <?= e($g['luogo']) ?></li>
                            <li>📅 <?= e($g['data']) ?> alle <?= e($g['ora']) ?></li>
                        </ul>

                        <div class="card-actions">
                            <?php if(isset($_SESSION["username"])): ?>
                                <a href="studygroup.php?idcdl=<?= $g['idcdl'] ?>&idesame=<?= $g['idesame'] ?>&idstudygroup=<?= $g['idstudygroup'] ?>" class="btn-primary">
                                    Vedi dettagli →
                                </a>
                            <?php else: ?>
                                <a href="login.php" class="btn-secondary">
                                    Accedi per partecipare
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
