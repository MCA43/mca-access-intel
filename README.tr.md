# mca/access-intel

**Türkçe** | [English](README.md)

`mca/access-log` üzerinde IP sağlık skoru — Laravel 13.  
**Access suite** rolü: `intel`.

## Kurulum

```bash
composer require mca/access-log mca/access-intel
php artisan mca:access-intel:install
```

Root ile `/mca/access-intel` açın.

## Skor sinyalleri

- İstek hacmi
- 4xx/5xx hata oranı
- Path çeşitliliği (tarama benzeri davranış)
- Firewall engel oranı

## Lisans

MIT
