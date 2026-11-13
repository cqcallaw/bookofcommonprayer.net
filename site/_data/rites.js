import { readdirSync, readFileSync } from "node:fs";
import { fileURLToPath } from "node:url";

const views = fileURLToPath(new URL("../../rites/", import.meta.url));
const skip = new Set([
  "welcome",
  "collects",
  "canticles",
  "prefaces",
  "contemporary_prefaces",
  "error",
  "forbidden",
  "bad_method",
  "lectionary_sub",
  "psalter",
]);

function rewrite(html) {
  let text = html.replace(
    /<\?php\s+echo\s+SERVER;\s*\?>#\/search\/display\/([^"'<>]+)/g,
    (_, citation) => `/search/?q=${encodeURIComponent(citation.trim())}`
  );
  text = text.replace(
    /href=(["'])search\?passage=([^"']+)\1/g,
    (_, quote, citation) => `href=${quote}/search/?q=${encodeURIComponent(citation.trim())}${quote}`
  );
  text = text.replace(/href=(["'])canticles#/g, "href=$1/canticles/#");
  const hadPhp = /<\?php/.test(text);
  text = text.replace(/<\?php[\s\S]*?\?>/g, "");
  return { html: text.trim(), hadPhp };
}

const rites = [];
for (const file of readdirSync(views).sort()) {
  if (!file.endsWith(".phtml")) continue;
  const slug = file.slice(0, -6);
  if (skip.has(slug)) continue;
  const source = readFileSync(views + file, "utf8");
  const { html, hadPhp } = rewrite(source);
  const words = html.replace(/<[^>]+>/g, " ").replace(/\s+/g, " ").trim();
  if (words.length < 40) continue;
  rites.push({ slug, title: slug.replace(/_/g, " "), html, hadPhp });
}
export default rites;
