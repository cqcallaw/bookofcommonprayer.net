// Build-time readings for offices, lectionary Sundays, collects, and the psalter.
// Spoken office text is site/_includes/office.njk. Which translation is
// embedded is site/_data/policy.json. Verse text is read from site/bible.

import { readFileSync, existsSync } from "node:fs";
import { fileURLToPath } from "node:url";
import {
  splitReadings,
  splitPsalms,
  visiblePieces,
  resolveCitation,
  passageText,
  escapeHtml,
  countVerses,
} from "./citations.js";

const dataDir = fileURLToPath(new URL("../_data/", import.meta.url));
const bibleDir = fileURLToPath(new URL("../bible/", import.meta.url));

function loadJson(name) {
  return JSON.parse(readFileSync(dataDir + name, "utf8"));
}

const policy = loadJson("policy.json");
const books = new Map(loadJson("books.json").map((book) => [book.id, book.name]));
const dailyOffice = loadJson("daily_office.json");
const collects = {
  rite_i: indexBy(loadJson("collects.json"), "id"),
  rite_ii: indexBy(loadJson("collects_contemporary.json"), "id"),
  es: indexBy(loadJson("collects_es.json"), "id"),
};
const lectionaries = {
  bcp: loadJson("sunday_lectionary.json"),
  rcl: loadJson("rcl_sunday_lectionary.json"),
};
const canticleById = new Map(loadJson("canticles.json").map((row) => [String(row.id), row]));

// Same weekday table as BCP\CanticlesByPrayerType for Rite II.
// first/second are the canticles after the first and second lessons.
const riteIiCanticles = {
  lent: {
    morning: { first: ["14", "9", "13", "14", "8", "14", "12"], second: ["16", "19", "18", "16", "19", "18", "19"] },
    evening: { first: ["3", "14", "10", "12", "11", "13", "9"], second: ["5", "5", "3", "5", "3", "5", "3"] },
  },
  advent: {
    morning: { first: ["11", "9", "13", "11", "8", "10", "12"], second: ["21", "19", "18", "16", "20", "18", "19"] },
    evening: { first: ["3", "8", "10", "12", "11", "13", "9"], second: ["5", "5", "3", "5", "3", "5", "3"] },
  },
  easter: {
    morning: { first: ["8", "9", "13", "11", "8", "10", "12"], second: ["21", "19", "18", "16", "20", "18", "19"] },
    evening: { first: ["3", "8", "10", "12", "11", "13", "9"], second: ["5", "5", "3", "5", "3", "5", "3"] },
  },
  ordinary: {
    morning: { first: ["16", "9", "13", "11", "8", "10", "12"], second: ["21", "19", "18", "16", "20", "18", "19"] },
    evening: { first: ["3", "8", "10", "12", "11", "13", "9"], second: ["5", "5", "3", "5", "3", "5", "3"] },
  },
};

const chapterCache = new Map();

function indexBy(rows, key) {
  const map = new Map();
  for (const row of rows) map.set(String(row[key]), row);
  return map;
}

export function getVerses(version, bookId, chapter) {
  const key = `${version}/${bookId}/${chapter}`;
  if (chapterCache.has(key)) return chapterCache.get(key);
  const path = `${bibleDir}${version}/${bookId}/${chapter}.json`;
  if (!existsSync(path)) {
    chapterCache.set(key, null);
    return null;
  }
  const verses = JSON.parse(readFileSync(path, "utf8")).verses;
  chapterCache.set(key, verses);
  return verses;
}

function bookName(bookId) {
  return books.get(bookId) || "";
}

export function formatLiturgical(text) {
  return String(text || "")
    .replaceAll("*or this*", "<p><em> - or this - </em></p>")
    .replaceAll("=Amen.=", "<em>Amen.</em>");
}

function findReadings(year, id, dow) {
  const matches = dailyOffice.filter((row) => row.do_yearNo === String(year) && row.do_id === String(id));
  if (dow === null || dow === undefined || dow === "" || dow === "null") {
    return matches[0] || null;
  }
  return matches.find((row) => String(row.do_dow) === String(dow)) || null;
}

function findLectionary(table, year, id) {
  return table.find((row) => row.sl_year_no === year && row.sl_id === String(id)) || null;
}

