<?php
  require_once 'bootstrap.php';
  $siteTitle = 'StudyBo - Accessibilità';

  if (!function_exists('e')) {
      function e(string $s): string {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
      }
  }
?>

<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="style.css" />
  <title><?= e($siteTitle) ?></title>

  <script>
  (function () {
    try {
      const raw = localStorage.getItem("sb_prefs");
      const p = raw ? JSON.parse(raw) : {};
      const root = document.documentElement;

      root.classList.remove(
        "theme-dark",
        "theme-contrast",
        "theme-cvd",
        "no-images",
        "reduce-motion"
      );

      // SOLO due temi: default e cvd
      const theme = (p.theme === "cvd") ? "cvd" : "default";
      if (theme === "cvd") root.classList.add("theme-cvd");

      const fs = Number(p.fontScale || 1);
      root.style.setProperty("--font-scale", String(fs));

      if (p.noImages) root.classList.add("no-images");
      if (p.reduceMotion) root.classList.add("reduce-motion");
    } catch (e) {}
  })();
  </script>
</head>

<body>

<a class="skip-link" href="#contenuto">Salta al contenuto</a>

<?php include 'header.php'; ?>

<main class="pagina" id="contenuto" tabindex="-1">
  <section class="sezione" aria-labelledby="acc-title">

    <div class="section-head">
      <h1 id="acc-title">Impostazioni di accessibilità</h1>
      <p class="section-hint">
        Le impostazioni vengono salvate sul dispositivo e applicate a tutto il sito.
      </p>
    </div>

    <noscript>
      <p class="alert alert-warning">
        Per salvare e applicare queste impostazioni è necessario JavaScript.
      </p>
    </noscript>

    <form class="acc-form" id="acc-form">

      <!-- TEMA: solo default + blu (cvd) -->
      <fieldset class="acc-card" aria-describedby="themeHelp">
        <legend class="acc-legend">Tema</legend>

        <p class="acc-help" id="themeHelp">
          Puoi scegliere tra il tema standard (rosso) e il tema blu.
        </p>

        <div class="acc-row">
          <input id="theme-default" type="radio" name="theme" value="default" checked />
          <label class="acc-choice" for="theme-default">Tema rosso (standard)</label>
        </div>

        <div class="acc-row">
          <input id="theme-cvd" type="radio" name="theme" value="cvd" />
          <label class="acc-choice" for="theme-cvd">Tema blu</label>
        </div>
      </fieldset>

      <fieldset class="acc-card">
        <legend class="acc-legend">Testo</legend>

        <label class="acc-label" for="fontScale">Grandezza caratteri</label>
        <div class="acc-inline">
          <input
            id="fontScale"
            name="fontScale"
            type="range"
            min="1"
            max="1.6"
            step="0.05"
            value="1"
            aria-describedby="fontHelp fontOut"
          />
          <output id="fontOut" aria-live="polite">100%</output>
        </div>

        <p class="acc-help" id="fontHelp">
          Consiglio: 110–130% se vuoi leggere più comodamente.
        </p>
      </fieldset>

      <fieldset class="acc-card">
        <legend class="acc-legend">Risparmio dati</legend>

        <div class="acc-row">
          <input type="checkbox" name="noImages" id="noImages" />
          <label class="acc-choice" for="noImages">Non mostrare immagini (risparmia dati)</label>
        </div>
      </fieldset>

      <div class="acc-actions">
        <button class="btn-primary" type="submit">Salva</button>
        <button class="btn-secondary" type="button" id="reset">Ripristina</button>
        <a class="btn-secondary" href="home.php">Torna alla home</a>

        <p
          class="acc-saved"
          id="saved"
          role="status"
          aria-live="polite"
          hidden
        >
          Impostazioni salvate ✅
        </p>
      </div>

    </form>
  </section>
</main>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("acc-form");
  const resetBtn = document.getElementById("reset");
  const fontScale = document.getElementById("fontScale");
  const fontOut = document.getElementById("fontOut");
  const saved = document.getElementById("saved");

  const readPrefs = () => {
    try {
      const raw = localStorage.getItem("sb_prefs");
      return raw ? JSON.parse(raw) : {};
    } catch {
      return {};
    }
  };

  const writePrefs = (p) =>
    localStorage.setItem("sb_prefs", JSON.stringify(p));

  const applyPrefs = (p) => {
    const root = document.documentElement;

    root.classList.remove(
      "theme-dark",
      "theme-contrast",
      "theme-cvd",
      "no-images",
      "reduce-motion"
    );

    // SOLO due temi: default e cvd
    const theme = (p.theme === "cvd") ? "cvd" : "default";
    if (theme === "cvd") root.classList.add("theme-cvd");

    const fs = Number(p.fontScale || 1);
    root.style.setProperty("--font-scale", String(fs));

    if (p.noImages) root.classList.add("no-images");
  };

  const syncUI = (p) => {
    const theme = (p.theme === "cvd") ? "cvd" : "default";
    const themeInput = form.querySelector(
      `input[name="theme"][value="${theme}"]`
    );
    if (themeInput) themeInput.checked = true;

    const fs = Number(p.fontScale || 1);
    fontScale.value = String(fs);
    fontOut.textContent = `${Math.round(fs * 100)}%`;

    document.getElementById("noImages").checked = !!p.noImages;
  };

  const showSaved = () => {
    saved.hidden = false;
    setTimeout(() => {
      saved.hidden = true;
    }, 1600);
  };

  const prefs = readPrefs();
  applyPrefs(prefs);
  syncUI(prefs);

  fontScale.addEventListener("input", () => {
    const fs = Number(fontScale.value);
    fontOut.textContent = `${Math.round(fs * 100)}%`;
    const p = readPrefs();
    p.fontScale = fs;
    writePrefs(p);
    applyPrefs(p);
  });

  form.addEventListener("change", () => {
    const p = readPrefs();

    const chosen = form.querySelector('input[name="theme"]:checked')?.value || "default";
    p.theme = (chosen === "cvd") ? "cvd" : "default";

    p.noImages = document.getElementById("noImages").checked;
    p.fontScale = Number(fontScale.value);
    writePrefs(p);
    applyPrefs(p);
  });

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    showSaved();
  });

  resetBtn.addEventListener("click", () => {
    const p = { theme: "default", fontScale: 1, noImages: false };
    writePrefs(p);
    applyPrefs(p);
    syncUI(p);
    showSaved();
  });
});
</script>

</body>
</html>
