#compdef <?php echo $vars['script'] ?>


_<?php echo $vars['script'] ?>()
{
    local state com cur

    cur=${words[${#words[@]}]}

    # lookup for command
    for word in ${words[@]:1}; do
        if [[ $word != -* ]]; then
            com=$word
            break
        fi
    done

    if [[ ${cur} == --* ]]; then
        state="option"
        opts=(<?php echo join(' ', array_map(function($v) use ($helpers, $vars) { return $helpers['zsh_describe']($v, $vars['options_descriptions'][$v]); }, $vars['options_global'])) ?>)
    elif [[ $cur == $com ]]; then
        state="command"
        coms=(<?php echo join(' ', array_map(function($v) use ($helpers, $vars) { return $helpers['zsh_describe']($v, $vars['commands_descriptions'][$v]); }, $vars['commands'])) ?>)
    fi

    case $state in
        command)
            _describe 'command' coms
        ;;
        option)
            case "$com" in
<?php foreach ($vars['options_command'] as $command => $options): ?>

            <?php echo $command ?>)
            opts+=(<?php echo join(' ', array_map(function($v) use ($helpers, $vars, $command) { return $helpers['zsh_describe']($v, $vars['commands_options_descriptions'][$command][$v]); }, array_diff($options, $vars['options_global']))) ?>)
            ;;
<?php endforeach; ?>

            esac

            _describe 'option' opts
        ;;
        *)
            # fallback to file completion
            _arguments '*:file:_files'
    esac
}

<?php foreach ($vars['tools'] as $tool): ?>
compdef _<?php echo $vars['script'] ?> <?php echo $tool ?>

<?php endforeach; ?>
