// The menu of Bible translations. Names come from version-names.json.
// Extra names come from extra-translations.json. A directory under
// site/bible/ that has no name yet is listed by its code. BCP is the
// psalter, not a Bible version.

import { existsSync, readdirSync, readFileSync, statSync } from "node:fs";
import { fileURLToPath } from "node:url";

const dir = fileURLToPath(new URL("./", import.meta.url));
const read = (name) => JSON.parse(readFileSync(dir + name, "utf8"));

const policy = read("policy.json");
const named = read("version-names.json");
const extraFile = read("extra-translations.json");
const extra = Array.isArray(extraFile) ? extraFile : extraFile.translations || [];

const names = new Map();
for (const row of [...named, ...extra]) {
  if (row?.id && !names.has(row.id)) names.set(row.id, row.name || row.id);
}

const bibleDir = fileURLToPath(new URL("../bible/", import.meta.url));
if (existsSync(bibleDir)) {
  for (const entry of readdirSync(bibleDir)) {
    if (entry === "BCP" || entry.startsWith(".")) continue;
    if (!statSync(bibleDir + entry).isDirectory()) continue;
    if (!names.has(entry)) names.set(entry, entry);
  }
}

const order = [];
const push = (id) => {
  if (!id || order.includes(id) || !names.has(id)) return;
  order.push(id);
};
push(policy.readerDefault);
push(policy.embedded);
for (const row of named) push(row.id);
for (const row of extra) push(row.id);
for (const id of [...names.keys()].sort()) push(id);

const copyrighted = new Set(policy.copyrighted || []);

export default order.map((id) => ({
  id,
  name: names.get(id),
  readerDefault: id === policy.readerDefault,
  embedded: id === policy.embedded,
  apocrypha: id === policy.apocrypha,
  copyrighted: copyrighted.has(id),
}));
