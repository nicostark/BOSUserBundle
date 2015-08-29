# BOSUserBundle
This a simple Symfony2 bundle to handle basic user management. PHP 5.5.0 as minimum is needed.

# Important
Notice that this bundle it's on alpha stage. It's currently working, but improvements are needed.

# What does it do?
This bundle will allow you to create users, update them, handle logins and logouts, and restrict URLs to only logged in users with a single annotation.

# Why is it so simple?
I needed a nice way to handle my users and i tried to install FOSUserBundle. Although it's a great development, i found, as many other people, that they tried to make it "too much" adaptable. At the point that it's no longer friendly code.

# Installation
- First of all, add the package to composer: "composer require buddiesofsymfony/user-bundle"
- Once you installed it, run "./composer.phar update" to download the bundle.

# Configuration
This bundle requires a single parameter to work, no routing needed, no services needed.
- Add "bos_login_name" parameter to your parameters.yml. This must be the route name you wish to redirect the user when it tries to access an address that requires login. Example: bos_login_name: _login_page
