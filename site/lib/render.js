// Build-time HTML for offices, lectionary Sundays, collects, and the psalter.
// Verse text is read from site/bible. The default pages embed KJV.
// Apocrypha (book id 67 and up) is read from RSV, which is the only
// translation that has those books in this database.

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
      version: "KJV",
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

const CONFESSION = `<p>Let us confess our sins against God and our neighbor.</p>
<p>Most merciful God,<br>
we confess that we have sinned against you<br>
in thought, word, and deed,<br>
by what we have done,<br>
and by what we have left undone.<br>
We have not loved you with our whole heart;<br>
we have not loved our neighbors as ourselves.<br>
We are truly sorry and we humbly repent.<br>
For the sake of your Son Jesus Christ,<br>
have mercy on us and forgive us;<br>
that we may delight in your will,<br>
and walk in your ways,<br>
to the glory of your Name. Amen.</p>
<p>Almighty God have mercy on you, forgive you all your sins
through our Lord Jesus Christ, strengthen you in all
goodness, and by the power of the Holy Spirit keep you in
eternal life. <em class="response">Amen.</em></p>`;

const CREED = `<p>I believe in God, the Father almighty,<br>
creator of heaven and earth.<br>
I believe in Jesus Christ, his only Son, our Lord.<br>
He was conceived by the power of the Holy Spirit<br>
and born of the Virgin Mary.<br>
He suffered under Pontius Pilate,<br>
was crucified, died, and was buried.<br>
He descended to the dead.<br>
On the third day he rose again.<br>
He ascended into heaven,<br>
and is seated at the right hand of the Father.<br>
He will come again to judge the living and the dead.<br>
I believe in the Holy Spirit,<br>
the holy catholic Church,<br>
the communion of saints,<br>
the forgiveness of sins,<br>
the resurrection of the body,<br>
and the life everlasting. Amen.</p>`;

const LORDS_PRAYER = `<p>Our Father in heaven,<br>
hallowed be your Name,<br>
your kingdom come,<br>
your will be done,<br>
on earth as in heaven.<br>
Give us today our daily bread.<br>
Forgive us our sins<br>
as we forgive those who sin against us.<br>
Save us from the time of trial,<br>
and deliver us from evil.<br>
For the kingdom, the power, and the glory are yours,<br>
now and for ever. Amen.</p>`;

function officeSection(id, title, opening, readings) {
  return `<section id="${id}">
<h2>${title}</h2>
${opening}
<h3>Confession of Sin</h3>
${CONFESSION}
<h3 id="${id}-psalms">The Psalms</h3>
${readings.psalms || "<p><em>No psalms.</em></p>"}
<h3 id="${id}-lessons">The Lessons</h3>
${readings.lessons || "<p><em>No lessons.</em></p>"}
<p>The canticles appointed after the lessons are on the <a href="/canticles/">Canticle</a> page. This page does not rotate them.</p>
<h3>The Apostles' Creed</h3>
${CREED}
<h3>The Lord's Prayer</h3>
${LORDS_PRAYER}
<h3>The Collect of the Day</h3>
<div class="collect" data-rite="ii">${readings.collectIi}</div>
<div class="collect" data-rite="i" hidden>${readings.collectI}</div>
</section>`;
}

