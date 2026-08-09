# mca/access-intel

**English** | [Türkçe](README.tr.md)

IP health scoring on top of `mca/access-log` for Laravel 13.  
**Access suite** role: `intel`.

## Install

```bash
composer require mca/access-log mca/access-intel
php artisan mca:access-log:install
php artisan mca:access-intel:install
```

Open `/mca/access-intel` as root.

## Score signals

- Request volume
- 4xx/5xx error ratio
- Path diversity (scan-like behavior)
- Firewall block ratio

## Suite dependencies

| Package | Relation |
|---------|----------|
| `mca/access-log` | Suggested / soft-required for data |
| `mca/firewall` | Suggested — ban from intel UI |
| `mca/permission` | Suggested — root UI |
| `mca/hub` | Suggested — dashboard card |

## License

MIT
