# scp / git might not leave ownership correct
sudo chown -R www-data ~/ifawnl_staging
sudo chgrp -R www-data ~/ifawnl_staging
# change .htaccess in public folder
sudo rm -rf ~/ifawnl_staging/public/.htaccess
sudo mv ~/ifawnl_staging/public/.htaccess-remote public/.htaccess
# make writeable for npm
sudo find ~/ifawnl_staging -type d -exec chmod 775 {} \;
sudo find ~/ifawnl_staging -type f -exec chmod 664 {} \;
# switch to node 10
sudo ~/.nvm/versions/node/v10.24.1/bin/n 10
# use gulp
sudo /home/ubuntu/.nvm/versions/node/v10.24.1/bin/gulp --production
#yes
echo "post merge done"