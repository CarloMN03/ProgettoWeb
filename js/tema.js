(function () {
  try {
    const raw = localStorage.getItem("sb_prefs");
    const p = raw ? JSON.parse(raw) : {};
    const root = document.documentElement;

    // Pulizia classi precedenti
    root.classList.remove("theme-dark", "theme-contrast", "theme-cvd", "no-images");

    // Applicazione del tema salvato
    if (p.theme === "dark") root.classList.add("theme-dark");
    if (p.theme === "contrast") root.classList.add("theme-contrast");
    if (p.theme === "cvd") root.classList.add("theme-cvd");

    // Applicazione scala font
    const fs = Number(p.fontScale || 1);
    root.style.setProperty("--font-scale", String(fs));

    // Blocco immagini
    if (p.noImages) root.classList.add("no-images");
  } catch (e) {
    console.error("Errore caricamento preferenze:", e);
  }
})();