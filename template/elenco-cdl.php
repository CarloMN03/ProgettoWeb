<h2>Corsi di Laurea con Study Group attivi</h2>
        <section class="el-cdl">
            <?php foreach($templateParams["cdl"] as $cdl) : ?>
                <div id="card-cdl">
                    <div id="card-img"><img src="<?php echo UPLOAD_DIR . $cdl["img"]; ?>" alt=""></div>
                    <div id="card-text">
                        <h3><?php echo $cdl["nomecdl"]; ?></h3>
                        <ul>
                            <li>Campus: <?php echo $cdl["sede"]; ?></li>
                            <li>Esami con Study Group: <?php $e = 0;
                            foreach($templateParams["numstudygr"] as $esamistgr):
                                if($esamistgr["idcdl"] == $cdl["idcdl"]) {
                                    $e++;
                                }
                            endforeach;
                            echo $e; ?></li>
                        </ul>
                    </div>
                </div>
                <div id="study-gr-anno">
                    <h3>Esami con Study Group attivi</h3>
                    <ul>
                        <?php for($i = 1; $i <= $cdl["durata"]; $i++) : ?>
                            <li><?php echo $i; ?> anno</li>
                            <div class="el-esami" id="<?php echo $i . $cdl["idcdl"]; ?>">
                            <?php foreach($templateParams["esami"] as $esame) : ?>
                                <?php if($esame["idcdl"] == $cdl["idcdl"] && $esame["annoesame"] == $i): ?>
                                    <a href="esame.php?idcdl=<?php echo $cdl["idcdl"]; ?>&idesame=<?php echo $esame["idesame"]; ?>"><div class="card-esami">
                                        <div class="esami-img">
                                            <img src="<?php echo UPLOAD_DIR . $esame["imgesame"]; ?>" alt=""/>   
                                        </div>                             
                                        <h4><?php echo $esame["nomeesame"]; ?></h4>
                                    </div></a> 
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </div>
                            <li><input type="button" id="close<?php echo $i . $cdl["idcdl"]; ?>" value="ʌ" onclick="closeForm('close<?php echo $i . $cdl['idcdl']; ?>', '<?php echo $i . $cdl['idcdl'] ;?>', 'open<?php echo $i . $cdl['idcdl']; ?>')"></li>
                            <li><input type="button" id="open<?php echo $i . $cdl["idcdl"]; ?>" value="v" onclick="openForm('close<?php echo $i . $cdl['idcdl']; ?>', '<?php echo $i . $cdl['idcdl']; ?>', 'open<?php echo $i . $cdl['idcdl']; ?>')"></li>
                        <?php endfor; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </section>
        <script src="js/script.js"></script>