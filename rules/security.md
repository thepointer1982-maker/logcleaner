# Security rules

- LogCleaner is an administrator tool. Treat configuration writes and log writes as administrative operations.
- Destructive operations include emptying the log, deleting single lines, deleting duplicates, deleting by level, deleting by app, and changing loglevel.
- Validate route parameters before using them in file or config operations.
- Treat log lines as untrusted input. Invalid JSON, missing fields, and unexpected scalar values must not crash runtime paths.
- Guard file operations with existence, file type, readability, and writability checks where relevant.
- Do not add public access exceptions to destructive or mutating routes unless the reason is documented.
- Do not write sensitive log contents, tokens, user data, or full production filesystem paths into app logs.
- Prefer non-GET methods for mutating routes when the frontend can be updated safely at the same time.
