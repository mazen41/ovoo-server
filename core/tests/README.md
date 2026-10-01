# Running the flow tests

`core/tests/` is dev-only — release zips are built with `git archive ... ':!core/tests'`, so
nothing here ever reaches a customer.

The feature tests drive the real `/webhook` endpoint against a real database and fake only the
outbound Meta Graph HTTP calls. They must never run against the dev database
(`product_ovowpp_v2_4`), so they live behind their own config, `tests/phpunit.flow.xml`, which
points them at a throwaway clone. The shipped `core/phpunit.xml` is deliberately left untouched
so `php artisan test` keeps working out of the box; under it these tests skip themselves with a
message telling you the command below.

Create the throwaway database from the dev schema (structure only — the tests seed what they
need and wrap every test in a transaction):

```bash
# from a Laragon shell, adjust the source database if yours differs.
# plain `mysql` is not on PATH under Laragon; use the full path, e.g.
#   /e/laragon/bin/mysql/mariadb-12.3.3-winx64/bin/mysql.exe
mysql -u root -e "DROP DATABASE IF EXISTS product_ovowpp_test; CREATE DATABASE product_ovowpp_test;"
mysqldump -u root --no-data --skip-add-locks product_ovowpp_v2_4 | mysql -u root product_ovowpp_test
mysqldump -u root --no-create-info product_ovowpp_v2_4 general_settings extensions | mysql -u root product_ovowpp_test
```

Then, from `core/`:

```bash
composer install                                # dev dependencies, phpunit included
./vendor/bin/phpunit -c tests/phpunit.flow.xml  # the flow suite -> 27 tests
```

Drop it again when you are done:

```bash
mysql -u root -e "DROP DATABASE IF EXISTS product_ovowpp_test;"
```

## What is covered

`tests/Feature/Flow/AutomationFlowTest.php` posts genuine Meta webhook payloads (text,
`interactive.button_reply`, `interactive.list_reply`, template quick replies arriving as
`type: "button"`) and asserts on the Graph API requests the app actually sends, plus the
`contact_flow_states` row left behind. `FlowTestCase.php` is the harness; it deliberately stores
`buttons_json` as a `json_encode()`d string, the shape the flow builder really writes, and
refuses to run at all unless `DB_DATABASE` is the throwaway clone.

## React checks

The flow-builder React fixes (list node source handle, canvas node duplication, the trigger node
a saved flow already carries) are covered by a headless render harness rather than PHPUnit:

```bash
npm install                       # needs node_modules for react + esbuild
node tests/js/run.mjs             # the current sources -> 9/9 pass
node tests/js/run.mjs baseline    # the versions in git HEAD -> 8 of the 9 fail
```

It stubs `reactflow`, `./http` and `react-dom/client`, renders the components with
`react-dom/server`, and asserts on the resulting markup, so no browser is involved. The baseline
mode fetches the pre-fix sources with `git show HEAD:...` and deletes them again on exit.
