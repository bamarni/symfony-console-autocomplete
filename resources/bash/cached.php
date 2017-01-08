_<?php echo $vars['script'] ?>()
{
    local cur script coms opts com
    COMPREPLY=()
    _get_comp_words_by_ref -n : cur words

    # for an alias, get the real script behind it
    if [[ $(type -t ${words[0]}) == "alias" ]]; then
        script=$(alias ${words[0]} | sed -E "s/alias ${words[0]}='(.*)'/\1/")
    else
        script=${words[0]}
    fi

    # lookup for command
    for word in ${words[@]:1}; do
        if [[ $word != -* ]]; then
            com=$word
            break
        fi
    done

    # completing for an option
    if [[ ${cur} == --* ]] ; then
        opts="<?php echo join(' ', $vars['options_global']) ?>"

        case "$com" in
<?php foreach ($vars['options_command'] as $command => $options): ?>

            <?php echo $command ?>)
            opts="${opts} <?php echo join(' ', array_diff($options, $vars['options_global'])) ?>"
            ;;
<?php endforeach; ?>

        esac

        COMPREPLY=($(compgen -W "${opts}" -- ${cur}))
        __ltrim_colon_completions "$cur"

        return 0;
    fi

    # completing for a command
    if [[ $cur == $com ]]; then
        coms="<?php echo join(' ', $vars['commands']) ?>"

        COMPREPLY=($(compgen -W "${coms}" -- ${cur}))
        __ltrim_colon_completions "$cur"

        return 0
    fi
}

<?php foreach ($vars['tools'] as $tool): ?>
complete -o default -F _<?php echo $vars['script'] ?> <?php echo $tool ?>

<?php endforeach; ?>
