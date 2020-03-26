# Bakery Module



Run it like this

```
ptconfigure auto x --af=multi-os-install.dsl.yml --vars=vars_ubuntu_14.04.6.php,vars_all.php --step-times --step-numbers
```



In single OS's, which works better for now...

```
# ptconfigure auto x --af=single-os-install.dsl.yml --vars="vars_all.php,vars_ubuntu_18.04.2.php" --step-times=true --step-numbers=true --os_slug="ubuntu_18.04.2"
# ptconfigure auto x --af=single-os-install.dsl.yml --vars="vars_all.php,vars_ubuntu_18.04.3.php" --step-times=true --step-numbers=true --os_slug="ubuntu_18.04.3"
ptconfigure auto x --af=single-os-install.dsl.yml --vars="vars_all.php,vars_ubuntu_18.04.4.php" --step-times=true --step-numbers=true --os_slug="ubuntu_18.04.4"
```