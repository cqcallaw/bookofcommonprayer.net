# bookofcommonprayer.net

The PHP application in this tree is the current site. `site/` is a static build of the same offices and rites, produced with Eleventy. The published files do not need PHP or MariaDB.

## Build

The export reads the local database through the PHP calendar classes. Start the Docker stack first (`docker compose` in `docker/`, site on port 8090).

```sh
npm install
npm run export
npm run build
npm run serve
```

`npm run serve` listens on http://127.0.0.1:8091/. `npm run export` rewrites `site/_data/` and `site/bible/`.

## What is generated

- Rite pages from `public/views`, with the shared header baked in.
- A Daily Office page for each civil date from 1 January 2024 through 31 December 2032, at `/office/YYYY/MM/DD/`. Morning and evening are anchors on that page. Readings without the prayers are at `/office/YYYY/MM/DD/readings/`.
- A Sunday lectionary page for each of those Sundays, Revised Common Lectionary first and the 1979 lectionary after it.
- Collects, prefaces, canticles, the thirty-day psalter, and a holy-day list.

The date index is made by running `BCP\ChurchCalendar` and `BCP\DailyOffice` the same way `api/daily_office.php` does. It is not a new Easter calculation.

Pages embed the King James Version so they can be read with scripts turned off. The browser then asks for the translation saved in preferences. NRSV is that default. Book id 67 and above is read from the RSV, which is the only copy of those books in the database.

`site/bible/` and `_site/` are gitignored. RSV, NRSV, and NKJV must not be committed. A built office page can still contain an RSV excerpt when the lesson is from the Apocrypha.

## Deployment

Publish the files in `_site/`. Copy that directory to the server and point the document root at the copy. The host serves files only. It does not run PHP, Node, or MariaDB. Do not point the document root at this git checkout. The checkout still contains the PHP application, and a document root there would publish it.

`npm run build` writes `_site/`.

Each URL is a folder with `index.html` inside it. Apache needs `DirectoryIndex index.html`. nginx needs `try_files`. Sample vhosts are `deploy/apache.conf` and `deploy/nginx.conf`. There is no PHP and no rewrite map on the published site.

`npm run serve` is only for reading the build on this machine. It listens on http://127.0.0.1:8091/.

## Change the site

### A Bible translation

Pages embed the translation named `embedded` in `site/_data/policy.json`. The reader's default is `readerDefault` in that same file. Apocrypha (book id 67 and up) is read from `apocrypha`.

A translation that is already in the database gets chapter files from `npm run export`. Add its display name to `site/_data/version-names.json` if it is new. The preferences menu is that list, plus any code that already has a directory under `site/bible/`.

A translation that is not in the database is a row in `site/_data/extra-translations.json` and one JSON file per chapter at `site/bible/CODE/bookId/chapter.json`:

```json
{"verses":[{"verse":1,"text":"..."}]}
```

Codes listed under `copyrighted` in `policy.json` stay out of git. `site/bible/` is gitignored as a whole.

### The look of a page

The header, footer, and menu are `site/_includes/layout.njk`. Menu entries come from `site/_data/catalog.json`. The blue theme is `public/themes/bcp/css/theme-blue-2.css`. Small adjustments are `site/assets/site.css`.

Morning and evening wording, including the confession, creed, and Lord's Prayer, is `site/_includes/office.njk` and `site/_includes/rite-ii.njk`. Sunday headings are `site/_includes/lectionary.njk`. Which lessons are chosen is still `site/lib/render.js`.

### Another language, or Compline

`site/_data/catalog.json` lists the offices and the languages. An office entry with an `href` appears in the Daily Offices menu. Compline is the rite page at `/compline/`, from `public/views/compline.phtml`. A dated Compline would be a new template next to `office.njk`. The four Compline sentences are the array in `api/daily_office_compline_scripture.php`, not a database table. Noonday has no page yet. The same file says where those sentences are.

Spanish collects, prefaces, and canticles are already pages. Spanish morning and evening prayer would be `site/_includes/office-es.njk`. The lesson citations are shared. Contemporary Spanish prefaces are already in `site/_data/prefaces_contemporary_es.json`. Add a row to `site/_data/prefacePages.js` with `which` set to `CES` to publish that page.

## Not in this build

Rite I and Spanish morning and evening prayer, the antiphon and canticle rotation, and the noonday office. Compline is the rite page, not a dated office. The church-year math is still the PHP classes.
