import { splitReadings, resolveCitation, passageText } from "/lib/citations.js";

const PREFS_KEY = "bcp.prefs";

const defaults = {
  bibleVersion: "NRSV",
  prayerRite: false,
  prayerDayTime: true,
  prayerAll: false,
  lectionaryType: false,
  useBCPDO: false,
  fontSize: 14,
};

function loadPrefs() {
  try {
    return { ...defaults, ...JSON.parse(localStorage.getItem(PREFS_KEY) || "{}") };
  } catch {
    return { ...defaults };
  }
}

function savePrefs(prefs) {
  localStorage.setItem(PREFS_KEY, JSON.stringify(prefs));
}

function pad(value) {
  return String(value).padStart(2, "0");
}

function officeUrl(date, prefs) {
  const year = date.getFullYear();
  if (year < 2024 || year > 2032) return null;
  const morning = prefs.prayerAll ? !!prefs.prayerDayTime : date.getHours() < 12;
  return `/office/${year}/${pad(date.getMonth() + 1)}/${pad(date.getDate())}/#${morning ? "morning" : "evening"}`;
}

const chapterCache = new Map();

function loadChapter(version, bookId, chapter) {
  const key = `${version}/${bookId}/${chapter}`;
  if (!chapterCache.has(key)) {
    chapterCache.set(key, fetch(`/bible/${version}/${bookId}/${chapter}.json`).then((response) => {
      if (!response.ok) return null;
      return response.json().then((data) => data.verses);
    }).catch(() => null));
  }
  return chapterCache.get(key);
}

async function fillPassage(node, prefs) {
  const isPsalm = node.dataset.psalm === "1";
  const useBcp = isPsalm && !!prefs.useBCPDO;
  const spec = resolveCitation(node.dataset.ref || "", {
    isPsalm,
    version: prefs.bibleVersion || "KJV",
    useBcp,
  });
  if (!spec.marks || spec.marks.length === 0 || spec.error) return;
  const loaded = new Map();
  await Promise.all(spec.marks.map(async (mark) => {
    const key = `${spec.version}/${spec.bookId}/${mark.chapter}`;
    if (!loaded.has(key)) loaded.set(key, await loadChapter(spec.version, spec.bookId, mark.chapter));
  }));
  let missing = false;
  const html = passageText(spec, (version, bookId, chapter) => {
    const verses = loaded.get(`${version}/${bookId}/${chapter}`);
    if (!verses) missing = true;
    return verses;
  });
  if (!missing) {
    const body = node.querySelector(".passage-body");
    if (body) body.innerHTML = html;
  }
}

function applyPrefs(prefs) {
  const container = document.getElementById("maincontainer");
  if (container && prefs.fontSize) container.style.fontSize = `${prefs.fontSize}px`;
  const rite = prefs.prayerRite ? "i" : "ii";
  document.querySelectorAll(".collect").forEach((node) => {
    node.hidden = node.dataset.rite !== rite;
  });
  if (prefs.lectionaryType) {
    const older = document.querySelector('.lectionary[data-lectionary="1979"]');
    const parent = older && older.parentElement;
    if (older && parent) parent.insertBefore(older, parent.firstChild);
  }
  const passages = document.querySelectorAll(".passage");
  if (passages.length) {
    Promise.all([...passages].map((node) => fillPassage(node, prefs)));
  }
}

function wireChrome() {
  document.querySelectorAll(".navbar-toggle").forEach((button) => {
    button.addEventListener("click", () => {
      const menu = document.querySelector(".navbar-collapse");
      if (menu) menu.classList.toggle("in");
    });
  });
  document.querySelectorAll(".dropdown-toggle").forEach((link) => {
    link.addEventListener("click", (event) => {
      event.preventDefault();
      link.parentElement.classList.toggle("open");
    });
  });
}

function wireToday(prefs) {
  const path = location.pathname.replace(/\/$/, "");
  const status = document.getElementById("today-status");
  if (path === "/office/today") {
    const url = officeUrl(new Date(), prefs);
    if (url) location.replace(url);
    else if (status) status.textContent = "Today is outside the prepared years, 2024 through 2032.";
  }
  if (path === "/lectionary/today") {
    const now = new Date();
    const sunday = new Date(now.getFullYear(), now.getMonth(), now.getDate() - now.getDay());
    const year = sunday.getFullYear();
    if (year < 2024 || year > 2032) {
      if (status) status.textContent = "This Sunday is outside the prepared years, 2024 through 2032.";
      return;
    }
    location.replace(`/lectionary/${year}/${pad(sunday.getMonth() + 1)}/${pad(sunday.getDate())}/`);
  }
}

