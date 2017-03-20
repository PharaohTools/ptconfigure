#
### Ensure that the test tools exist. So, need to install PHP, PHPUnit and Behat
#composer require global phpunit
#composer require global behat#
#
### Ensure that Pharaoh Configure exists for Any Test
#git clone *configure repo* && php ptconfigure/install-silent#
#
### If its any PT Except for Configure That we're testing
## Ensure Pharaoh XX is installed...
#{{{ var::pharaoh_app }}} || sudo ptconfigure {{{ var::pharaoh_app }}} install -yg ;
## "Updating Virtualize to Cloned files..." ;
#git clone https://{{{ var::source_username }}}:{{{ var::source_password }}}@source.internal.pharaohtools.com/git/phpengine/pharaoh_{{{ var::pharaoh_app }}}
## "Copying Cloned Files to Pharaoh Virtualize Standard Directories..."
#sudo cp -r {{{ var::pharaoh_app }}}/* /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}/ ;##
#
### Execute Behat Tests
## Ensure the report directories exist for the behat tests
#mkdir -p /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}/reports/junit/behat ;
#mkdir -p /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}/reports/html/behat ;
## generate behat config yaml
#php  /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}/build/tests/behat/yaml-generator.php ;
## About to run Behat tests, must run from repo root, to use composer json for autoloading
#cd  /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}
#behat --config `pwd`/build/tests/behat/behat_gen.yml --suite=core_features -f junit -o `pwd`/../reports/junit/behat -f progress -o std ;
## Now convert the junit test output to html
#/usr/local/bin/junit-viewer --results= /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}/reports/junit/behat --save= /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}/reports/html/behat/index.html;#
#
### Execute PHP Unit Tests
## Ensure the report directories exist for the phpunit tests
#mkdir -p /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}/reports/junit/phpunit ;
#mkdir -p /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}/reports/phpunit/results ;
#mkdir -p /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}/reports/phpunit/coverage ;
#mkdir -p /opt/{{{ var::pharaoh_app }}}/{{{ var::pharaoh_app }}}/reports/html/phpunit/ ;
## About to run PHPUnit tests
#export PATH=~/.composer/vendor/bin:$PATH ;
#echo "--coverage-clover flag has been temporarily removed reports/phpunit/coverage/report.xml" ;
## Execute PHP Unit
#phpunit --configuration ptvirtualize/build/tests/phpunit/phpunit.xml --log-junit=reports/phpunit/results/output.xml --testsuite=modules ;
## Now convert the junit test output to html
#/usr/local/bin/junit-viewer --results=reports/phpunit/results --save=reports/html/phpunit/index.html ;
## Now convert the junit test coverage to html
#/usr/local/bin/junit-viewer --results=reports/phpunit/coverage --save=reports/html/phpunit/coverage.html ;
#


RunCommand install
  label "Stop this Autopilot if the run type parameter is not set"
  guess
  command "exit 1"
  not_when "{{{ Param::run_type }}}"

EnvironmentConfig configure
  label "Lets ensure an environment in our papyrusfile for our $$env_id_slug environment"
  guess true
  environment-name "$$env_id_slug"
  tmp-dir "/tmp/"
  keep-current-environments true
  no-manual-servers true
  add-single-environment true
  when "$$run_type_add"

Boxify box-ensure
  label "Ask $$current_cloud_provider API to create our Single Server node for our $$env_id_slug environment"
  guess true
  environment-name "$$env_id_slug"
  provider-name "$$current_cloud_provider"
  box-amount "$$current_cloud_box_amount"
  image-id "$$current_cloud_image_id"
  region-id "$$current_cloud_region_id"
  size-id "$$current_cloud_size_id"
  server-prefix ""
  box-user-name "root"
  key-path "KS::id_rsa"
  ssh-key-name "$$cloud_ssh_key_name"
  wait-for-box-info true
  wait-until-active true
  when "$$run_type_add"

Boxify box-destroy
  label "Ask Provider API to destroy our node"
  guess true
  destroy-all-boxes
  environment-name "$$env_id_slug"
  provider-name "$$current_cloud_provider"
  when "$$run_type_remove"

EnvironmentConfig del
  label "Lets remove our $$env_id_slug environment from our papyrusfile"
  guess true
  environment-name "$$env_id_slug"
  when "$$run_type_remove"
