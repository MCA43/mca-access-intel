# Changelog

## [0.1.0] - 2026-08-09

### Added
- IP health scoring from `mca/access-log` (volume, errors, path diversity, blocks)
- Levels: healthy / watch / risky
- Admin UI with ranked IPs and IP detail
- Soft-dep firewall actions (blacklist / whitelist)
- Cacheable ranking window (default 24h)
- Install: `php artisan mca:access-intel:install`
- Hub integration (`extra.mca`, suite: access / intel)

### Notes
- Requires `mca/access-log` for meaningful scores
- Soft-deps: `mca/firewall`, `mca/permission`, `mca/hub`
