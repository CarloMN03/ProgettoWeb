<section>
    <h2>Study Group per l'esame di <?php echo $templateParams["esame"][0]["nomeesame"]; ?></h2>
    <form action="#" method="POST" id="newsg">
        <h3>Crea un nuovo Study Group</h3>
        <?php if(isset($templateParams["notifica-pref"])): ?>
            <p><?php echo $templateParams["notifica-pref"]; ?></p>
        <?php endif; ?>
        <?php if(isset($templateParams["ritorno-creasg"])): ?>
            <p><?php echo $templateParams["ritorno-creasg"]; ?></p>
        <?php endif; ?>
        <?php if(isset($templateParams["ritorno-adesione"])): ?>
            <p><?php echo $templateParams["ritorno-adesione"]; ?></p>
        <?php endif; ?>
        <ul>
            <li>
                <label for="tema">Tema: </label><input type="text" name="tema" id="tema"/>
            </li>
            <li>
                <label for="luogo">Luogo: </label><select name="luogo" id="luogo" onchange="addDesc('#newsg')"><option value="">--Seleziona un luogo--</option><option value="Online">Online</option><option value="Fisico">In fisico</option></select>
            </li>
            <li>
            </li>
            <li>
                <label for="data">Data: </label><input type="date" name="data" id="data"/>
            </li>
            <li>
                <label for="ora">Ora: </label><input type="time" name="ora" id="ora"/>
            </li>
            <li>
                <label for="lingua">Lingua: </label><select name="lingua" id="lingua">
                    <option value=""></option>
                    <?php foreach($templateParams["lingua"] as $lingua): ?>
                        <option value="<?php echo $lingua["idlingua"]; ?>"><?php echo $lingua["descrizionelingua"]; ?></option>
                    <?php endforeach; ?>
                </select>
            </li>
            <li>
                <input type="submit" value="Invia"/>
            </li>
        </ul>
    </form>
</section>