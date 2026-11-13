// Citation splitting and verse selection.
// splitReadings follows DailyOfficeReadings::getReadingsArray:
// "or" (including a jammed "orGenesis") is split before "&&", and a
// bookless piece such as "8:6-18" keeps the book from the piece before it.
// Verse ranges follow Parser: a hyphen, up to three comma-separated
// pieces, a chapter span, and verses in parentheses or brackets.

import { matchBook } from "./books.js";

export function intentionOf(reading) {
  const text = String(reading);
  if (text.includes("***")) return "evening";
  if (text.includes("**")) return "morning";
  return "both";
}

export function splitReadings(reference) {
  const ref = String(reference ?? "").trim();
  const alternatives = ref.split(/\s+[Oo][Rr](?:\s+|(?=[A-Z]))|(?<=\S)[Oo][Rr](?=[A-Z])/);
  const readings = [];
  const joins = [];
  alternatives.forEach((alternative, altIndex) => {
    const parts = alternative.split("&&");
    let book = null;
    const kept = [];
    for (let part of parts) {
      part = part.trim();
      if (part === "") continue;
      const bare = part.replace(/^[ \t\[(]+/, "");
      if (/^\d+:/.test(bare)) {
        if (book) part = `${book} ${bare}`;
      } else {
        const numbered = bare.match(/^(\d\s*[A-Za-z]+(?:\s+[A-Za-z]+)*)/);
        const words = bare.match(/^([A-Za-z]+(?:\s+[A-Za-z]+)*)/);
        const match = numbered || words;
        if (match) book = match[1];
      }
      kept.push(part);
    }
    kept.forEach((part, partIndex) => {
      readings.push(part);
      if (partIndex < kept.length - 1) joins.push("AND");
      else if (altIndex < alternatives.length - 1) joins.push("OR");
      else joins.push(null);
    });
  });
  if (readings.length === 0) {
    readings.push(ref);
    joins.push(null);
  }
  const used = joins.filter(Boolean);
  let type = "NORMAL";
  if (readings.length > 1) {
    const unique = new Set(used);
    type = unique.size === 1 ? used[0] : "MIXED";
  }
  return {
    type,
    pieces: readings.map((text, i) => ({
      text,
      join: joins[i],
      intention: intentionOf(text),
    })),
  };
}

// A comma piece that is only the word "or" is the alternative between
// psalm numbers (the daily office stores "113,114,or,118").
export function splitPsalms(reference) {
  const split = String(reference || "").split(":o:");
  const take = (side) => {
    const out = [];
    for (const piece of String(side || "").split(",")) {
      const text = piece.trim();
      if (!text) continue;
      if (/^or$/i.test(text) && out.length) {
        out[out.length - 1].join = "OR";
      } else {
        out.push({ text, join: null, intention: "both" });
      }
    }
    return out;
  };
  return { morning: take(split[0]), evening: take(split[1]) };
}

export function visiblePieces(pieces, office) {
  const tagged = pieces.map((piece, index) => ({ ...piece, index }));
  const kept = tagged.filter((piece) => {
    if (office === "morning") return piece.intention !== "evening";
    if (office === "evening") return piece.intention !== "morning";
    return true;
  });
  return kept.map((piece, idx) => {
    const next = kept[idx + 1];
    let join = null;
    if (next && next.index === piece.index + 1) join = piece.join;
    else if (next && piece.join === "OR") join = "OR";
    return { ...piece, join };
  });
}

function cleanVerse(value) {
  return String(value).replace(/[abc]/g, "").trim();
}

function range(chapter, raw, extra) {
  const [startRaw, endRaw] = cleanVerse(raw).split("-");
  const start = Number(startRaw);
  const end = endRaw ? Number(endRaw) : start;
  return { chapter: Number(chapter), start, end, ...extra };
}

function parseStandard(rest, isPsalm) {
  const split = String(rest).split(":");
  const chapter = Number(String(split[0]).replace(/[^\d]/g, ""));
  const cleaned = split.length > 1 ? cleanVerse(split[1]) : "";
  if (!cleaned && isPsalm) return [range(chapter, "0-177")];
  if (!cleaned) return [range(chapter, "1-177")];
  return [range(chapter, cleaned)];
}

function parseComma(rest) {
  const split = String(rest).split(":");
  const chapter = Number(String(split[0]).replace(/[^\d]/g, ""));
  const pieces = cleanVerse(split[1] || "").split(",", 3);
  return pieces.filter((piece) => piece !== "").map((piece) => range(chapter, piece));
}

function parseMarked(rest, open, close) {
  const split = String(rest).split(":");
  const chapter = Number(String(split[0]).replace(/[^\d]/g, ""));
  const cleaned = cleanVerse(split[1] || "");
  const pattern = open === "[" ? /\[(.*?)\]/ : /\(([^)]+)\)/;
  const found = cleaned.match(pattern);
  const inside = found ? found[1] : "";
  const before = cleaned.split(open)[0];
  const spans = [];
  if (before && /\d/.test(before)) spans.push(range(chapter, before));
  if (inside) spans.push(range(chapter, inside, { open, close }));
  const afterAt = cleaned.indexOf(close);
  if (afterAt >= 0) {
    const after = cleaned.slice(afterAt + 1);
    if (/\d/.test(after)) spans.push(range(chapter, after));
  }
  return spans;
}

function parseRest(rest, isPsalm) {
  const text = String(rest || "").trim();
  const simple = text.match(/^(\d+):(\d+)\s*-\s*(\d+):(\d+)\s*$/);
  if (simple) {
    return [
      range(simple[1], `${simple[2]}-177`),
      range(simple[3], `1-${simple[4]}`),
    ];
  }
  const parenSpan = text.match(/^(\d+):(\d+)-(\d+)\((\d+)-(\d+):(\d+)\)\s*$/);
  if (parenSpan) {
    return [
      range(parenSpan[1], `${parenSpan[2]}-${parenSpan[3]}`),
      range(parenSpan[1], `${parenSpan[4]}-177`, { open: "(", close: "" }),
      range(parenSpan[5], `1-${parenSpan[6]}`, { open: "", close: ")" }),
    ];
  }
  const bracketSpan = text.match(/^(\d+):(\d+)-(\d+)\[(\d+)-(\d+):(\d+)\]\s*$/);
  if (bracketSpan) {
    return [
      range(bracketSpan[1], `${bracketSpan[2]}-${bracketSpan[3]}`),
      range(bracketSpan[1], `${bracketSpan[4]}-177`, { open: "[", close: "" }),
      range(bracketSpan[5], `1-${bracketSpan[6]}`, { open: "", close: "]" }),
    ];
  }
  if (text.includes(",")) return parseComma(text);
  if (text.includes("(")) return parseMarked(text, "(", ")");
  if (text.includes("[")) return parseMarked(text, "[", "]");
  return parseStandard(text, isPsalm);
}

export function resolveCitation(reference, options = {}) {
  const cleaned = String(reference || "").replace(/\*/g, "").trim();
  const book = matchBook(cleaned, !!options.isPsalm);
  if (!book) {
    return { display: cleaned, error: "unknown book", intention: intentionOf(reference), marks: [] };
  }
  let version = options.version || "KJV";
  const apocryphaFrom = Number.isFinite(options.apocryphaFromBook) ? options.apocryphaFromBook : 67;
  if (book.bookId >= apocryphaFrom) version = options.apocryphaVersion || "RSV";
  if (options.useBcp && book.isPsalm) version = "BCP";
  let rest = book.rest.trim();
  let fullWrap = "";
  if (rest.startsWith("[")) {
    fullWrap = "[";
    rest = rest.replace(/^\[/, "").replace(/\]\s*$/, "");
  } else if (rest.startsWith("(")) {
    fullWrap = "(";
    rest = rest.replace(/^\(/, "").replace(/\)\s*$/, "");
  }
  return {
    display: cleaned,
    bookId: book.bookId,
    isPsalm: book.isPsalm,
    version,
    intention: intentionOf(reference),
    fullWrap,
    marks: parseRest(rest, book.isPsalm),
  };
}

export function escapeHtml(value) {
  return String(value)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

export function passageText(spec, getVerses) {
  if (!spec || spec.error || !spec.marks || spec.marks.length === 0) {
    return `<p><strong>${escapeHtml(spec && spec.display ? spec.display : "")}</strong></p>`;
  }
  const chunks = [];
  if (spec.fullWrap === "[") chunks.push("<strong>[</strong>");
  if (spec.fullWrap === "(") chunks.push("<strong>(</strong>");
  for (const mark of spec.marks) {
    if (!Number.isFinite(mark.chapter) || !Number.isFinite(mark.start) || !Number.isFinite(mark.end)) {
      continue;
    }
    const verses = getVerses(spec.version, spec.bookId, mark.chapter) || [];
    const chosen = verses.filter((verse) => verse.verse >= mark.start && verse.verse <= mark.end);
    if (chosen.length === 0) {
      chunks.push(`<em>(${escapeHtml(spec.version)} text for this passage is not on this server)</em> `);
      continue;
    }
    for (const verse of chosen) {
      if (mark.open && verse.verse === mark.start) chunks.push(`<strong>${mark.open}</strong>`);
      chunks.push(`${escapeHtml(verse.text)} `);
      if (mark.close && verse.verse === mark.end) chunks.push(`<strong>${mark.close}</strong>`);
    }
  }
  if (spec.fullWrap === "[") chunks.push("<strong>]</strong>");
  if (spec.fullWrap === "(") chunks.push("<strong>)</strong>");
  return chunks.join("");
}

export function countVerses(spec, getVerses) {
  if (!spec || !spec.marks) return 0;
  let count = 0;
  for (const mark of spec.marks) {
    const verses = getVerses(spec.version, spec.bookId, mark.chapter) || [];
    count += verses.filter((verse) => verse.verse >= mark.start && verse.verse <= mark.end).length;
  }
  return count;
}
