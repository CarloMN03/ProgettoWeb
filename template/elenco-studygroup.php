<section class="cerca-sg">
    <form action="" method="GET">
        <ul>
            <?php if(!empty($_SESSION["username"])): ?>
                <input type="hidden" id="idcdl" name="idcdl" value="<?php echo $templateParams["idcdl"]; ?>"/>
            <?php else: ?>
                <li>
                    <label for="idcdl">Corso di Laurea:</label>
                    <select name="idcdl" id="idcdl">
                        <?php foreach($templateParams["cdl"] as $cdl): ?>
                            <option value="<?php echo $cdl["idcdl"]; ?>"><?php echo $cdl["nomecdl"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
            <?php endif; ?>
            <li>
                <label for="idesame">Esame:</label>
                <select name="idesame" id="idesame">
                    <?php foreach($templateParams["esami"] as $esame): ?>
                        <option value="<?php echo $esame["idesame"]; ?>"><?php echo $esame["nomeesame"]; ?></option>
                    <?php endforeach; ?>
                </select>
            </li>
        </ul>
    </form>
</section>