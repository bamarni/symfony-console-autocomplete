#!/bin/bash

_symfony()
{
    local cur prev script com opts
    COMPREPLY=()
    _get_comp_words_by_ref -n : cur prev words

    for word in ${words[@]:1}; do
        if [[ $word != -* ]]; then
            com=$word
            break
        fi
    done

    if [[ ${cur} == --* ]] ; then
        opts=${words[0]}
        [[ -n $com ]] && opts=$opts" -h "$com
        opts=$($opts --no-ansi 2>/dev/null | sed -n '/Options/,/^$/p' | sed -e '1d;$d' | sed -En 's/.*(-(-[[:alnum:]]+){1,}).*/\1/p'; exit ${PIPESTATUS[0]});
        [[ $? -eq 0 ]] || return 0;
        COMPREPLY=($(compgen -W "${opts}" -- ${cur}))
        __ltrim_colon_completions "$cur"

        return 0
    fi

    if [[ $cur == $com ]]; then
        coms=$(${words[0]} list --raw 2>/dev/null | sed -E 's/(([^ ]+ )).*/\1/'; exit ${PIPESTATUS[0]})
        [[ $? -eq 0 ]] || return 0;
        COMPREPLY=($(compgen -W "${coms}" -- ${cur}))
        __ltrim_colon_completions "$cur"

        return 0;
    fi
}

%%TOOLS%%
