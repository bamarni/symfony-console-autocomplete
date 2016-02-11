_symfony() {
    local state cur

    cur=${words[${#words[@]}]}

    # lookup for command
    for word in ${words[@]:1}; do
        if [[ $word != -* ]]; then
            com=$word
            break
        fi
    done

    # completing for an option
    if [[ ${cur} == --* ]] ; then
        state="option"
    fi

    # completing for a command
    if [[ $cur == $com ]]; then
        state="command"
    fi

    case $state in
        command)
            local commands;
            commands=("${(@f)$(${words[1]} list --raw 2>/dev/null | sed -E 's/:/\\:/g' | sed -E 's/([^ ]+)[[:space:]]*(.*)/\1:\2/')}")
            _describe 'command' commands
        ;;
        option)
            local options;
            options=("${(@f)$(${words[1]} -h ${words[2]} --no-ansi 2>/dev/null | sed -n '/Options/,/^$/p' | sed -e '1d;$d' | sed 's/[^--]*\(--.*\)/\1/' | sed -En 's/[^ ]*(-(-[[:alnum:]]+){1,})[[:space:]]+(.*)/\1:\3/p' | awk '{$1=$1};1')}")
            _describe 'option' options
    esac
}

%%TOOLS%%
