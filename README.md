<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">News catalog API based on Yii 2 Advanced </h1>
    <br>
</p>


LINKS 
-------------------
```
POSTS

GET /api/posts - query params: category_id, title
POST /api/posts - request body: title, content, slug, category_id: []
UPDATE /api/posts/:id - request body: title, content, slug, category_id: []
DELETE /api/posts/:id


CATEGORIES

GET /api/categories - all categories
GET /api/get-tree - categories tree
POST /api/categories - request body: title, slug, parent_id
UPDATE /api/categories/:id - request body: title, slug, parent_id
DELETE /api/categories/:id
```

INSTALLATION 
-------------------
```
1) clone repo
2) go to project root and run  "php init"
3) Create a new database and adjust the components['db'] configuration in common/config/main-local.php accordingly.
4) Open a console terminal, apply migrations with command /path/to/php-bin/php /path/to/yii-application/yii migrate.

In your server config set document root the root of project directory for all(api|backend|frontend) apps
For Apache it could be the following:

 <VirtualHost *:80>
    ServerName sitename.loc
    ServerAlias www.sitename.loc
    DocumentRoot "/path/to/sitename.loc/"
       
    <Directory "/path/to/sitename.loc/">
        AllowOverride All
    </Directory>
</VirtualHost>

```

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
api
    actions/             contains api custom actions 
    config/              contains api configurations
    controllers/         contains Web controller classes
    models/              contains api-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    web/                 contains the entry script and Web resources
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```
