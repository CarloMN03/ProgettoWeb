        <section class="esame">
            <h2><?php echo $templateParams["cdlesame"][0]["nomecdl"]; ?></h2>
            <div class="card-esame">
                <div class="card-img"><img src="<?php echo UPLOAD_DIR . $templateParams["esame"][0]["imgesame"]; ?>" alt=""></div>
                <div class="card-text">
                    <h3><?php echo $templateParams["esame"][0]["nomeesame"]; ?></h3>
                    <ul>
                        <li>Campus: <?php echo $templateParams["cdlesame"][0]["sede"]; ?></li>
                        <li>Study Group attivi: <?php echo $templateParams["stgresame"][0]["numsg"]; ?></li>
                        <?php if(isset($_SESSION["username"])): ?>
                            <li><button onclick="addPreferenza()">Aggiungi ai Preferiti</button></li>
                        <?php endif; ?>
                    </ul>
                    <div class="form-pref">    
                    </div>
                </div>    
            </div>   
            <div class="study-gr-corso">
                <h3>Elenco Study Group attivi sul corso</h3>
                <?php foreach($templateParams["studygrattivi"] as $studygr): ?>
                 <a href="studygroup.php?idcdl=<?php echo($_GET["idcdl"]); ?>&idesame=<?php echo($_GET["idesame"]); ?>&idstudygroup=<?php echo($studygr["idstudygroup"]); ?>">   
                <div class="card-study-gr">
                    <div class="card-stGr-img"><img src="<?php echo UPLOAD_DIR . $templateParams["esame"][0]["imgesame"]; ?>" alt=""/>
                    </div>
                    <div class="card-stGr-text">
                        <h4><?php echo $templateParams["esame"][0]["nomeesame"]; ?></h4>
                        <ul>
                            <li>Tema: <?php echo $studygr["tema"]; ?></li>
                            <li>Luogo: <?php echo $studygr["luogo"]; ?></li>
                            <li>Data: <?php echo $studygr["data"]; ?></li>
                            <li>Ora: <?php echo $studygr["ora"]; ?></li>
                            <li>Lingua: <?php foreach($templateParams["lingua"] as $lingua): ?><?php if($lingua["idlingua"] == $studygr["idlingua"]){
                                echo $lingua["descrizionelingua"];
                            }?><?php endforeach; ?></li>
                        </ul>
                        <div class="part-stGr">
                            <img src="./upload/cuore.png" alt=""/>
                            <div class="conta-part"><?php $p = 0; if(!empty($templateParams["partecipanti"])){
                                foreach($templateParams["partecipanti"] as $partecipante) : ?><?php if($partecipante["idstudygroup"] == $studygr["idstudygroup"]){
                                $p++;
                            }; ?>
                            <?php endforeach;} echo $p; ?></div>
                        </div>
                    </div>
                    
                </div></a>
                <?php endforeach; ?>
                <a href="crea-sg.php?idcdl=<?php echo $_GET["idcdl"]; ?>&idesame=<?php echo $_GET["idesame"]; ?>">
                    <div class="card-study-gr">
                        <div class="card-stGr-img"><img src="./upload/plus.png" alt=""/>
                        </div>
                        <div class="card-stGr-text">
                            <h4>Crea nuovo Study Group</h4>
                        </div>
                    </div>
                </a>
            </div>
        </section>