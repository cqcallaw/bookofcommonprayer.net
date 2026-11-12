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
- A Daily Office page for each civil date from 1 January 2024 through 31 December 2032, at `/office/YYYY/MM/DD/`. Morning and evening are anchors on that page.
- A Sunday lectionary page for each of those Sundays, Revised Common Lectionary first and the 1979 lectionary after it.
- Collects, prefaces, canticles, the thirty-day psalter, and a holy-day list.

The date index is made by running `BCP\ChurchCalendar` and `BCP\DailyOffice` the same way `api/daily_office.php` does. It is not a new Easter calculation.

Pages embed the King James Version so they can be read with scripts turned off. The browser then asks for the translation saved in preferences. NRSV is that default. Book id 67 and above is read from the RSV, which is the only copy of those books in the database.

`site/bible/` and `_site/` are gitignored. RSV, NRSV, and NKJV must not be committed. A built office page can still contain an RSV excerpt when the lesson is from the Apocrypha.

## Not in this build

Rite I and Spanish morning and evening prayer, the antiphon and canticle rotation, and the noonday office. Compline is the rite page, not a dated office. The church-year math is still the PHP classes.
