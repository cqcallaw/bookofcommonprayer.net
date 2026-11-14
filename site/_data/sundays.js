import { readFileSync } from "node:fs";
import { fileURLToPath } from "node:url";

const days = JSON.parse(
  readFileSync(fileURLToPath(new URL("./days.json", import.meta.url)), "utf8"),
);
export default days.filter((day) => day.dow === 0);