// CollectOfTheDay, including the Christmas-week rule. The PHP calls date("Y")
// for that weekday, which would freeze every year to the export year.
// This uses the civil year of the office date.
export function collectIdFor(day) {
  let id = String(day.office.id);
  const dow = day.office.dow === null || day.office.dow === undefined ? null : Number(day.office.dow);
  if ((id === "R" || id === "PS") && dow > 0) id = `${id}-${dow}`;
  else if (id === "LE" && dow >= 3) id = "AW";
  else if (id === "STST") id = "12-26";
  else if (id === "STJE") id = "12-27";
  else if (id === "HI") id = "12-28";
  else if (id === "1-2" || id === "1-3" || id === "1-4" || id === "1-5") {
    const christmas = new Date(Date.UTC(Number(day.y), 11, 25));
    id = christmas.getUTCDay() === 6 ? "2C" : "1C";
  } else if (["1-7", "1-8", "1-9", "1-10", "1-11", "1-12"].includes(id)) id = "E";
  return id;
}

function collectHtml(row) {
  if (!row) return "<p><em>No collect is stored for this day.</em></p>";
  return `<p><strong>${escapeHtml(row.name)}</strong><br>${formatLiturgical(row.text)}</p>`;
}

function piecesHtml(pieces, options) {
  const visible = options.office ? visiblePieces(pieces, options.office) : pieces;
  if (visible.length === 0) return "";
  const blocks = [];
  visible.forEach((piece, index) => {
    const cleaned = piece.text.replace(/\*/g, "").trim();
    if (!cleaned || cleaned === "None") {
      blocks.push("<p><em>No reading.</em></p>");
      return;
    }
    const spec = resolveCitation(piece.text, {
      isPsalm: options.isPsalm,
      version: policy.embedded,
      apocryphaVersion: policy.apocrypha,
      apocryphaFromBook: policy.apocryphaFromBook,
      useBcp: false,
    });
    if (spec.bookId) spec.display = spec.isPsalm && !/^psalm/i.test(spec.display) ? `Psalm ${spec.display}` : spec.display;
    const body = passageText(spec, getVerses);
    let heading;
    if (options.kind === "gospel" && spec.bookId) {
      heading = `A Reading from the Gospel according to St. ${escapeHtml(bookName(spec.bookId))} (${escapeHtml(spec.display)})`;
    } else if (options.isPsalm) {
      heading = escapeHtml(spec.display || cleaned);
    } else {
      heading = `A Reading (Lesson) from: ${escapeHtml(spec.display || cleaned)}`;
    }
    blocks.push(
      `<div class="passage" data-ref="${escapeHtml(piece.text.trim())}" data-psalm="${options.isPsalm ? "1" : "0"}" data-kind="${options.kind || "lesson"}">` +
        `<p><em>Reader:</em> ${heading}</p>` +
        `<div class="passage-body">${body}</div>` +
      `</div>`
    );
    if (piece.join && index < visible.length - 1) {
      blocks.push(`<p class="join"><strong><em>—${piece.join}—</em></strong></p>`);
    }
  });
  return blocks.join("\n");
}

function lessonPieces(reference, office, kind) {
  const pieces = splitReadings(reference).pieces;
  return piecesHtml(office ? visiblePieces(pieces, office) : pieces, { kind });
}

function canticleHtml(id) {
  const row = canticleById.get(String(id));
  if (!row) return "";
  const ref = row.ref ? `<br><em>${escapeHtml(row.ref)}</em>` : "";
  const instr = row.instr ? `<br><em>${escapeHtml(row.instr)}</em>` : "";
  return `<p><strong>${escapeHtml(row.title)}</strong> <em>${escapeHtml(row.latin || "")}</em>${ref}${instr}</p><pre>${escapeHtml(row.cont || "")}</pre>`;
}

function canticleBucket(day) {
  const id = String(day.office.id || "");
  const season = day.season || "";
  if (season === "Lent" || season === "Holy_Week" || /^(LE|PS|[1-5]L)$/.test(id)) return "lent";
  if (/^[1-4]A$/.test(id)) return "advent";
  if (season === "Easter" || /^(R|[2-7]R)$/.test(id)) return "easter";
  return "ordinary";
}

