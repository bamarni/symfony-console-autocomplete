# Symfony console autocomplete

Enables bash autocompletion for tools based on the
[Symfony2 Console](http://symfony.com/doc/master/components/console/introduction.html)
component.

## Installation

Install the tool globally with Composer :

    composer global require bamarni/symfony-console-autocomplete

## Simple usage

The following configuration works out of the box for Symfony, Composer, PHPSpec, etc.

Just add the following line to your `~/.bash_profile` :

```
eval "$(symfony-autocomplete)"
```

Logout / login from your terminal and you're ready to go!

## Specific usage

The simple usage needs to hook on the fly to inspect the tool commands / options,
it should be fast enough but if for some reason you want to dump a static version,
you can pass the tool name as an argument :

    symfony-autocomplete composer

This will print the completion script for Composer to stdout. The output should be saved
at a specific location depending on your OS / setup. Here are a few examples :

**Ubuntu / Debian**

    symfony-autocomplete composer | sudo tee /etc/bash_completion.d/composer

**Mac OSX (with Homebrew "bash-completion")**

    symfony-autocomplete composer > $(brew --prefix)/etc/bash_completion.d/composer

## Supported tools

All tools using the Symfony Console component are supported,
here is a non-exhaustive list :

* composer
* php-cs-fixer
* behat
* phpspec
