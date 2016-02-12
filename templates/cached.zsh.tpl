#!/usr/bin/env zsh

_%%SCRIPT%%()
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
        opts=(%%SHARED_OPTIONS%%)
    elif [[ $cur == $com ]]; then
        state="command"
        coms=(%%COMMANDS%%)
    fi

    case $state in
        command)
            _describe 'command' coms
        ;;
        option)
            case "$com" in
            %%SWITCH_CONTENT%%
            esac

            _describe 'option' opts
        ;;
        *)
            # fallback to file completion
            _arguments '*:file:_files'
    esac
}

%%TOOLS%%
