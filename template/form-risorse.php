<section class="risorse-sg">
    <h2>Study Group di <?php echo $templateParams["studygroup"][0]["nomeesame"]; ?> - Risorse</h2>
    <nav id="nav-sg">
        <ul>
            <li><a href="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Gestione</a></li><li><a href="risorsesg.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Risorse</a></li><li><a href="bacheca.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Messaggi</a></li>
        </ul>
    </nav>
    <div id="risorse">
        <section class="carica-ris">
            <form id="ins-ris" action="risorsesg.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?> " enctype="multipart/form-data" method="POST">
                <h3>Carica nuova risorsa</h3>
                <?php if(isset($msg)): ?>
                    <h3><?php echo $msg; ?></h3>
                <?php endif; ?>
                <ul>
                    <li>
                        <label for="nomeris">Nome File:</label><input type="text" id="nomeris" name="nomeris"/>
                    </li>
                    <li>
                        <label for="private">Riservata allo Study Group:</label><select name="private" id="private"><option value=1>SI</option><option value=0>NO</option></select>
                    </li>
                    <li>
                        <label for="risorsa">Risorsa</label><input type="file" id="risorsa" name="risorsa"/>
                    </li>
                    <li>
                        <input type="submit" name="submit" value="Carica"/>
                    </li>
                </ul>
            </form>
        </section>
        <section class="elenco-ris">
            <h3>Risorse caricate nello Study Group</h3>
            <div class="tabella-ris">
                <?php if(empty($templateParams["risorsa"])): ?>
                    <h3>Al momento non sono state caricate risorse sullo Study Group</h3>
                <?php endif; ?>
                <table <?php echo $tabella; ?>>  
                    <tr>
                        <th id="nomeris">Nome risorsa</th><th id="usercarica">Inserita da</th>
                    </tr>
                        <?php foreach($templateParams["risorsa"] as $risorsa) : ?>
                            <?php if($risorsa["notifica"] == 0): ?>
                                <tr>
                                    <td header="nomeris"><a href="<?php echo UPLOAD_DIR . $risorsa["filerisorsa"]; ?>"><?php echo $risorsa["nomeris"]; ?></a></td><td headers="usercarica"><?php echo $risorsa["username"]; ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                </table>
            </div>  
        </section> 
    </div>
</section>