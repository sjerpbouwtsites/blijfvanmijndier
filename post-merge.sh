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
npm install
# use gulp
sudo gulp --production
# yeah that was weird'
sudo find ~/ifawnl_staging -type d -exec chmod 775 {} \;
sudo find ~/ifawnl_staging -type f -exec chmod 664 {} \;
#yes
echo "post merge done"