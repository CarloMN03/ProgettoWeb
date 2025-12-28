<h2>Contatti</h2>
        <section class="contatti">
            <table>
                <tr>
                    <th id="amministratore">Amministratore</th><th id="email">Email</th>
                </tr>
                <?php foreach($templateParams["admin"] as $admin) : ?>
                <tr>
                    <th id="<?php echo $admin["username"]; ?>"><?php echo $admin["nome"] . " " . $admin["cognome"]; ?></th><td headers="email <?php echo $admin["username"]; ?>"><a href="mailto:<?php echo $admin["username"] ; ?>@studybo.it"><?php echo $admin["username"] ; ?>@studybo.it</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>