# Operational security notes

## Purpose

These notes describe safe deployment boundaries for LogCleaner. They are operational guidance, not application code.

## Principles

- LogCleaner should be used through a normal protected Nextcloud deployment.
- Do not expose development instances directly to the public internet.
- Prefer layered controls: TLS at the reverse proxy, Nextcloud authentication, administrator-only app access, and host-level file permissions.
- Keep the server able to receive operating system, PHP, web server, and Nextcloud security updates.
- Do not try to protect the system by blindly blocking all outbound traffic; update paths and package repositories must remain intentionally available.
- Avoid direct access to the log file outside Nextcloud unless maintenance requires it.

## Deployment checklist

- [ ] Nextcloud is served over HTTPS.
- [ ] The web server and PHP runtime are supported and patched.
- [ ] The Nextcloud data directory is not web-exposed.
- [ ] The log file is readable and writable only by the expected server user.
- [ ] Only administrators can access LogCleaner.
- [ ] Backups exist before destructive log cleanup experiments.
- [ ] No tokens, credentials, or full production paths are copied into public tickets or logs.

## Network boundary

Use segmentation and least privilege. The application should sit behind the same access controls as the rest of the Nextcloud administration surface. Public port forwarding to development helpers, local scripts, or validation tools is not needed.
