# Bakery Module



Run it like this

In single OS's, which works better for now...

```
ptconfigure auto x --af=single-os-install.dsl.yml --vars="vars_all.php,vars_ubuntu_18.04.4.php" --step-times=true --step-numbers=true --os_slug="ubuntu_18.04.4"
# ptconfigure auto x --af=single-os-install.dsl.yml --vars="vars_all.php,vars_ubuntu_18.04.2.php" --step-times=true --step-numbers=true --os_slug="ubuntu_18.04.2"
# ptconfigure auto x --af=single-os-install.dsl.yml --vars="vars_all.php,vars_ubuntu_18.04.3.php" --step-times=true --step-numbers=true --os_slug="ubuntu_18.04.3"
```


Single in parallel should work fine if you've got resources
```
ptconfigure parallax -yg \
 --command-1='ptconfigure auto x --af=single-os-install.dsl.yml --vars=vars_all.php,vars_ubuntu_18.04.2.php --step-times --step-numbers' \
 --command-2='ptconfigure auto x --af=single-os-install.dsl.yml --vars=vars_all.php,vars_ubuntu_18.04.3.php --step-times --step-numbers' \
 --command-3='ptconfigure auto x --af=single-os-install.dsl.yml --vars=vars_all.php,vars_ubuntu_18.04.4.php --step-times --step-numbers' 
```

Or sequential Multi OSs, the yaml seems right but something underlying doesnt work

```
ptconfigure auto x --af=multi-os-install.dsl.yml --vars=vars_ubuntu_14.04.6.php,vars_all.php --step-times --step-numbers
```

