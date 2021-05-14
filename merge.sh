# save the .env
sudo mv ~/ifawnl_staging/.env ~/.env-staging-temp
# stash away earlier postfixes
git stash
# pull current work
git pull
# scp / git might not leave ownership correct
sudo chown -R www-data ~/ifawnl_staging
sudo chgrp -R www-data ~/ifawnl_staging
echo "chowned and chgrped"
# change .htaccess in public folder
sudo rm -rf ~/ifawnl_staging/public/.htaccess
sudo cp ~/ifawnl_staging/public/.htaccess-remote public/.htaccess
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
sudo gulp --watch
echo "ran gulp"
# yeah that was weird'
sudo find ~/ifawnl_staging -type d -exec chmod 775 {} \;
sudo find ~/ifawnl_staging -type f -exec chmod 664 {} \;
echo "fixed permissions to safe setting"
# retrieve
sudo mv ~/.env-staging-temp ~/ifawnl_staging/.env
#yes
echo "post merge done"