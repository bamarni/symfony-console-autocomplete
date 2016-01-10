# Symfony console autocomplete

Enables shell autocompletion for tools based on the
[Symfony2 Console](http://symfony.com/doc/master/components/console/introduction.html) 
(Symfony framework, Composer, PHPSpec, Behat, etc.)

<img src="https://cloud.githubusercontent.com/assets/1205386/12221229/ecbda408-b791-11e5-8b2f-524763250a53.png" />

## Installation

Install the tool globally with Composer :

    composer global require bamarni/symfony-console-autocomplete

## Quick setup (recommended)

Add the following line at the end of your shell configuration file (`~/.bash_profile` or `~/.zshrc`) :

```
eval "$(symfony-autocomplete)"
```

Close / re-open your terminal window and you're ready to go!

## Static setup

If you don't like all the magic from the quick setup and want to go with a more standard way,
you can dump a static completion file for a given tool :

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
