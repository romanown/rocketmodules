### Installation guide:
1. Add rocketmodules repository as `git` submodule:
```bash
# execute from project's root direcotry
git submodule add git@github.com:pavsad/rocketmodules.git protected/rocketmodules
```
2. Configure HumHub to be able to discover **new modules** directory.
Change the `%root%/protected/config/common.php` file as follows:
```php
<?php // %root%/protected/config/common.php
return [
    'params' => [
        'moduleAutoloadPaths' => ['@app/rocketmodules'],
    ],
];
```
3. Enable desired module in the HumHub modules administration page
