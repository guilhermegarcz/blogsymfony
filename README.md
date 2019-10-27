# Symfony
Simple Blog using Symfony 4 framework

# Setup
```shell script
$ git clone git@github.com:guilhermegarcz/blogsymfony.git
$ cd blogsymfony
```
Update the .env file, you can either use .env.example (rename to .env and update the config) or create a new one with this base
```dotenv
APP_ENV=prod
APP_DEBUG=0

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=

DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db
```
### Note
If you put a google oauth key it wont allow you to access the admin area unless you are logged in with google oauth.
To login with google your email must be the same email as the account.

```shell script
$ yarn install
$ ./node_modules/.bin/encore production
$ composer install --no-dev --optimize-autoloader
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
$ php bin/console cache:clear
```

When Installed go to __WebsiteURL/install__ to create an admin account and generate some dummy articles
