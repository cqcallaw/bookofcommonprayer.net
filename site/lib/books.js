// Book ids copied from BCP\Parser::getBookNameAndId. The first token of a
// citation is matched with spaces removed, so "1 Corinthians" and
// "1Corinthians" both hit 1CORINTHIANS.

export const BOOKS = {
  GENESIS: 1,
  EXODUS: 2,
  LEVITICUS: 3,
  NUMBERS: 4,
  DEUTERONOMY: 5,
  JOSHUA: 6,
  JUDGES: 7,
  RUTH: 8,
  "1SAMUEL": 9,
  "2SAMUEL": 10,
  "1KINGS": 11,
  "2KINGS": 12,
  "1CHRONICLES": 13,
  "2CHRONICLES": 14,
  EZRA: 15,
  NEHEMIAH: 16,
  ESTHER: 17,
  JOB: 18,
  PSALM: 19,
  PSALMS: 19,
  PROVERBS: 20,
  ECCLESIASTES: 21,
  SONGOFSOLOMON: 22,
  SONGOFSONGS: 22,
  ISAIAH: 23,
  JEREMIAH: 24,
  LAMENTATIONS: 25,
  EZEKIEL: 26,
  DANIEL: 27,
  HOSEA: 28,
  JOEL: 29,
  AMOS: 30,
  OBADIAH: 31,
  JONAH: 32,
  MICAH: 33,
  NAHUM: 34,
  HABAKKUK: 35,
  ZEPHANIAH: 36,
  HAGGAI: 37,
  ZECHARIAH: 38,
  MALACHI: 39,
  MATTHEW: 40,
  MARK: 41,
  LUKE: 42,
  JOHN: 43,
  ACTS: 44,
  ROMANS: 45,
  "1CORINTHIANS": 46,
  "2CORINTHIANS": 47,
  GALATIANS: 48,
  EPHESIANS: 49,
  PHILIPPIANS: 50,
  COLOSSIANS: 51,
  "1THESSALONIANS": 52,
  "2THESSALONIANS": 53,
  "1TIMOTHY": 54,
  "2TIMOTHY": 55,
  TITUS: 56,
  PHILEMON: 57,
  HEBREWS: 58,
  JAMES: 59,
  "1PETER": 60,
  "2PETER": 61,
  "1JOHN": 62,
  "2JOHN": 63,
  "3JOHN": 64,
  JUDE: 65,
  REVELATION: 66,
  "1MACCABEES": 67,
  ECCLESIASTICUS: 68,
  SIRACH: 68,
  WISDOM: 69,
  BARUCH: 70,
  JUDITH: 71,
};

const KEYS = Object.keys(BOOKS).sort((a, b) => b.length - a.length);

export function matchBook(reference, isPsalm) {
  const ref = String(reference || "")
    .replace(/\*/g, "")
    .trim();
  if (isPsalm || /^(psalms?)\b/i.test(ref)) {
    const rest = ref.replace(/^(psalms?)\s*/i, "");
    return { bookId: 19, bookName: "Psalm", rest, isPsalm: true };
  }
  const tokens = ref.split(/\s+/);
  let compact = "";
  for (let i = 0; i < tokens.length; i++) {
    compact += tokens[i].toUpperCase();
    if (BOOKS[compact]) {
      const longer = KEYS.some(
        (key) =>
          key.startsWith(compact) &&
          key.length > compact.length &&
          tokens
            .slice(0, i + 2)
            .join("")
            .toUpperCase() === key,
      );
      if (longer) {
        continue;
      }
      return {
        bookId: BOOKS[compact],
        bookName: null,
        rest: tokens.slice(i + 1).join(" "),
        isPsalm: BOOKS[compact] === 19,
      };
    }
  }
  return null;
}
