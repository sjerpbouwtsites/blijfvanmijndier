# scp / git might not leave ownership correct
sudo chown -R www-data ~/ifawnl_staging
sudo chgrp -R www-data ~/ifawnl_staging
echo "chowned and chgrped"
# change .htaccess in public folder
sudo rm -rf ~/ifawnl_staging/public/.htaccess
sudo mv ~/ifawnl_staging/public/.htaccess-remote public/.htaccess
echo "fixed htaccess"
# make writeable for npm
sudo find ~/ifawnl_staging -type d -exec chmod 777 {} \;
sudo find ~/ifawnl_staging -type f -exec chmod 777 {} \;
echo "created unsafe permissions"
# switch to node 10
sudo n 10
npm install
echo "npm installed"
# use gulp
sudo gulp --production
echo "ran gulp"
# yeah that was weird'
sudo find ~/ifawnl_staging -type d -exec chmod 775 {} \;
sudo find ~/ifawnl_staging -type f -exec chmod 664 {} \;
echo "fixed permissions to safe setting"
#yes
echo "post merge done"