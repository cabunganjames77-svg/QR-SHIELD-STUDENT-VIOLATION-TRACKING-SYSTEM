# QR Shield

**QR Shield** is a student violation tracking system for Golden West Colleges, Inc. It uses QR codes to identify students and helps the Office of Student Affairs record, review, and report violations.

## Development

The first development phase focused on identifying the OSA's need for a reliable gate-to-office process, verified student identities, and scan recording that continues when internet access is unavailable. Requirements were translated into user roles, workflows, database entities, and online/offline scanner behavior.

The system uses a PHP web application connected to a relational database, plus a mobile-friendly browser scanner. Scans sync immediately when the server is reachable; otherwise, they are queued on the device and can be synced or transferred to the office. The System Admin reviews records and evidence before final reporting.

## Users and Design

- **Students:** activate accounts from the official roster, view their QR code, records, and notifications.
- **Guards / Marshals:** scan student QR codes and record gate incidents online or offline.
- **OSA Staff:** look up students and record or view office violations.
- **System Admin:** manages users and settings, reviews evidence, and prepares reports and backups.
- **School Admin:** has read-only access to office information.

The application uses role-based authorization and shares violation validation across scanner submissions, offline imports, and office forms.

## Technologies

| Area | Technologies |
|---|---|
| Programming languages | PHP, JavaScript, SQL, HTML, CSS |
| Database | MySQL / MariaDB |
| Local development | XAMPP, Apache, PHP, MySQL/MariaDB |
| Libraries | PhpSpreadsheet, html5-qrcode, Chart.js |
| Offline support | Service Worker, IndexedDB, PWA |
| Development tools | Visual Studio Code, Git, Composer, browser developer tools |

The database stores accounts and roles, student and staff profiles, violations, violation types, notifications, settings, and audit records. The fresh-install schema and upgrade scripts are in `database/schema/` and `database/upgrades/`; see [`docs/DATABASE.md`](docs/DATABASE.md) before installing or upgrading. Never commit `.env` files, credentials, service-account keys, or backups containing student data.

## Project Documentation

- [`docs/USER_MANUAL.md`](docs/USER_MANUAL.md) — instructions by user role
- [`docs/SYSTEM_FLOW.md`](docs/SYSTEM_FLOW.md) — system architecture and online/offline flow
- [`docs/FEATURES.md`](docs/FEATURES.md) — feature and code reference
- [`docs/DATABASE.md`](docs/DATABASE.md) — database architecture
- [`scanner/README.md`](scanner/README.md) — scanner setup
