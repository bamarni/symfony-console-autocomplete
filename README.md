# Symfony console autocomplete

Enables bash autocompletion for tools based on the
[Symfony2 Console](http://symfony.com/doc/master/components/console/introduction.html)
component.

## Installation

Install the tool globally with Composer :

    composer global require bamarni/symfony-console-autocomplete

## Usage example : Composer completion

If you want to generate autocompletion for [Composer](http://getcomposer.org/)
commands and options, you can run the following command :

    symfony-autocomplete composer

It will print the completion script to stdout, the output should be saved at a specific
location depending on your OS / setup. Here are a few examples :

**Ubuntu / Debian**

    symfony-autocomplete composer | sudo tee /etc/bash_completion.d/composer

**Mac OSX (with Homebrew "bash-completion")**

    symfony-autocomplete composer > $(brew --prefix)/etc/bash_completion.d/composer

## Usage example : Symfony framework completion

Symfony framework console is not a global tool, each Symfony project will likely have its own
variety of different commands. As such, the completion script shouldn't be installed globally.

Here is a working setup example for Symfony 3, it adds a script in `composer.json` :

    "scripts": {
        "...",
        "post-update-cmd": [
            "...",
            "@autocomplete"
        ],
        "autocomplete": "symfony-autocomplete bin/console --script-options=\"--no-debug\" > .autocomplete.bash"
    },

The completion script can then be generated with `composer autocomplete`. It will also be regenerated automatically
when vendors are updated.

Finally, run this shell command to source the generated script : `. .autocomplete.bash`.

### Tip

Instead of sourcing the generated script manually, you could use [direnv](http://direnv.net/),
with the following `.envrc` at the root of your Symfony project :

    [[ -f autocomplete.bash ]] && source .autocomplete.bash

Now, each time you'll enter into your Symfony project, completion will be loaded automatically.

## Supported tools

All tools using the Symfony Console component are supported,
here is a non-exhaustive list :

* composer
* php-cs-fixer
* behat
* phpspec
