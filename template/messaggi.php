<section class="msg">
    <h2>Study Group di <?php echo $templateParams["studygroup"][0]["nomeesame"]; ?> - Messaggi</h2>
    <nav id="nav-sg">
        <ul>
            <li><a href="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Gestione</a></li><li><a href="risorsesg.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Risorse</a></li><li><a href="bacheca.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>">Messaggi</a></li>
        </ul>
    </nav>
    <div class="struttura-msg">
        <h3>Messaggi dello Study Group</h3>
        <ul>
            <?php foreach($templateParams["messaggio"] as $messaggio):?>
                <li>
                    <div class="el-msg">
                        <div class="img-msg">
                            <?php if($messaggio["imguser"] == ""): ?>
                                <p><?php echo strtoupper(substr($messaggio["nome"], 0, 1)) . strtoupper(substr($messaggio["cognome"], 0, 1)); ?></p>
                            <?php else: ?>
                                <img class="imguser" src="<?php echo UPLOAD_DIR . $messaggio["imguser"]; ?>" alt=""/>
                            <?php endif; ?>
                        </div>
                        <div class="card-msg">
                            <div class="user-msg">
                                <h4><?php echo $messaggio["username"] . " - " . $messaggio["datamsg"] . "-" . $messaggio["oramsg"]; ?></h4>
                            </div>
                            <div class="testo-msg">
                                <p><?php echo $messaggio["testomessaggio"];?></p>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="nuovo-msg">
        <h3>Scrivi un nuovo messaggio</h3>
        <?php if(isset($templateParams["ritorno-creamessaggio"])): ?>
            <h4><?php echo $templateParams["ritorno-creamessaggio"]; ?></h4>
        <?php endif; ?>
        <form id="crea-msg" action="bacheca.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $_GET["idstudygroup"]; ?>" method="POST">
            <div class="img-msg">
                <?php if($templateParams["user"][0]["imguser"] == ""): ?>
                    <p><?php echo strtoupper(substr($templateParams["user"][0]["nome"], 0, 1)) . strtoupper(substr($templateParams["user"][0]["cognome"], 0, 1)); ?></p>
                <?php else: ?>
                    <img class="imguser" src="<?php echo UPLOAD_DIR . $templateParams["user"][0]["imguser"]; ?>" alt=""/>
                <?php endif; ?>
            </div>  
            <div class="card-nuovomsg">
                <ul>
                    <li><h4><?php echo $_SESSION["username"]; ?></h4></li>
                    <li><label for="testomsg">Testo: </label><input type="text" id="testomsg" name="testomsg"/></li>
                    <li><input type="submit" value="Invia"/></li>
                </ul>
            </div>            
        </form>
    </div>
</section>