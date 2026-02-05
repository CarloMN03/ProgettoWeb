<section class="pagina">
    <header class="section-head">
        <h2>Study Group per l'esame di <?php echo $templateParams["esame"][0]["nomeesame"]; ?></h2>
    </header>

    <div class="gest-utente">
        <form action="#" method="POST" id="newsg" class="acc-form">
            <h3>Crea un nuovo Study Group</h3>
            
            <?php if(isset($templateParams["ritorno-creasg"])): ?>
                <div class="alert alert-success"><?php echo $templateParams["ritorno-creasg"]; ?></div>
            <?php endif; ?>

            <?php if(isset($templateParams["ritorno-creasg-errore"])): ?>
                <div class="alert alert-error"><?php echo $templateParams["ritorno-creasg-errore"]; ?></div>
            <?php endif; ?>

            <?php if(isset($templateParams["notifica-pref"])): ?>
                <div class="alert alert-success"><?php echo $templateParams["notifica-pref"]; ?></div>
            <?php endif; ?>

            <?php if(isset($templateParams["info-notifica"])): ?>
                <div class="alert alert-success"><?php echo $templateParams["info-notifica"]; ?></div>
            <?php endif; ?>
            
            <ul class="form-list">
                <li>
                    <label for="tema" class="acc-label">Tema: </label>
                    <input type="text" name="tema" id="tema" class="sb-input" required />
                </li>
                <li>
                    <label for="luogo" class="acc-label">Luogo: </label>
                    <select name="luogo" id="luogo" class="sb-input" onchange="addDesc('#newsg')" required>
                        <option value="">--Seleziona un luogo--</option>
                        <option value="Online">Online</option>
                        <option value="Fisico">Fisico</option>
                    </select>
                </li>
                
                <li id="container-icone-online" class="icon-container-small"></li>

                <li>
                    <label for="data" class="acc-label">Data: </label>
                    <input type="date" name="data" id="data" class="sb-input" required />
                </li>
                <li>
                    <label for="ora" class="acc-label">Ora: </label>
                    <input type="time" name="ora" id="ora" class="sb-input" required />
                </li>
                <li>
                    <label for="lingua" class="acc-label">Lingua: </label>
                    <select name="lingua" id="lingua" class="sb-input" required>
                        <option value=""></option>
                        <?php foreach($templateParams["lingua"] as $lingua): ?>
                            <option value="<?php echo $lingua["idlingua"]; ?>"><?php echo $lingua["descrizionelingua"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                <li class="admin-form-actions">
                    <input type="submit" name="submit" value="Crea Study Group" class="btn-primary" />
                </li>
            </ul>
        </form>
    </div>
</section>