// Same groups as api/daily_office_comfortable_words.php for Rite II morning.
// Evening uses one set on every day. Christmas week (1C, 2C) and Epiphany
// Day (E) are blank in that script; they take the seasonal sentences here.
function openingKey(day) {
  let id = String(day.office.id || "");
  const dow = Number(weekdayNumber(day));
  if (id === "LE" && dow >= 3) id = "1L";
  if (["STST", "STJE", "HI", "12-29", "12-30", "12-31", "1-2", "1-3", "1-4", "1-5", "1-7", "1-8", "1-9", "1-10", "1-11", "1-12", "HN"].includes(id)) return "ordinary";
  if (/^[1-4]A$/.test(id)) return "advent";
  if (id === "CM" || id === "CMII" || id === "CMIII" || id === "1C" || id === "2C") return "christmas";
  if (/^[1-8]E$/.test(id) || id === "E" || id === "LE") return "epiphany";
  if (/^[1-5]L$/.test(id)) return "lent";
  if (id === "PS") return "holyweek";
  if (id === "R" || /^[2-7]R$/.test(id) || id === "P" || id === "AD") return "easter";
  if (id === "T") return "trinity";
  return "ordinary";
}

function weekdayNumber(day) {
  const raw = day.office && day.office.dow;
  if (raw !== null && raw !== undefined && raw !== "" && raw !== "null") return String(raw);
  if (day.dow !== null && day.dow !== undefined && day.dow !== "" && day.dow !== "null") return String(day.dow);
  const [year, month, date] = String(day.date || "").split("-").map(Number);
  if (!year || !month || !date) return "0";
  return String(new Date(Date.UTC(year, month - 1, date)).getUTCDay());
}

function sectionReadings(row, office, appointed) {
  const psalms = splitPsalms(row.do_psalms);
  const side = office === "morning" ? psalms.morning : psalms.evening;
  const other = office === "morning" ? psalms.evening : psalms.morning;
  return {
    id: office,
    title: office === "morning" ? "Morning Prayer" : "Evening Prayer",
    psalms: piecesHtml(side, { isPsalm: true, kind: "psalm" }),
    otherPsalms: piecesHtml(other, { isPsalm: true, kind: "psalm" }),
    ot: lessonPieces(row.do_ot, office, "lesson"),
    nt: lessonPieces(row.do_nt, office, "lesson"),
    gospel: lessonPieces(row.do_gospel, office, "gospel"),
    canticleFirst: appointed.first,
    canticleSecond: appointed.second,
  };
}

function readingsBlocks(row) {
  const psalms = splitPsalms(row.do_psalms);
  return {
    morningPsalms: piecesHtml(psalms.morning, { isPsalm: true, kind: "psalm" }),
    eveningPsalms: piecesHtml(psalms.evening, { isPsalm: true, kind: "psalm" }),
    ot: lessonPieces(row.do_ot, null, "lesson"),
    nt: lessonPieces(row.do_nt, null, "lesson"),
    gospel: lessonPieces(row.do_gospel, null, "gospel"),
  };
}

export function officeView(day) {
  const week = day.week && day.week.name ? day.week.name : "Daily Office";
  const color = day.week && day.week.color ? day.week.color : "";
  const row = findReadings(day.office.year, day.office.id, day.office.dow);
  if (!row) {
    return {
      missing: true,
      title: day.title,
      week,
      year: day.office.year,
      id: day.office.id,
    };
  }
  const collectKey = collectIdFor(day);
  const collectIi = collectHtml(collects.rite_ii.get(collectKey));
  const collectI = collectHtml(collects.rite_i.get(collectKey));
  const bucket = canticleBucket(day);
  const dow = weekdayNumber(day);
  const table = riteIiCanticles[bucket];
  const dayIndex = Number(dow);
  const sections = ["morning", "evening"].map((office) => ({
    ...sectionReadings(row, office, {
      first: canticleHtml(table[office].first[dayIndex]),
      second: canticleHtml(table[office].second[dayIndex]),
    }),
    collectIi,
    collectI,
    dow,
    opening: office === "evening" ? "evening" : openingKey(day),
    proper: /^[0-9]{1,2}$/.test(String(day.office.id || "")),
    lent: bucket === "lent",
    easter: bucket === "easter",
  }));
  return {
    missing: false,
    title: day.title,
    week,
    color,
    sunday: day.sunday,
    year: day.office.year,
    lectionaryYear: day.lectionaryYear || "",
    path: `/office/${day.y}/${day.m}/${day.d}/`,
    readings: readingsBlocks(row),
    sections,
  };
}

