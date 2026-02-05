<section class="bacheca-sg">
    <header class="sezione">
        <h2><?php echo e($templateParams["info_gruppo"]["tema"]); ?></h2>
        <p>Esame: <strong><?php echo e($templateParams["studygroup"][0]["nomeesame"] ?? 'Esame'); ?></strong></p>
        <p>📍 <?php echo e($templateParams["info_gruppo"]["luogo"]); ?> | 📅 <?php echo e($templateParams["info_gruppo"]["data"]); ?> ore <?php echo e($templateParams["info_gruppo"]["ora"]); ?></p>
    </header>

    <nav class="admin-nav" aria-label="Navigazione Study Group">
        <ul class="admin-nav-list">
            <li class="admin-nav-item">
                <a class="admin-nav-link" href="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Gestione</a>
            </li>
            <li class="admin-nav-item">
                <a class="admin-nav-link" href="risorsesg.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Risorse</a>
            </li>
            <li class="admin-nav-item">
                <a class="admin-nav-link is-active" href="bacheca2.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Messaggi</a>
            </li>
        </ul>
    </nav>

    <div class="struttura-msg">
        <section class="sezione card">
            <h3>Messaggi dello Study Group</h3>
            <ul id="lista-messaggi-ajax" class="lista-messaggi">
                <?php foreach($templateParams["messaggio"] as $m):?>
                    <li class="msg-item">
                        <div class="card-msg">
                            <div class="user-msg">
                                <strong class="msg-username"><?php echo $m["username"]; ?></strong>
                                <small class="text-muted"> - <?php echo $m["datamsg"] . " " . $m["oramsg"]; ?></small>
                            </div>
                            <div class="testo-msg">
                                <p><?php echo e($m["testomessaggio"]); ?></p>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="nuovo-msg card">
            <h3>Scrivi un nuovo messaggio</h3>
            <?php if(isset($templateParams["ritorno-creamessaggio"])): ?>
                <p class="alert alert-success"><?php echo $templateParams["ritorno-creamessaggio"]; ?></p>
            <?php endif; ?>

            <form id="crea-msg" action="bacheca2.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>" method="POST" class="acc-form">
                <div class="card-nuovomsg">
                    <ul class="msg-form-list">
                        <li class="msg-form-row">
                            <strong>Invia come: <?php echo $_SESSION["username"]; ?></strong>
                        </li>
                        <li class="msg-form-row">
                            <label for="testomsg" class="acc-label">Messaggio: </label>
                            <textarea id="testomsg" name="testomsg" class="sb-input msg-textarea" rows="3" placeholder="Scrivi qui..."></textarea>
                        </li>
                        <li>
                            <input type="submit" value="Invia Messaggio" class="btn-primary"/>
                        </li>
                    </ul>
                </div>
            </form>
        </section>
    </div>
</section>
