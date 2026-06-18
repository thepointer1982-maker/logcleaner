## Summary

- 

## Validation

- [ ] `./repo-run.sh ci` passes.
- [ ] Changed PHP files pass focused syntax checks.
- [ ] Route/controller changes pass `./repo-run.sh routes`.
- [ ] Localization changes pass `./repo-run.sh json`.
- [ ] Metadata changes pass `./repo-run.sh metadata`.

## Security review

- [ ] Destructive or mutating operations remain admin-only.
- [ ] Route parameters are validated before use.
- [ ] Logfile reads/writes handle missing or inaccessible files.
- [ ] Malformed log lines do not crash runtime paths.
- [ ] No sensitive log contents, tokens, user data, or production paths were added to logs or docs.

## Notes

- 