function lectionaryBlock(row) {
  if (!row) return { empty: true };
  const psalm = splitReadings(row.sl_psalm).pieces.map((piece) => ({ ...piece, intention: "both" }));
  return {
    empty: false,
    name: row.sl_name || "",
    psalm: piecesHtml(psalm, { isPsalm: true, kind: "psalm" }),
    ot: piecesHtml(splitReadings(row.sl_ot).pieces, { kind: "lesson" }),
    nt: piecesHtml(splitReadings(row.sl_nt).pieces, { kind: "lesson" }),
    gospel: piecesHtml(splitReadings(row.sl_gospel).pieces, { kind: "gospel" }),
  };
}

export function lectionaryView(day) {
  const year = day.lectionaryYear || "";
  const id = day.lectionaryId;
  return {
    week: day.week && day.week.name ? day.week.name : day.title,
    title: day.title,
    year,
    blocks: [
      { id: "rcl", heading: `Revised Common Lectionary, Year ${year}`, ...lectionaryBlock(findLectionary(lectionaries.rcl, year, id)) },
      { id: "1979", heading: `1979 Lectionary, Year ${year}`, ...lectionaryBlock(findLectionary(lectionaries.bcp, year, id)) },
    ],
  };
}

const COLLECT_ORDER = ["1A","2A","3A","4A","CM","1C","HN","2C","E","1E","2E","3E","4E",
  "5E","6E","7E","8E","LE","AW","1L","2L","3L","4L","5L","PS","PS-1","PS-2","PS-3",
  "PS-4","PS-5","PS-6","R","R-1","R-2","R-3","R-4","R-5","R-6","2R","3R","4R","5R",
  "6R","AD","7R","P","T","CC","1","2","3","4","5","6","7","8","9","10","11","12",
  "13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28",
  "29","11-30","12-21","12-26","12-27","12-28","1-18","1-25","2-2","2-24","3-19",
  "3-25","4-25","5-1","5-31","6-11","6-24","6-29","7-4","7-22","7-25","8-6","8-15",
  "8-24","9-14","9-21","9-29","10-18","10-23","10-28","THK","11-1","SL","mart",
  "miss","past","tchr","monk","snt","vo1","vo2","vo3","vo4","vo5","vo6","vo7","vo8",
  "vo9","vo10","vo11","vo12","vo13","vo14","vo15-1","vo15-2","vo15-3","vo16","vo17",
  "vo18","vo19-1","vo19-2","vo19-3","vo20","vo21","vo22","vo23","vo24","vo25"];

export function renderCollects(which) {
  const table = which === "C" ? collects.rite_ii : which === "ES" ? collects.es : collects.rite_i;
  const slug = which === "C" ? "contemporary" : which === "ES" ? "spanish" : "traditional";
  const here = `/collects/${slug}/`;
  const head = `<p><a href="/collects/traditional/">Traditional</a> &nbsp;|&nbsp; <a href="/collects/contemporary/">Contemporary</a> &nbsp;|&nbsp; <a href="/collects/spanish/">Español</a></p>`;
  let inHolyDays = false;
  const parts = [head, `<div id="top"><strong>Index</strong></div>`,
    `<p><a href="${here}#season">The Season</a><br><a href="${here}#holy_days">Holy Days</a><br><a href="${here}#saints">The Common of Saints</a><br><a href="${here}#various">Various Occasions</a></p>`,
    `<div id="season"><h2>${which === "C" ? "Contemporary Collects" : which === "ES" ? "Colectas" : "Traditional Collects"}</h2></div>`];
  const ids = which === "ES" ? [...table.keys()] : COLLECT_ORDER;
  for (const id of ids) {
    const row = table.get(id);
    if (!row) continue;
    if (id === "11-30") {
      inHolyDays = true;
      parts.push(`<div id="holy_days"><h2>Holy Days</h2></div>`);
    }
    if (id === "mart") parts.push(`<div id="saints"><h2>The Common of Saints</h2></div>`);
    if (id === "vo1") parts.push(`<div id="various"><h2>Various Occasions</h2></div>`);
    const showId = inHolyDays && id !== "SL" && id !== "THK";
    const label = showId ? `${escapeHtml(row.name)} (${escapeHtml(id)})` : escapeHtml(row.name);
    parts.push(`<p><strong>${label}</strong><br>${formatLiturgical(row.text)}${row.preface ? `<br><em>${formatLiturgical(row.preface)}</em>` : ""}</p>`);
  }
  return parts.join("\n");
}

