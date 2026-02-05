<section class="risorse-sg">
    <h2>Study Group di <?php echo $templateParams["studygroup"][0]["nomeesame"]; ?> - Risorse</h2>

    <nav class="admin-nav" aria-label="Navigazione Study Group">
        <ul class="admin-nav-list">
            <li class="admin-nav-item">
                <a class="admin-nav-link" href="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Gestione</a>
            </li>
            <li class="admin-nav-item">
                <a class="admin-nav-link is-active" href="risorsesg.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Risorse</a>
            </li>
            <li class="admin-nav-item">
                <a class="admin-nav-link" href="bacheca2.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Messaggi</a>
            </li>
        </ul>
    </nav>

    <div class="risorse-layout">
        <section class="carica-ris card">
            <form id="ins-ris" class="ris-form" action="risorsesg.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>" enctype="multipart/form-data" method="POST">
                <h3>Carica nuova risorsa</h3>
                
                <?php if(isset($msg)): ?>
                    <p class="alert alert-success"><?php echo $msg; ?></p>
                <?php endif; ?>

                <ul class="form-list">
                    <li>
                        <label for="nomeris">Nome File:</label>
                        <input type="text" id="nomeris" name="nomeris" class="sb-input"/>
                    </li>
                    <li>
                        <label for="private">Riservata allo Study Group:</label>
                        <select name="private" id="private" class="sb-input">
                            <option value="1">SI</option>
                            <option value="0">NO</option>
                        </select>
                    </li>
                    <li>
                        <label for="risorsa">Risorsa:</label>
                        <input type="file" id="risorsa" name="risorsa" class="sb-input"/>
                    </li>
                    <li>
                        <button type="submit" name="submit" class="btn-primary">Carica</button>
                    </li>
                </ul>
            </form>
        </section>

        <section class="elenco-ris card">
            <h3>Risorse caricate nello Study Group</h3>
            
            <?php if(isset($templateParams["ritorno-rimuovi-ris"])): ?>
                <p class="alert alert-success"><?php echo $templateParams["ritorno-rimuovi-ris"]; ?></p>
            <?php endif; ?>

            <div class="tabella-ris">
                <?php if(empty($templateParams["risorsa"])): ?>
                    <p class="muted">Al momento non sono state caricate risorse sullo Study Group.</p>
                <?php else: ?>
                    <table class="ris-table">
                        <thead>
                            <tr>
                                <th>Nome risorsa</th>
                                <th>Inserita da</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($templateParams["risorsa"] as $risorsa) : ?>
                                <?php if($risorsa["notifica"] == 0): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo UPLOAD_DIR . $risorsa["filerisorsa"]; ?>">
                                                <?php echo $risorsa["nomeris"]; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $risorsa["username"]; ?></td>
                                        <td>
                                            <?php if($risorsa["username"] == $_SESSION["username"]): ?>
                                                <form action="" method="POST" class="inline-form">
                                                    <input type="hidden" name="idrisorsa" value="<?php echo $risorsa["idrisorsa"]; ?>"/>
                                                    <button type="submit" name="rimuovi-ris" class="btn-secondary btn-small">Rimuovi</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>      
                    </table>
                <?php endif; ?>
            </div>  
        </section> 
    </div>
</section>