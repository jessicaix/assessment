# ps_console

It is a command line tool of Drupal site initial construction system.

## Site installation

```
php ps_console/console ps:site:install
```

- It is necessary to place `.env` immediately under the project.
- Refer to `.env.example`.
- `.env` File name should be...

### `.env` file

- `DRUPAL_LANGCODE`: Default language
- `DATABASE_HOST`: DB host
- `DATABASE_NAME`: DB name
- `DATABASE_USER`: DB username
- `DATABASE_PASSWORD`: DB password
- `DATABASE_PORT`: DB port
- `DRUPAL_SITE_NAME`: Drupal site name
- `DRUPAL_SITE_MAIL`: Drupal site mail address
- `DRUPAL_ACCOUNT_NAME`: Administrator username
- `DRUPAL_ACCOUNT_MAIL`: Administrator email
- `DRUPAL_ACCOUNT_PASS`: Administrator password

## Theme installation

```
php ps_console/console ps:theme:install
```

- `ps_console/themes.yml` Theme list。
- `ps_console/themes.yml.dist` Please set value referring to

## Change theme

```
php ps_console/console ps:theme:change
```

- `ps_console/config/system.theme.yml` It is necessary to arrange.
- `ps_console/config/system.theme.yml.dist` Please set the value referring to.
- The theme you specify must be installed in avilable.

## Show module list

```
php ps_console/console ps:module:list
```

- Show list of installed modules
- You can check validity/invalid module
- Internal run `vendor/bin/drupal debug:module` command

## Module installation

```
php ps_console/console ps:module:install
```

- `ps_console/modules.yml` Module list
- `ps_console/modules.yml.dist` Please set the value referring to.

## Import configuration

```
php ps_console/console ps:config:import
```

- Import all configuration to Drupal new site.