export function renderCanticles(which) {
  const rows = loadJson(which === "ES" ? "canticles_es.json" : "canticles.json");
  const parts = [`<p><a href="/canticles/">English</a> &nbsp;|&nbsp; <a href="/canticles/spanish/">Español</a></p>`, `<h1>${which === "ES" ? "Cánticos" : "Canticles"}</h1>`];
  for (const row of rows) {
    parts.push(`<div id="${escapeHtml(row.id)}"><h2>${escapeHtml(row.id)} ${escapeHtml(row.title || "")}</h2>
${row.latin ? `<p><em>${row.latin}</em></p>` : ""}
${row.ref ? `<p>${row.ref}</p>` : ""}
${row.instr ? `<p><em>${row.instr}</em></p>` : ""}
${row.cont || ""}
</div>`);
  }
  return parts.join("\n");
}

export function renderPrefaces(which) {
  const name = which === "C" ? "prefaces_contemporary.json" : which === "ES" ? "prefaces_es.json" : which === "CES" ? "prefaces_contemporary_es.json" : "prefaces.json";
  const rows = loadJson(name);
  const links = `<p><a href="/prefaces/traditional/">Traditional</a> &nbsp;|&nbsp; <a href="/prefaces/contemporary/">Contemporary</a> &nbsp;|&nbsp; <a href="/prefaces/spanish/">Español</a></p>`;
  const parts = [links, `<h1>Prefaces</h1>`];
  for (const row of rows) {
    parts.push(`<div id="${escapeHtml(row.pref_id || "")}"><h2>${escapeHtml(row.pref_name || "")}</h2>
${row.pref_instr ? `<p><em>${row.pref_instr}</em></p>` : ""}
${row.pref_text || ""}
</div>`);
  }
  return parts.join("\n");
}

export function renderPsalter(index) {
  const parts = [`<h1>The Daily Psalms</h1>`, `<p>The thirty-day psalter from the Book of Common Prayer. Morning is marked A in the tables, evening E.</p>`];
  for (const day of index) {
    const block = (chapters) => chapters.map((chapter) => {
      const spec = resolveCitation(String(chapter), {
        isPsalm: true,
        version: policy.embedded,
        useBcp: true,
        apocryphaVersion: policy.apocrypha,
        apocryphaFromBook: policy.apocryphaFromBook,
      });
      return `<h3>Psalm ${chapter}</h3><div class="passage" data-ref="${chapter}" data-psalm="1" data-kind="psalm"><div class="passage-body">${passageText(spec, getVerses)}</div></div>`;
    }).join("\n");
    parts.push(`<h2 id="day-${day.day}">Day ${day.day}</h2>`);
    parts.push(`<h3 id="day-${day.day}-morning">Morning</h3>${block(day.morning)}`);
    parts.push(`<h3 id="day-${day.day}-evening">Evening</h3>${block(day.evening)}`);
  }
  return parts.join("\n");
}

export function renderHolyDays() {
  const rows = loadJson("holy_days_daily_office.json");
  const parts = [`<h1>Holy Day Readings</h1>`, `<p>Citations from the daily office holy days. Open a citation to read it.</p>`, `<ul>`];
  for (const row of rows) {
    const bits = [row.hd_do_psalm, row.hd_do_first, row.hd_do_second].filter(Boolean);
    const links = bits.map((bit) => `<a href="/search/?q=${encodeURIComponent(String(bit).trim())}">${escapeHtml(String(bit).trim())}</a>`).join("; ");
    parts.push(`<li><strong>${escapeHtml(row.hd_do_name || row.hd_do_id || "")}</strong> ${links}</li>`);
  }
  parts.push(`</ul>`);
  const lect = loadJson("holy_days_lectionary.json");
  parts.push(`<h2>Holy Days in the 1979 Lectionary</h2><ul>`);
  for (const row of lect) {
    parts.push(`<li><strong>${escapeHtml(row.hd_name || "")}</strong> (${escapeHtml(row.hd_id || "")})</li>`);
  }
  parts.push(`</ul>`);
  return parts.join("\n");
}

export { countVerses, resolveCitation, splitReadings };
