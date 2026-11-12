import { readFileSync } from "node:fs";
import { fileURLToPath } from "node:url";

const days = JSON.parse(readFileSync(fileURLToPath(new URL("./days.json", import.meta.url)), "utf8"));
const names = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const months = [];
const seen = new Set();
for (const day of days) {
  const key = `${day.y}-${day.m}`;
  if (seen.has(key)) continue;
  seen.add(key);
  months.push({ y: day.y, m: day.m, name: names[Number(day.m) - 1] });
}
export default months;
