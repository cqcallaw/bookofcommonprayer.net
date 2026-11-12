# Security checks for the finished site

Written 2026-11-11 from the review of the current PHP app. Check these against the site that actually goes live, including a replacement that no longer uses this code. "Fixed" includes "this piece no longer exists."

Do not put the production database password, Bible dumps, or exploit material in this file.

## Runtime

- The live host runs a PHP release that still receives security fixes, at or above the patch level that fixes CVE-2026-109618 and CVE-2026-109619. As of this note that means PHP 8.4.27, 8.3.36, or 8.2.35, with 8.4 preferred. PHP 8.2 security support ends 2026-12-31.
- The production `.htaccess` handler is not `application/x-httpd-ea-php74`. The handler and the host PHP change in the same deploy. This tree uses `#[\AllowDynamicProperties]` and will not parse on PHP 7.4.
- If the finished site is static files, PHP is absent and this group is satisfied.

## Errors and headers

- A normal page, a missing page, and a bad API request show no PHP warning, stack trace, filesystem path, SQL text, or database name.
- Production does not set `display_errors` from `E_ALL`. `index.php` today does `ini_set("display_errors", ERROR_SET)`, and the downloaded config sets `ERROR_SET` to `E_ALL`, which turns display on.
- If PHP remains, PDO uses `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`. The current code sets `PDO::ATTR_ERRMODE => true` (warnings) and uses `PDO::ERRMODE_EXCEPTION` as an attribute name, which does not enable exceptions. Exception text is not returned in the response. `Preface.php` catches the wrong class and returns `$e->getMessage()`.
- Responses include `Strict-Transport-Security`, `X-Content-Type-Options: nosniff`, a frame policy (`X-Frame-Options` or CSP `frame-ancestors`), and a Content-Security-Policy that matches how the pages actually load scripts.
- Check with a browser and with a request that sends no `Accept` header. On the local PHP 8.4 app, a missing `Accept` header prints a deprecation and the path to `Request.php`.

## HTTPS

- `https://bookofcommonprayer.net/...` redirects once to `https://www.bookofcommonprayer.net/...` and never to `http://www...`. The current apex rule hardcodes `http://www.bookofcommonprayer.net`.
- The HTTPS rule does not copy the request `Host` header into the redirect target.
- An HTTP request ends on HTTPS before any page or cookie is set.

## Files the web server must not serve

Request each of these. The response must not be the file's contents, a stack trace, or a directory listing.

- `system/cfg/config.php` and `system/cfg/config.example.php`
- `vendor/` (including `vendor/composer/installed.json` and `vendor/BCP/Parser.php`)
- `controllers/`
- any `.phtml` under `public/views/`
- `docker/`, `orig/`, `.git/`
- `api/*.php` entered as real files, such as `api/search.php` and `api/daily_office.php`. Today those run outside the front controller. `api/search.php` prints a fatal error and an absolute path when display_errors is on. Routed POSTs to `/api/daily_office` may still exist until the API is removed.
- `bugsnag/` (PHP files, `composer.json`, and `ARCHITECTURE.md` are readable on the local app today)
- backup names such as `.htaccess_old`, editor swaps, and `config.php~`

The production database password stays only in the untracked config. RSV, NRSV, and NKJV texts stay off any public repository.

## Cookies and sessions

- If a session cookie remains, it is `Secure`, `HttpOnly`, and `SameSite=Lax` (or stricter). Today `session_start()` sets `PHPSESSID` with only `path=/`.
- No session cookie is also a pass, once nothing reads `$_SESSION`. Nothing sets `$_SESSION["isAuthenticated"]` today. The login link in `vendor/nav_bar/nav_bar.php` never renders.
- Preference cookies hold rite, office, lectionary, translation, language, and timezone. They must not hold secrets. They are set from JavaScript today, so they cannot be `HttpOnly`.

## Data access

There is no `INSERT`, `UPDATE`, or `DELETE` in the PHP. Confirm the finished site still has no visitor-writable store and no admin login hidden in the tree.

If the PHP query code remains:

- `Collects.php` and `Prefaces.php` may append `_contemporary` only for an exact `C`.
- Lectionary table names stay an exact choice between the 1979 and RCL tables.
- Verse queries keep bound parameters for version, book, chapter, and verse.
- `Preface.php` stops pasting `collects.pref_id` into `IN (...)`. Bind each id.
- `DailyOfficeReferences.php` stops pasting `yearNo`, `p_prop`, and `t_prop` into SQL. Those values are computed today (`1`/`2` and a proper number, and only for ids `P` and `T`), not taken from the request body.
- `PsalmParser::parseCanticle` is deleted or rewritten. It calls `mysql_query`, which PHP 7 removed, and nothing constructs `PsalmParser` or `BCPPsalmParser`.

`Request.php` must not print the request path into HTML. A quote in the query string is echoed today. Browsers percent-encode `<` and `"` and the current code does not decode them.

## Front-end copies

These versions are loaded by `public/themes/bcp/header.php`: AngularJS 1.5.5, jQuery 2.2.3, Bootstrap 3.1.1. Guzzle 6.3.0, psr7 1.4.2, and Bugsnag 3.8.1 are in `vendor/`. Bugsnag is commented out in `index.php` and the key there is the placeholder `BUGSNAG_API_KEY`.

The finished site either no longer ships these or runs maintained releases. Prayer text goes through `ng-bind-html` today. The filters do not mark HTML as trusted, and the text comes from the database. Confirm a replacement does not treat visitor input as HTML.

## Done when

A dated pass through this list is written under a `## Checks` heading below, with the host, the revision, and any item left open.

## Checks
