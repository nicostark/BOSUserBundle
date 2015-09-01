# BuddiesOfSymfony/UserBundle
This a simple Symfony2 bundle to handle basic user management. PHP 5.5.0 as minimum is needed.

# Important
Notice that this bundle it's on alpha stage. It's currently working, but improvements are needed.

# Before you start
If you find any bugs or have troubles installing the bundle, [open a issue](https://github.com/nicostark/BOSUserBundle/issues) and i'll try to fix it ASAP. 
This way we all win.

# What does it do?
This bundle will allow you to create users, update them, handle logins and logouts, and restrict URLs to only logged in users with a single annotation.

# Why is it so simple?
I needed a nice way to handle my users and i tried to install FOSUserBundle. Although it's a great development, i found, as many other people, that they tried to make it "too much" adaptable. At the point that it's no longer friendly to the programmer.

# Installation
- First of all, add the package to your composer.json. In your project folder run (on the console): 
 
`composer require buddiesofsymfony/user-bundle`

- Once you saved it, run "./composer.phar update" to download the bundle.

# Use and configuration
Please [check the wiki section](https://github.com/nicostark/BOSUserBundle/wiki). Don't worry, it's very very simple.
