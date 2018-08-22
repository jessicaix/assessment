# `vagrant_ansible_assessment`


## Overview

This project offers `Vagrantfile` and ansible playbook to build environment for ansible.


## Preparing vagrant plugins

```
vagrant plugin install dotenv
vagrant plugin install vagrant-proxyconf
vagrant plugin install vagrant-vbguest
```


## Box file

`centos/7` is used. If there is no Box file in local, it will be downloaded at initial setup (takes time).

When installing VirtualBox Guest Addition, sometimes installation will fail because of version inconsistency of `kernel-devel`. In this case, updating Box file may solve this problem.

```
vagrant box update
```

If problem is not solved by this command, login to created virtual machin ( `vagrant ssh` ) and manually update kernel packages.

```
sudo yum update kernel kernel-headers kernel-tools kernel-tools-libs
```


## .env

Create `.env` by coping `.env.example`, and adjust contents.

|key|description|note|
|---|---|---|
|`VB_PRIVATE_IP`|IP address of virtual machine. configure locally usable value.||
|`VB_MEMORY`|Memory size allocated to virtual machine. If no enough power in host machine, set "1024" etc.||
|`VB_CPU`|CPU core size allocated to virtual machine.||
|`VB_USE_PROXY`|Under proxy environment: "yes", otherwise: "no"||
|`VB_PROXY_HTTP`|Configure if `VB_USE_PROXY` is "yes".||
|`VB_PROXY_HTTPS`|Configure if `VB_USE_PROXY` is "yes".||
|`VB_PROXY_NO_PROXY`|Configure if `VB_USE_PROXY` is "yes".||
|`VB_ADDITIONAL_SYNCED_FOLDERS`|If you want to add additional general purpose synced folders: "yes", otherwise: "no" (see `Vagrantfile`)||
|`VB_COMPANY`|"gobear"|(1)|

### note(1)

- Playbook differs according to the value of `VB_COMPANY` ( `playbook.gobear.yml`).
- Variables included for `application` role differs according to playbook ( `vars/base_gobear.yml`).
- Domain in `httpd` config file differs ( `roles/application/templates/app_01.conf.j2` ).

### Example

```
VB_PRIVATE_IP = "192.168.33.112"
VB_MEMORY = "2048"
VB_CPU = "2"

VB_USE_PROXY = "yes"
VB_PROXY_HTTP  = "http://proxy.example.com:8080"
VB_PROXY_HTTPS = "http://proxy.example.com:8080"
VB_PROXY_NO_PROXY = "localhost,127.0.0.1"

VB_ADDITIONAL_SYNCED_FOLDERS = "yes"

VB_COMPANY = "gobear"
```


## Case when ansible failed and sshd was not restarted

The sshd config file rewrite task in common role calls sshd restart handler.

If ansible ends with failure, sshd may not be restarted.

If you can not login with SSH on 22 port, execute following command.

```
vagrant ssh
sudo systemctl restart sshd
```

## Config host on your local Window.
File: C:\Windows\System32\drivers\etc\host

Content: 192.168.33.112 local-assessment.gobear.com

## Reference

Ansible and Vagrant

https://www.vagrantup.com/docs/provisioning/ansible_intro.html

Ansible Local Provisioner

https://www.vagrantup.com/docs/provisioning/ansible_local.html