function wireDateForm(prefs) {
  const form = document.getElementById("date-form");
  if (!form) return;
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    const value = new FormData(form).get("date");
    if (!value) return;
    const [year, month, day] = String(value).split("-").map(Number);
    const chosen = new Date(year, month - 1, day, new Date().getHours(), 0, 0);
    const morning = prefs.prayerAll ? !!prefs.prayerDayTime : chosen.getHours() < 12;
    location.href = `/office/${year}/${pad(month)}/${pad(day)}/#${morning ? "morning" : "evening"}`;
  });
}

function wireSearch(prefs) {
  const form = document.getElementById("search-form");
  const result = document.getElementById("search-result");
  if (!form || !result) return;
  const input = document.getElementById("q");
  const params = new URLSearchParams(location.search);
  if (params.get("q") && input) input.value = params.get("q");
  const draw = async (query) => {
    const trimmed = query.trim();
    if (!trimmed) {
      result.innerHTML = "";
      return;
    }
    const pieces = splitReadings(trimmed).pieces;
    const html = [];
    for (const piece of pieces) {
      const spec = resolveCitation(piece.text, { version: prefs.bibleVersion, isPsalm: /psalm/i.test(piece.text) });
      const loaded = new Map();
      if (spec.marks) {
        await Promise.all(spec.marks.map(async (mark) => {
          const key = `${spec.version}/${spec.bookId}/${mark.chapter}`;
          if (!loaded.has(key)) loaded.set(key, await loadChapter(spec.version, spec.bookId, mark.chapter));
        }));
      }
      let missing = false;
      const body = passageText(spec, (version, bookId, chapter) => {
        const verses = loaded.get(`${version}/${bookId}/${chapter}`);
        if (!verses) missing = true;
        return verses;
      });
      html.push(`<h2>${piece.text.replace(/\*/g, "")}</h2>`);
      html.push(missing ? "<p><em>That passage is not in the selected translation on this server.</em></p>" : body);
      if (piece.join) html.push(`<p class="join"><strong><em>—${piece.join}—</em></strong></p>`);
    }
    result.innerHTML = html.join("");
  };
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    const query = input.value;
    history.replaceState(null, "", `/search/?q=${encodeURIComponent(query)}`);
    draw(query);
  });
  if (params.get("q")) draw(params.get("q"));
}

function wirePreferences(prefs) {
  const form = document.getElementById("prefs-form");
  if (!form) return;
  const set = (id, value) => {
    const field = document.getElementById(id);
    if (!field) return;
    if (field.type === "checkbox") field.checked = !!value;
    else field.value = String(value);
  };
  set("bibleVersion", prefs.bibleVersion);
  set("useBCPDO", prefs.useBCPDO);
  set("prayerRite", prefs.prayerRite);
  set("prayerDayTime", prefs.prayerDayTime);
  set("prayerAll", prefs.prayerAll);
  set("lectionaryType", prefs.lectionaryType);
  set("fontSize", prefs.fontSize);
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    const next = {
      ...prefs,
      bibleVersion: document.getElementById("bibleVersion").value,
      useBCPDO: document.getElementById("useBCPDO").value === "true",
      prayerRite: document.getElementById("prayerRite").value === "true",
      prayerDayTime: document.getElementById("prayerDayTime").value === "true",
      prayerAll: document.getElementById("prayerAll").checked,
      lectionaryType: document.getElementById("lectionaryType").value === "true",
      fontSize: Number(document.getElementById("fontSize").value) || 14,
    };
    savePrefs(next);
    form.insertAdjacentHTML("beforeend", "<p id=\"prefs-saved\">Saved in this browser.</p>");
  });
}

const prefs = loadPrefs();
wireChrome();
applyPrefs(prefs);
wireToday(prefs);
wireDateForm(prefs);
wireSearch(prefs);
wirePreferences(prefs);
