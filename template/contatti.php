<section class="sezione" aria-labelledby="contatti-titolo">
    <div class="section-head">
        <h1 id="contatti-titolo">Contatti</h1>
        <p class="section-hint">In questa pagina trovi i riferimenti degli amministratori per ricevere assistenza tecnica.</p>
    </div>

    <article class="card-corso">
        <section class="contatti">
            <table class="ris-table">
                <thead>
                    <tr>
                        <th scope="col" id="amministratore">Amministratore</th>
                        <th scope="col" id="email">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["admin"] as $admin) : ?>
                    <tr>
                        <th scope="row" id="<?php echo e($admin["username"]); ?>" class="link-strong">
                            <?php echo e($admin["nome"]) . " " . e($admin["cognome"]); ?>
                        </th>
                        
                        <td headers="email <?php echo e($admin["username"]); ?>">
                            <a href="mailto:<?php echo e($admin["username"]); ?>@studybo.it">
                                <?php echo e($admin["username"]); ?>@studybo.it
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </article>
</section>