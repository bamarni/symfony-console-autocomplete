# Symfony console autocomplete

[![Build Status](https://img.shields.io/travis/bamarni/symfony-console-autocomplete.svg?style=for-the-badge&logo=travis)](https://travis-ci.org/bamarni/symfony-console-autocomplete)
[![GitHub issues](https://img.shields.io/github/issues/bamarni/symfony-console-autocomplete.svg?style=for-the-badge&logo=github)](https://github.com/bamarni/symfony-console-autocomplete/issues)

[![PHP Version](https://img.shields.io/packagist/php-v/bamarni/symfony-console-autocomplete.svg?style=for-the-badge)](https://github.com/bamarni/symfony-console-autocomplete)
[![Stable Version](https://img.shields.io/packagist/v/bamarni/symfony-console-autocomplete.svg?style=for-the-badge&label=Latest)](https://packagist.org/packages/bamarni/symfony-console-autocomplete)

[![Total Downloads](https://img.shields.io/packagist/dt/bamarni/symfony-console-autocomplete.svg?style=for-the-badge&label=Total+downloads)](https://packagist.org/packages/bamarni/symfony-console-autocomplete)
[![Monthly Downloads](https://img.shields.io/packagist/dm/bamarni/symfony-console-autocomplete.svg?style=for-the-badge&label=Monthly+downloads)](https://packagist.org/packages/bamarni/symfony-console-autocomplete)
[![Daily Downloads](https://img.shields.io/packagist/dd/bamarni/symfony-console-autocomplete.svg?style=for-the-badge&label=Daily+downloads)](https://packagist.org/packages/bamarni/symfony-console-autocomplete)
[![Packagist Stars](https://img.shields.io/packagist/stars/bamarni/symfony-console-autocomplete?style=for-the-badge)](https://github.com/bamarni/symfony-console-autocomplete)

Enables shell autocompletion for tools based on the
[Symfony Console](http://symfony.com/doc/master/components/console/introduction.html)
(Symfony framework, Composer, PHPSpec, Behat, etc.)

<img src="https://cloud.githubusercontent.com/assets/1205386/12221229/ecbda408-b791-11e5-8b2f-524763250a53.png" />

## Prerequisites

* Make sure the global composer project is configured properly with your shell.
Notably, the composer global bin directory needs to be in your path.
See https://getcomposer.org/doc/03-cli.md#global for instructions on how todo that.
* If you're using bash, you'll have to make sure [programmable completion functions](https://github.com/scop/bash-completion) are available. Linux distributions usually ship it and enable it by default. On Mac OSX, you can install it with brew (`brew install bash-completion`) and enable it by adding `source $(brew --prefix)/etc/bash_completion` at the end of your `.bashrc`.

## Installation

Install the tool globally with Composer :

    composer global require bamarni/symfony-console-autocomplete

## Quick setup

Add the following line at the end of your shell configuration file (`~/.bash_profile` or `~/.zshrc`) :

    eval "$(symfony-autocomplete)"

Close / re-open your terminal window and you're ready to go!

## Static setup

If you don't like all the magic from the quick setup and want to go with a more standard way,
you can dump a static completion file for a given tool :

    symfony-autocomplete composer

This will print the completion script for Composer to stdout. The output should be saved
at a specific location depending on your OS / setup. Here are a few examples :

    # BASH - Ubuntu / Debian
    symfony-autocomplete composer | sudo tee /etc/bash_completion.d/composer

    # BASH - Mac OSX (with Homebrew "bash-completion")
    symfony-autocomplete composer > $(brew --prefix)/etc/bash_completion.d/composer

    # ZSH - Config file
    symfony-autocomplete composer > ~/.composer_completion && echo "source ~/.composer_completion" >> ~/.zshrc

    # FISH
    symfony-autocomplete composer --shell=fish > ~/.config/fish/completions/composer.fish

If you are running an environment that does not have automatic execution of PHP scripts, then you will need to
call PHP and the script in question :

    symfony-autocomplete "php ./artisan"

## General tips

### Update

To update the tool to a new version use :

    composer global update bamarni/symfony-console-autocomplete

### Symfony framework completion

In order to get completion running, you shouldn't prepend `php` at the begining of the command :

    app/console [TAB]

## Tips for Bash users

### Alias support

By default, completion for your aliases won't be enabled. If you're using aliases
(eg. "c" for "composer", "pspec" for "phpspec", etc.), you have to pass them explicitly :

    symfony-autocomplete --aliases=c --aliases=pspec

## Tips for Docker users

### Defining the `SHELL` environment variable

If you connect to your container using something similar to `docker exec -it container bash` then
you may find that the completions cannot be built due to an inability to locate the `SHELL`
environment variable. This has been reported in https://github.com/bamarni/symfony-console-autocomplete/issues/32

A solution is to supply the `SHELL` environment variable as part of the `docker exec` command:

    docker exec -e SHELL=bash -it container bash

## Supported tools

All tools using the Symfony Console component are supported,
here is a non-exhaustive list :

* composer
* php-cs-fixer
* behat
* phpspec
* robo
* deployer
* laravel's artisan
* roadiz
* magento 2 console
