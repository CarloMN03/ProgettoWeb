<section class="esame">
    <h2><?php echo $templateParams["cdlesame"][0]["nomecdl"]; ?></h2>

    <?php if(!empty($templateParams["flash_msg"])): ?>
        <p class="msg-ok" role="status"><?php echo e($templateParams["flash_msg"]); ?></p>
    <?php endif; ?>

    <?php if(!empty($templateParams["flash_err"])): ?>
        <p class="msg-err" role="alert"><?php echo e($templateParams["flash_err"]); ?></p>
    <?php endif; ?>
    
    <div class="card-esame">
        <div class="card-text">
            <h3><?php echo $templateParams["esame"][0]["nomeesame"]; ?></h3>
            <ul>
                <li><strong>Campus:</strong> <?php echo $templateParams["cdlesame"][0]["sede"]; ?></li>
                <li><strong>Study Group attivi:</strong> <?php echo $templateParams["stgresame"][0]["numsg"]; ?></li>
                <?php if(isset($_SESSION["username"])): ?>
                    <li><button type="button" onclick="addPreferenza()">Aggiungi ai Preferiti</button></li>
                <?php endif; ?>
            </ul>
            <div class="form-pref"></div>
        </div>    
    </div>   

    <div class="study-gr-corso">
        <h3>Elenco Study Group attivi sul corso</h3>
        
        <?php foreach($templateParams["studygrattivi"] as $studygr): ?>
            <a href="studygroup.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>&idstudygroup=<?php echo $studygr["idstudygroup"]; ?>">   
                <div class="card-study-gr">
                    <div class="card-stGr-text">
                        <h4><?php echo $templateParams["esame"][0]["nomeesame"]; ?></h4>
                        <ul>
                            <li><strong>Tema:</strong> <?php echo $studygr["tema"]; ?></li>
                            <li><strong>Luogo:</strong> <?php echo $studygr["luogo"]; ?></li>
                            <li><strong>Data:</strong> <?php echo $studygr["data"]; ?></li>
                            <li><strong>Ora:</strong> <?php echo $studygr["ora"]; ?></li>
                            <li><strong>Lingua:</strong> 
                                <?php foreach($templateParams["lingua"] as $lingua): ?>
                                    <?php if($lingua["idlingua"] == $studygr["idlingua"]) echo $lingua["descrizionelingua"]; ?>
                                <?php endforeach; ?>
                            </li>
                        </ul>
                        <div class="part-stGr">
                            <span>Partecipanti: </span>
                            <div class="conta-part">
                                <?php 
                                $p = 0; 
                                if(!empty($templateParams["partecipanti"])){
                                    foreach($templateParams["partecipanti"] as $partecipante) {
                                        if($partecipante["idstudygroup"] == $studygr["idstudygroup"]) $p++;
                                    }
                                } 
                                echo $p; 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>

        <a href="crea-sg.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>">
            <div class="card-study-gr">
                <div class="card-stGr-text">
                    <h4>+ Crea nuovo Study Group</h4>
                </div>
            </div>
        </a>
    </div>
</section>