export function renderOffice(day) {
  const row = findReadings(day.office.year, day.office.id, day.office.dow);
  const week = day.week && day.week.name ? day.week.name : "Daily Office";
  const color = day.week && day.week.color ? day.week.color : "";
  if (!row) {
    return `<h1>${escapeHtml(day.title)}</h1><p>No daily office readings are stored for ${escapeHtml(week)} (year ${escapeHtml(day.office.year)}, id ${escapeHtml(day.office.id)}).</p>`;
  }
  const psalms = splitPsalms(row.do_psalms);
  const ot = splitReadings(row.do_ot).pieces;
  const nt = splitReadings(row.do_nt).pieces;
  const gospel = splitReadings(row.do_gospel).pieces;
  const collectKey = collectIdFor(day);
  const collectIi = collectHtml(collects.rite_ii.get(collectKey));
  const collectI = collectHtml(collects.rite_i.get(collectKey));
  const section = (office) => ({
    psalms: piecesHtml(office === "morning" ? psalms.morning : psalms.evening, { isPsalm: true, kind: "psalm" }),
    lessons: [
      piecesHtml(visiblePieces(ot, office), { kind: "lesson" }),
      piecesHtml(visiblePieces(nt, office), { kind: "lesson" }),
      piecesHtml(visiblePieces(gospel, office), { kind: "gospel" }),
    ].join("\n"),
    collectIi,
    collectI,
  });
  return `<p class="office-note">Readings for this date, with the Rite II confession, creed, Lord's Prayer, and collect. A lesson marked for the morning or the evening is shown in that office. Rite I spoken text, the Spanish offices, antiphons, and canticle rotation are not on this page.</p>
<h1>${escapeHtml(day.title)}</h1>
<p class="week season-${escapeHtml(color)}"><strong>${escapeHtml(week)}</strong></p>
<p>Week of ${escapeHtml(day.sunday)}. Daily Office year ${escapeHtml(day.office.year)}. Lectionary year ${escapeHtml(day.lectionaryYear || "")}.</p>
<p><a href="#morning">Morning Prayer</a> &nbsp;|&nbsp; <a href="#evening">Evening Prayer</a></p>
${officeSection("morning", "Morning Prayer", "<p>Lord, open our lips.<br><em class=\"response\">And our mouth shall proclaim your praise.</em></p>", section("morning"))}
${officeSection("evening", "Evening Prayer", "<p>O God, make speed to save us.<br><em class=\"response\">O Lord, make haste to help us.</em></p>", section("evening"))}`;
}

function lectionaryBlock(title, row) {
  if (!row) return `<h2>${escapeHtml(title)}</h2><p><em>No readings for this Sunday in this lectionary.</em></p>`;
  const psalm = splitReadings(row.sl_psalm).pieces.map((piece) => ({ ...piece, intention: "both" }));
  const ot = splitReadings(row.sl_ot).pieces;
  const nt = splitReadings(row.sl_nt).pieces;
  const gospel = splitReadings(row.sl_gospel).pieces;
  return `<h2>${escapeHtml(title)}</h2>
<p><strong>${escapeHtml(row.sl_name || "")}</strong></p>
<h3>Psalm</h3>
${piecesHtml(psalm, { isPsalm: true, kind: "psalm" })}
<h3>Old Testament</h3>
${piecesHtml(ot, { kind: "lesson" })}
<h3>New Testament</h3>
${piecesHtml(nt, { kind: "lesson" })}
<h3>Gospel</h3>
${piecesHtml(gospel, { kind: "gospel" })}`;
}

export function renderLectionary(day) {
  const year = day.lectionaryYear;
  const id = day.lectionaryId;
  const week = day.week && day.week.name ? day.week.name : day.title;
  const rcl = findLectionary(lectionaries.rcl, year, id);
  const bcp = findLectionary(lectionaries.bcp, year, id);
  return `<h1>${escapeHtml(week)}</h1>
<p>${escapeHtml(day.title)}. Lectionary year ${escapeHtml(year || "")}.</p>
<div class="lectionary" data-lectionary="rcl">
${lectionaryBlock(`Revised Common Lectionary, Year ${year || ""}`, rcl)}
</div>
<div class="lectionary" data-lectionary="1979">
${lectionaryBlock(`1979 Lectionary, Year ${year || ""}`, bcp)}
</div>`;
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
  const traditional = which === "C" ? "" : "traditional";
  const head = which === "ES"
    ? `<p><a href="/collects/traditional/">Traditional</a> &nbsp;|&nbsp; <a href="/collects/contemporary/">Contemporary</a> &nbsp;|&nbsp; <a href="/collects/spanish/">Español</a></p>`
    : `<p><a href="/collects/traditional/">Traditional</a> &nbsp;|&nbsp; <a href="/collects/contemporary/">Contemporary</a> &nbsp;|&nbsp; <a href="/collects/spanish/">Español</a></p>`;
  let inHolyDays = false;
  const parts = [head, `<div id="top"><strong>Index</strong></div>`,
    `<p><a href="#season">The Season</a><br><a href="#holy_days">Holy Days</a><br><a href="#saints">The Common of Saints</a><br><a href="#various">Various Occasions</a></p>`,
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
      const spec = resolveCitation(String(chapter), { isPsalm: true, version: "KJV", useBcp: true });
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
