# review

Goal: review a LogCleaner change before merge.

Checklist:

- [ ] The change is small and focused.
- [ ] Changed PHP files pass `php -l`.
- [ ] `./repo-run.sh ci` passes locally or in CI.
- [ ] Route changes pass `./repo-run.sh routes`.
- [ ] Translation changes pass `./repo-run.sh json`.
- [ ] Metadata changes preserve app id `logcleaner`, namespace `LogCleaner`, and supported Nextcloud range `31..33`.
- [ ] Destructive or mutating operations remain admin-only.
- [ ] Route parameters are validated before use.
- [ ] Logfile reads/writes guard missing, unreadable, or unwritable files.
- [ ] Malformed JSON log lines do not crash controllers, widgets, or cron jobs.
- [ ] No sensitive log contents, tokens, user data, or full production paths were added to logs or docs.
