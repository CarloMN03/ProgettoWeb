<h2>Corsi di Laurea con Study Group attivi</h2>
        <section class="el-cdl">
            <?php foreach($templateParams["cdl"] as $cdl) : ?>
                <div id="card-cdl">
                    <div id="card-img"><img src="<?php echo $cdl["img"]; ?>" alt=""></div>
                    <div id="card-text">
                        <h3><?php echo $cdl["nomecdl"]; ?></h3>
                        <ul>
                            <li>Campus: <?php echo $cdl["sedecdl"]; ?></li>
                            <li>Esami con Study Group: <?php echo $templateParam["numstudygr"][$cdl["idcdl"]]; ?></li>
                        </ul>
                    </div>
                </div>
                <div id="study-gr-anno">
                    <h3>Esami con Study Group attivi</h3>
                    <ul>
                        <?php for($i = 1; $i <= $cdl["anni"]) : ?>
                            <li><?php echo $i; ?> anno</li>
                            <?php foreach($templateParams["esami"][$cdl][$i] as $esame) : ?>
                                <div class="el-esami" id="<?php echo $annocdl . $cdl["idcdl"]; ?>">
                                    <a href="esame.html?esame=<?php echo $esame["idesame"]; ?>"><div class="card-esami">
                                        <div class="esami-img">
                                            <img src="<?php echo $esame["img"]; ?>" alt=""/>
                                        </div>
                                        <h4><?php $esame["nomeesame"]; ?></h4>
                                    </div></a>
                                </div>
                            <?php endforeach; ?>
                            <li><input type="button" id="close<?php echo $i . $cdl["idcdl"]; ?>" value="ʌ" onclick="closeForm('close<?php echo $i . $cdl['idcdl']; ?>', '<?php echo $i . $cdl['idcdl'] ;?>', 'open<?php echo $i . $cdl['idcdl']; ?>')"></li>
                            <li><input type="button" id="open<?php echo $i . $cdl["idcdl"]; ?>" value="v" onclick="openForm('close<?php echo $i . $cdl['idcdl']; ?>', '<?php echo $i . $cdl['idcdl']; ?>', 'open<?php echo $i . $cdl['idcdl']; ?>')"></li>
                        <?php endfor; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </section>
        <script src="script.js"></script>