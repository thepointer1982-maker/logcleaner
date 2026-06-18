# Changelog

## 1.3.6

### Fixed
- Added `AGENTS.md` so Codex and other coding agents have repository-specific instructions.
- Added a PHP lint workflow for pushes and pull requests.
- Removed the duplicate `Settings#dellog` route.
- Removed the unimplemented `Settings#getLL` route because loglevel is already returned by `/getparam`.
- Fixed app default handling so API responses return scalar values instead of nested `DataResponse` objects.
- Hardened loglevel updates so invalid values are rejected before writing system config.
- Removed undefined `$logid` references from filtered log methods.
- Hardened helper log reading, single-line deletion, and JSON log parsing.
- Added fallback values for log metadata responses when the log file is missing.
- Fixed the cleanup cron job to use the injected app config service and log failures safely.

## 1.3.5

### Changed
- filter updated

### Added
- some css classes
- optional footer with helpful buttons

## 1.3.4

### Fixed
- dashboard widget updates when duplicates are deleted

### Added
- new feature that allows you to delete all log entries for apps that caused the log message
- new feature to filter log entries by apps that caused log messages

## 1.3.3

### Fixed
- some code cleanups

### Added
- new template that only shows entries of the desired error level

### Changed
- dashboard widget can now refer to the new template regarding the display of a certain error level

## 1.3.2

### Added
- some extra information within dashboard widget

### Fixed
- some code fixes

## 1.3.1

### Fixed
- Bug fixed when deleting the last single entry of a filtered view

### Added
- delete log entries by level within settings

### Changed
- language files updated

## 1.3.0

### Fixed
- Bug fixed duplicate handling

## 1.2.9

### Fixed
- Bug fixed wrong log output ([#18](https://github.com/zomtec2311/logcleaner/issues/18))

### Added
- Filter function for displayed error levels

### Changed
- language files updated

## 1.2.8

### Added
- Function to search for log entries on various search engines

## 1.2.7

### Fixed
- some code fixes

### Changed
- position of button 'view detail' ([#14](https://github.com/zomtec2311/logcleaner/issues/14))
- position of button 'copy to clipboard' sticky

## 1.2.6

### Added
- new function to copy log detail to clipboard

### Changed
- language files updated
- loading spinner position

## 1.2.5

### Added
- Showing log entry details

## 1.2.4

### Added
- Preparation for cooperation with AdminCockpit

## 1.2.3

### Changed
-some code fixes
-removed backup process


## 1.2.2

### Changed
- backup process

## 1.2.1

### Fixed
- new swedish language files because of bad translation (Thanks to maghog)

### Added
- backup of the previous version of the app 

## 1.2.0
