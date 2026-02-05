<div class="sg-container">

    <header class="sezione">
        <h2><?php echo e($templateParams["info_gruppo"]["tema"]); ?></h2>
        <p>Esame: <strong><?php echo e($templateParams["info_gruppo"]["nomeesame"] ?? 'Esame'); ?></strong></p>
        <p>📍 <?php echo e($templateParams["info_gruppo"]["luogo"]); ?> | 📅 <?php echo e($templateParams["info_gruppo"]["data"]); ?> ore <?php echo e($templateParams["info_gruppo"]["ora"]); ?></p>
    </header>

    <section class="sg">
        <h2>Study Group di <?php echo $templateParams["studygroup"][0]["nomeesame"]; ?> - Gestione</h2>

        <nav class="admin-nav" aria-label="Navigazione Study Group">
            <ul class="admin-nav-list">
                <li class="admin-nav-item">
                    <a class="admin-nav-link is-active" href="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Gestione</a>
                </li>
                <li class="admin-nav-item">
                    <a class="admin-nav-link" href="risorsesg.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Risorse</a>
                </li>
                <li class="admin-nav-item">
                    <a class="admin-nav-link" href="bacheca2.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Messaggi</a>
                </li>
            </ul>
        </nav>

        <div class="contenuto-sg">
            <section class="dettaglio-sg sezione">

                <?php if(isset($templateParams["ritorno-iscrivi"])): ?>
                    <div class="alert alert-success"><?php echo $templateParams["ritorno-iscrivi"]; ?></div>
                <?php endif; ?>
                <?php if(isset($templateParams["ritorno-variazionesg"])): ?>
                    <div class="alert alert-success"><?php echo $templateParams["ritorno-variazionesg"]; ?></div>
                <?php endif; ?>

                <div class="card-esame">
                    <div class="card-img">

                    </div>

                    <div class="card-text">
                        <?php if(!isset($_GET["action"]) || $_GET["action"] !== "modifica"): ?>
                            <div class="sg-desc" id="sg-desc">
                                <ul class="card-meta">
                                    <li><strong>Tema:</strong> <?php echo $templateParams["dettaglio-sg"][0]["tema"]; ?></li>
                                    <li><strong>Luogo:</strong> <?php echo $templateParams["dettaglio-sg"][0]["luogo"]; ?></li>
                                    <li>
                                        <strong>Dettaglio luogo:</strong>
                                        <?php if($templateParams["dettaglio-sg"][0]["luogo"]=="Online"): ?>
                                            <a href="<?php echo $templateParams["dettaglio-sg"][0]["dettaglioluogo"]; ?>" target="_blank">Accedi al link</a>
                                        <?php else: ?>
                                            <?php echo $templateParams["dettaglio-sg"][0]["dettaglioluogo"]; ?>
                                        <?php endif; ?>
                                    </li>
                                    <li><strong>Data:</strong> <?php echo $templateParams["dettaglio-sg"][0]["data"]; ?></li>
                                    <li><strong>Ora:</strong> <?php echo $templateParams["dettaglio-sg"][0]["ora"]; ?></li>
                                    <li><strong>Lingua:</strong> <?php echo $templateParams["dettaglio-sg"][0]["descrizionelingua"]; ?></li>
                                    <li><strong>Partecipanti:</strong> <?php echo $templateParams["numPartecipanti"][0]["numpart"]; ?></li>
                                </ul>

                                <div class="admin-actions" id="button-sg">
                                    <?php if($templateParams["is_iscrittosg"]): ?>
                                        <a href="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>&action=modifica" class="btn-primary">Modifica</a>
                                    <?php endif; ?>

                                    <form action="#" method="POST" class="inline-form">
                                        <input type="<?php echo $disiscrivi; ?>" name="disiscrivi" value="Disiscrivi" class="btn-secondary"/>
                                    </form>

                                    <form <?php echo $iscrivi; ?> action="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>" method="POST" class="inline-form">
                                        <input type="submit" name="iscrivi" value="Iscriviti" class="btn-primary"/>
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php if($templateParams["is_iscrittosg"]): ?>
                                <form id="sg-anag" action="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>" method="POST" class="acc-form">
                                    <label class="acc-label" for="tema">Tema</label>
                                    <input class="sb-input" type="text" value="<?php echo $templateParams["dettaglio-sg"][0]["tema"]; ?>" id="tema" name="tema"/>

                                    <label class="acc-label" for="luogo">Luogo</label>
                                    <select class="sb-input" name="luogo" id="luogo" onchange="addDesc('#sg-anag')">
                                        <option value="Online" <?php echo $templateParams["dettaglio-sg"][0]["luogo"] == "Online" ? "selected" : ""; ?>>Online</option>
                                        <option value="Fisico" <?php echo $templateParams["dettaglio-sg"][0]["luogo"] == "Fisico" ? "selected" : ""; ?>>In fisico</option>
                                    </select>

                                    <label class="acc-label" for="data">Data</label>
                                    <input class="sb-input" type="text" value="<?php echo $templateParams["dettaglio-sg"][0]["data"]; ?>" id="data" name="data"/>

                                    <label class="acc-label" for="ora">Ora</label>
                                    <input class="sb-input" type="text" value="<?php echo $templateParams["dettaglio-sg"][0]["ora"]; ?>" id="ora" name="ora"/>

                                    <label class="acc-label" for="lingua">Lingua</label>
                                    <select class="sb-input" name="lingua" id="lingua">
                                        <?php foreach($templateParams["lingua"] as $lingua): ?>
                                            <option value="<?php echo $lingua["idlingua"]; ?>" <?php echo $templateParams["dettaglio-sg"][0]["idlingua"] == $lingua["idlingua"] ? "selected" : ""; ?>>
                                                <?php echo $lingua["descrizionelingua"]; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <div class="sg-edit-actions">
                                        <input type="submit" value="Salva" class="btn-primary"/>
                                        <a href="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>" class="btn-secondary link-annulla">Annulla</a>
                                    </div>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="sg-participants sezione">
                <h3>Elenco partecipanti</h3>
                <div class="sg-participants-grid">
                    <?php foreach($templateParams["partecipanti"] as $partecipante): ?>
                        <div class="sg-user">
                            <div class="sg-avatar">
                                <?php if($partecipante["imguser"] == ""): ?>
                                    <span class="sg-avatar-fallback">
                                        <?php echo strtoupper(substr($partecipante["nome"], 0, 1)) . strtoupper(substr($partecipante["cognome"], 0, 1)); ?>
                                    </span>
                                <?php else: ?>
                                    <img src="<?php echo UPLOAD_DIR . $partecipante["imguser"]; ?>" alt="" width="48" height="48"/>
                                <?php endif; ?>
                            </div>

                            <div class="sg-user-info">
                                <strong><?php echo $partecipante["username"]; ?></strong>
                                <a href="mailto:<?php echo $partecipante["username"]; ?>@studio.unibo.it" class="sg-user-mail">
                                    <img src="./upload/busta.png" alt="Email" width="20" height="20">
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </section>
</div>
