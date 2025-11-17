<!-- .github/copilot-instructions.md - guidance for AI coding agents working on this repo -->
# Copilot instructions for Supermercado-BasesDeDatos

This repository is a small PHP/MySQL web app deployed under XAMPP (htdocs). The app uses plain PHP pages and per-page CSS files. Keep instructions focused, actionable, and specific to the files below.

- **Run / debug locally**: this project runs inside XAMPP. Start **Apache + MySQL** and open: `http://localhost/Supermercado-BasesDeDatos/index.php`.
- **Database**: DB name is `supermercado` (used in `conexion.php` and several pages). There is no SQL dump in the repo — ask the maintainer for the schema if needed.

Important files and patterns
- `conexion.php`: central mysqli connection file. It defines `$server`, `$user`, `$pass`, `$db` and creates `$conexion`. Note: it currently echoes a success message on connect — that produces output and can break redirects/headers. Prefer reusing this file rather than duplicating credentials.
- `add_cliente.php`, `add_cajeros.php`, `agregar_proveedores.php`, `agregar_productos.php` (and similar): standalone pages that often create their own DB connection variables rather than importing `conexion.php`. Insert/update SQL is constructed via string interpolation (no prepared statements).
- Per-page CSS: each page pairs with a CSS file with a similar name (e.g. `add_cliente.css`). Keep present styling patterns intact when modifying markup.

Coding conventions discovered
- Filenames and UI text are Spanish. Keep variable names and UX messages consistent with this language when editing the UI (e.g., `nom_cli`, `mensaje`).
- Pages follow a simple single-file form pattern: PHP preamble (connection + POST handling), then HTML form markup, then embedded PHP for status messages (example: `add_cliente.php`).
- SQL usage: raw SQL strings with `mysqli->query()` and error exposed via `$conexion->error`. When modifying DB code, keep the same result-display style (set `$mensaje` and let the page echo it) unless instructed otherwise.

Safety & side-effects to watch for
- `conexion.php` printing text: it echoes on successful connect — avoid adding logic that relies on headers before includes. If you need silent connect behavior, remove or guard that echo but ask the maintainer first.
- No prepared statements: queries use direct interpolation. If you refactor to prepared statements, update the page-level message handling and confirm there are no tight coupling assumptions elsewhere.
- DB credentials are plaintext in repository (typical for local dev). Do not publish these values externally.

What an AI agent should do when editing
- Reuse `conexion.php` where possible; if a page duplicates connection variables (for example `add_cliente.php`), prefer replacing duplication with `require 'conexion.php';` and ensure `$conexion` is available afterwards.
- Preserve existing UI flow: form handling at top of file, then HTML. Maintain `$mensaje` pattern for displaying success/error messages (see `add_cliente.php`).
- When changing SQL code, update only the relevant insert/update/select lines. Example: in `add_cliente.php` the insertion is: `INSERT INTO clientes (id_cli, nom_cli, tel_cli, dir_cli, email_cli) VALUES (...)` — search for `INSERT INTO clientes` to find related pages.

Examples (where to edit)
- Add DB connection include: replace duplicated connection block with `require 'conexion.php';` near the top of page preamble.
- To modify client insertion flow: edit `add_cliente.php`, inside the `if ($_SERVER["REQUEST_METHOD"] == "POST")` block. Keep the `$mensaje` assignment logic.

Developer workflows
- Start XAMPP (Apache + MySQL) to run the site locally. Use browser devtools for CSS/markup debugging and XAMPP's `php_error_log` and Apache error log for backend issues.
- There are no automated tests or build steps in the repo — manual verification required after changes.

If something's missing
- If you need the DB schema or sample data, request the SQL dump from the maintainer.
- If making cross-file changes (e.g., switching all pages to `require 'conexion.php'`), open a PR and include a short migration note explaining why (avoid breaking existing behavior like stray echoes).

Ask the maintainer when uncertain about changing visible behavior (messages, echoed texts, or filenames).

End of instructions.
