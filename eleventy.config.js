import { officeView, lectionaryView, renderCollects, renderCanticles, renderPrefaces, renderPsalter, renderHolyDays } from "./site/lib/render.js";
import { readFileSync } from "node:fs";

const psalterIndex = JSON.parse(readFileSync(new URL("./site/_data/psalter_index.json", import.meta.url), "utf8"));

export default function (eleventyConfig) {
  eleventyConfig.ignores.add("site/bible/**");
  eleventyConfig.addPassthroughCopy({ "site/bible": "bible" });
  eleventyConfig.addPassthroughCopy({ "site/assets": "assets" });
  eleventyConfig.addPassthroughCopy({ "site/lib/books.js": "lib/books.js" });
  eleventyConfig.addPassthroughCopy({ "site/lib/citations.js": "lib/citations.js" });
  eleventyConfig.addPassthroughCopy("public");
  eleventyConfig.addPassthroughCopy("favicon.ico");
  eleventyConfig.addPassthroughCopy("robots.txt");

  eleventyConfig.addFilter("buildYear", () => String(new Date().getFullYear()));
  eleventyConfig.addFilter("json", (value) => JSON.stringify(value));
  eleventyConfig.addFilter("officeView", (day) => officeView(day));
  eleventyConfig.addFilter("lectionaryView", (day) => lectionaryView(day));
  eleventyConfig.addFilter("collectsHtml", (which) => renderCollects(which));
  eleventyConfig.addFilter("canticlesHtml", (which) => renderCanticles(which));
  eleventyConfig.addFilter("prefacesHtml", (which) => renderPrefaces(which));
  eleventyConfig.addFilter("psalterHtml", () => renderPsalter(psalterIndex));
  eleventyConfig.addFilter("holyDaysHtml", () => renderHolyDays());

  return {
    dir: {
      input: "site",
      output: "_site",
      includes: "_includes",
      data: "_data",
    },
    htmlTemplateEngine: "njk",
    markdownTemplateEngine: "njk",
  };
};
