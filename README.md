# bookofcommonprayer.net

This tree is the Eleventy source for the static site. `npm run build` writes ordinary HTML, CSS, and JSON into `_site/`. The published files do not need PHP, Node, or MariaDB.

## License

The program is under the GNU General Public License, version 3 or any later version. The license text is `LICENSE`. This is the same grant as the 2013 header on the old site.

Copyright (C) 2013 Mike Seiler
Copyright (C) 2026 Caleb Callaway

The license covers the templates, scripts, and the markup written for this site.

These parts are outside that grant:

- The English text of the 1979 Book of Common Prayer is in the public domain.
- The Spanish text of *El Libro de Oración Común* is a separate work. Printed editions carry a 1989 Church Pension Fund copyright.
- Bible translations. The King James Version embedded in the pages is in the public domain. RSV, NRSV, and NKJV are not in this repository and are not covered. A built page can still quote an RSV verse when the lesson is from the Apocrypha.
- Bootstrap 3.1.1 and normalize.css are under the MIT license. Font Awesome 4.6.2 is MIT for the CSS and the SIL Open Font License 1.1 for the fonts. `public/themes/bcp/css/theme-blue-2.css` is Z-Theme by mamootthemes and has no license statement in the file.

## Build

```sh
npm install
npm run build
npm run serve
```

`npm run serve` listens on http://127.0.0.1:8091/.

## Checks

`npm` scripts are the task runner for this project.

```sh
npm run check
npm run format
```

`npm run check` runs Biome 2.6.7. It checks formatting and the recommended lint rules for `eleventy.config.js`, the JavaScript under `site/`, and `site/assets/site.css`. `npm run format` writes the fixes Biome can apply.

Biome does not read the Nunjucks templates, the rite sources, the JSON data, or the built HTML. Those files hold the liturgy, and a formatter would rewrap it.

`.editorconfig` tells an editor to use UTF-8, LF, and two-space indents. It does not rewrite files by itself.

The `[hidden]` rule in `site/assets/site.css` keeps `!important`. Bootstrap would otherwise display those blocks. That one Biome rule is turned off in `biome.json`.

## What is generated

- Rite pages from `rites/`, with the shared header baked in.
- A Daily Office page for each civil date from 1 January 2024 through 31 December 2032, at `/office/YYYY/MM/DD/`. Morning and evening are anchors on that page. Readings without the prayers are at `/office/YYYY/MM/DD/readings/`.
- A Sunday lectionary page for each of those Sundays, Revised Common Lectionary first and the 1979 lectionary after it.
- Collects, prefaces, canticles, the thirty-day psalter, and a holy-day list.

The date index is `site/_data/days.json`. It was exported from the PHP classes `BCP\ChurchCalendar` and `BCP\DailyOffice`. Those classes are not in this tree. Rebuilding the index means using the commit that still has them. It is not a new Easter calculation.

Pages embed the King James Version so they can be read with scripts turned off. The browser then asks for the translation saved in preferences. NRSV is that default. Book id 67 and above is read from the RSV chapter files when they are present.

`site/bible/` and `_site/` are gitignored. RSV, NRSV, and NKJV must not be committed. A built office page can still contain an RSV excerpt when the lesson is from the Apocrypha.

## Deployment

Publish the files in `_site/`. Copy that directory to the server and point the document root at the copy. The host serves files only. It does not run PHP, Node, or MariaDB. Do not point the document root at this git checkout. The checkout is the Eleventy project (templates, rite sources, and data). The pages to publish are `_site/`.

`npm run build` writes `_site/`.

Each URL is a folder with `index.html` inside it. Apache needs `DirectoryIndex index.html`. nginx needs `try_files`. Sample vhosts are `deploy/apache.conf` and `deploy/nginx.conf`. There is no PHP and no rewrite map on the published site.

`npm run serve` is only for reading the build on this machine. It listens on http://127.0.0.1:8091/.

## Change the site

### A Bible translation

Pages embed the translation named `embedded` in `site/_data/policy.json`. The reader's default is `readerDefault` in that same file. Apocrypha (book id 67 and up) is read from `apocrypha`.

Add a display name to `site/_data/version-names.json` when a translation already has chapter files. The preferences menu is that list, plus any code that already has a directory under `site/bible/`.

A translation that is not already on disk is a row in `site/_data/extra-translations.json` and one JSON file per chapter at `site/bible/CODE/bookId/chapter.json`:

```json
{"verses":[{"verse":1,"text":"..."}]}
```

Codes listed under `copyrighted` in `policy.json` stay out of git. `site/bible/` is gitignored as a whole.

### The look of a page

The header, footer, and menu are `site/_includes/layout.njk`. Menu entries come from `site/_data/catalog.json`. The blue theme is `public/themes/bcp/css/theme-blue-2.css`. Small adjustments are `site/assets/site.css`.

Morning and evening wording is `site/_includes/office.njk` and `site/_includes/rite-ii.njk`. Sunday headings are `site/_includes/lectionary.njk`. Which lessons are chosen is still `site/lib/render.js`. The Rite II canticle table is in that file.

### Another language, or Compline

`site/_data/catalog.json` lists the offices and the languages. An office entry with an `href` appears in the Daily Offices menu. Compline is the rite page at `/compline/`, from `rites/compline.phtml`. A dated Compline would be a new template next to `office.njk`. Noonday has no page yet. The catalog entry says what to add.

Spanish collects, prefaces, and canticles are already pages. Spanish morning and evening prayer would be `site/_includes/office-es.njk`. The lesson citations are shared. Contemporary Spanish prefaces are already in `site/_data/prefaces_contemporary_es.json`. Add a row to `site/_data/prefacePages.js` with `which` set to `CES` to publish that page.

## Not in this build

Rite I and Spanish morning and evening prayer, feast and seasonal invitatory antiphons, and the noonday office. Morning and Evening Prayer keep every opening sentence, prayer for mission, and closing sentence in the page and, when scripts run, show one of each, chosen on each load. An ordinary weekday prints the three invitatory antiphons. Canticle rotation follows the Rite II table when all readings are shown; the evening default is the Song of Mary or the Song of Simeon. Compline is the rite page, not a dated office. Church-year dates are the exported index in `site/_data/days.json`.
