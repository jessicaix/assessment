# How to build Vagrant + Ansible + Drupal

To install this Drupal version on local enviroment. Please do following steps.

## Vagrant

**INSTALL VAGRANT + ANSIBLE** (refering to : /BOX/README.md)

## Build Drupal site.

Login to Guest server by SSH:
```
vagrant ssh
```
Then, go to web root directory:
```
cd /home/httpd/app_01/drupal-site
```

Finaly, run following command that mentioned in **Build Drupal Site** (refering to : /drupal-site/ps_console/README.md)

Note: Let's clear Drupal DB cache.
```
php ps_console/console ps:cache:clear
```