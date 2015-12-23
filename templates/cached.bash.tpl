#!/usr/bin/env bash
_%%SCRIPT%%()
{
    local cur prev script coms opts com
    COMPREPLY=()
    _get_comp_words_by_ref -n : cur prev words
    coms="%%COMMANDS%%"
    opts="%%SHARED_OPTIONS%%"

    for word in ${words[@]:1}; do
        if [[ $word != -* ]]; then
            com=$word
            break
        fi
    done

    if [[ ${cur} == --* ]] ; then
        case "$com" in
        %%SWITCH_CONTENT%%
        esac

        COMPREPLY=($(compgen -W "${opts}" -- ${cur}))
        __ltrim_colon_completions "$cur"

        return 0;
    fi

    if [[ $cur == $com ]]; then
        COMPREPLY=($(compgen -W "${coms}" -- ${cur}))
        __ltrim_colon_completions "$cur"

        return 0
    fi
}

complete -o default -F _%%SCRIPT%% %%SCRIPT%%
