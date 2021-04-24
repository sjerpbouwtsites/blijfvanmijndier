# scp / git might not leave ownership correct
sudo chown -R www-data ~/ifawnl_staging
sudo chgrp -R www-data ~/ifawnl_staging
# change .htaccess in public folder
sudo rm -rf ~/ifawnl_staging/public/.htaccess
sudo mv ~/ifawnl_staging/public/.htaccess-remote public/.htaccess
# make writeable for npm
sudo find ~/ifawnl_staging -type d -exec chmod 777 {} \;
sudo find ~/ifawnl_staging -type f -exec chmod 777 {} \;
# switch to node 10
sudo n 10
sudo chown -hR ubuntu /usr/local/lib/node_modules
# install packages
sudo npm install -g gulp@3.9.1;
mkdir /usr/local/lib/node_modules/node-sass
mkdir /usr/local/lib/node_modules/node-sass
/build
sudo npm install -g node-sass
sudo npm install bootstrap-sass@3.3.7;
sudo npm install jquery@3.1.0;
sudo npm install laravel-elixir@6.0.0-11;
sudo npm install laravel-elixir-vue-2@0.2.0;
sudo npm install laravel-elixir-webpack-official@1.0.2;
sudo npm install lodash@4.16.2;
sudo npm install vue@2.0.1;
sudo npm install vue-resource@1.0.3;
# use gulp
sudo gulp --production
# yeah that was weird'
sudo find ~/ifawnl_staging -type d -exec chmod 775 {} \;
sudo find ~/ifawnl_staging -type f -exec chmod 664 {} \;
#yes
echo "post migration done"