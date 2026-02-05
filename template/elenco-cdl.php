<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'header.php';
?>

<main class="pagina">
    <header class="section-head">
        <h2>Corsi di Laurea con Study Group attivi</h2>
        <p class="section-hint">Seleziona il tuo corso di laurea per visualizzare gli esami e i gruppi di studio disponibili.</p>
    </header>

    <section class="el-cdl">
        <?php foreach ($templateParams["cdl"] as $cdl) : ?>
            <article class="sezione">

                <div class="card-corso">
                    <div class="card-text">
                        <h3 class="card-title">
                            <?php echo $cdl["nomecdl"]; ?>
                        </h3>
                        <div class="card-dl">
                            <div><dt>Campus:</dt> <dd><?php echo $cdl["sede"]; ?></dd></div>
                            <div><dt>Esami con Study Group:</dt> <dd>
                                <?php
                                $e = 0;
                                foreach ($templateParams["numstudygr"] as $esamistgr) {
                                    if ($esamistgr["idcdl"] == $cdl["idcdl"]) $e++;
                                }
                                echo $e;
                                ?>
                            </dd></div>
                        </div>
                    </div>

                    <div class="anni-cdl">
                        <?php for ($i = 1; $i <= $cdl["durata"]; $i++) : ?>
                            <div class="anno-box">
                                <div class="anno-header"
                                     id="header-<?php echo $i . $cdl["idcdl"]; ?>"
                                     onclick="toggleAnno('<?php echo $i . $cdl['idcdl']; ?>')">
                                    <span>
                                        <?php
                                        $label = ["", "Primo", "Secondo", "Terzo", "Quarto", "Quinto"];
                                        echo $label[$i] ?? $i . "°";
                                        ?> Anno
                                    </span>

                                    <span class="anno-arrow">+</span>
                                </div>

                                <div class="el-esami" id="<?php echo $i . $cdl["idcdl"]; ?>" hidden>
                                    <?php foreach ($templateParams["esami"] as $esame) : ?>
                                        <?php if ($esame["idcdl"] == $cdl["idcdl"] && $esame["annoesame"] == $i): ?>

                                            <a href="esame.php?idcdl=<?php echo $cdl["idcdl"]; ?>&idesame=<?php echo $esame["idesame"]; ?>" class="card-esame">
                                                <h4><?php echo $esame["nomeesame"]; ?></h4>
                                                <p>📚 Study group attivi: <strong><?php echo $esame["sgattivi"]; ?></strong></p>
                                            </a>

                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<script>
function toggleAnno(id) {
    const content = document.getElementById(id);
    const header = document.getElementById('header-' + id);
    const arrow = header.querySelector('.anno-arrow');

    if (content.hasAttribute('hidden')) {
        content.removeAttribute('hidden');
        header.classList.add('aperto');
        arrow.innerText = '−'; // Cambia in meno (carattere −)
    } else {
        content.setAttribute('hidden', '');
        header.classList.remove('aperto');
        arrow.innerText = '+'; // Torna in più
    }
}
</script>
<script src="js/script.js"></script>
