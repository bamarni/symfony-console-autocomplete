# Symfony console autocomplete

Enables bash autocompletion for scripts based on the
[Symfony2 Console](http://symfony.com/doc/master/components/console/introduction.html)
component.

## Installation

You can use composer to install Symfony console autocomplete:

    composer create-project bamarni/symfony-console-autocomplete -s dev

## Example: Composer autocompletion

If you want to have autocompletion on [Composer](http://getcomposer.org/)
commands and options, you can follow these steps:

    cd symfony-console-autocomplete
    php bin/autocomplete dump composer > composer
    sudo mv composer /etc/bash_completion.d/
    cd ~
    source .bashrc
    composer dum[TAB]

## Further documentation

You can find more documentation at the following links:

* [copyright and MIT license](LICENSE.md)
