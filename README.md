# Ibexa Bulk Operation Guard

Backport of fix on 4.6.33 and 5.0.11 for a critical severity vulnerability in the Rest API can lead
to RCE (Remote Code Execution) by users with access to the Rest API. 
This puts the fix in a bundle so it can be installed on an instance that cannot yet upgrade to 4.6.33 and 5.0.11

## Supported versions

| Ibexa DXP | `ibexa/admin-ui` | Status |
|---|---|---|
| 4.6 | `< 4.6.33` | supported — this is what the bundle is for |
| 4.6 | `>= 4.6.33` | already fixed upstream; composer `conflict` refuses the bundle |
| 5.0 | `< 5.0.11` | supported |
| 5.0 | `>= 5.0.11` | already fixed upstream; composer `conflict` refuses the bundle |

**Upgrading `ibexa/admin-ui` to 4.6.33 or 5.0.11 is the proper remedy.** This bundle exists only for
instances where that upgrade cannot happen yet.

**Remove the bundle before you upgrade.** The composer `conflict` above makes composer refuse any
upgrade that would pull in a fixed `ibexa/admin-ui`, so the upgrade fails as long as the bundle is
installed. Take it off first:

```bash
composer remove vidarl/ibexa-bulk-operation-guard
composer require ibexa/headless:4.6.33 --with-all-dependencies
```

(substitute the meta-package the project actually uses — `ibexa/oss`, `ibexa/headless`,
`ibexa/experience` or `ibexa/commerce`)

One case gives no error: a project that requires `ibexa/admin-ui` directly with a loose constraint
such as `^4.6`, instead of through a meta-package. There composer silently holds `ibexa/admin-ui` at
the last unfixed version while the rest of the stack moves on. Such an install is still protected —
this bundle carries the fix — but it is not upgraded. Verify with `composer show ibexa/admin-ui`
after upgrading.


## Installation

```bash
composer require vidarl/ibexa-bulk-operation-guard
```

The package is on Packagist, so no `repositories` entry is needed.

Symfony Flex registers the bundle for you — every Ibexa skeleton ships with Flex. In a project
without it, add the bundle to `config/bundles.php` by hand:

```php
VidarL\IbexaBulkOperationGuardBundle\VidarLIbexaBulkOperationGuardBundle::class => ['all' => true],
```

Clear the cache afterwards. To confirm the swap took effect:

```bash
php bin/console debug:container 'Ibexa\Bundle\AdminUi\Controller\BulkOperation\BulkOperationController'
```

The reported class must be
`VidarL\IbexaBulkOperationGuardBundle\Controller\BulkOperationController`.

## License

GPL-2.0-only. `src/bundle/Controller/BulkOperationController.php` is derived from `ibexa/admin-ui`
(Copyright (C) Ibexa AS), available under GPL-2.0-only. See `LICENSE`.
