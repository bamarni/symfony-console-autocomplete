_symfony() {
    local curcontext="$curcontext" state line
    typeset -A opt_args

    _arguments \
        '1: :->command'\
        '*: :->option'

    case $state in
        command)
            local commands;
            commands=("${(@f)$(${words[1]} list --raw 2>/dev/null | sed -E 's/([^ ]+)[[:space:]]*(.*)/\1:\2/')}")
            _describe 'command' commands
        ;;
        *)
            local options;
            options=("${(@f)$(${words[1]} -h ${words[2]} --no-ansi 2>/dev/null | sed -n '/Options/,/^$/p' | sed -e '1d;$d' | sed -En 's/.*(-(-[[:alnum:]]+){1,}).*/\1/p')}")
            _describe 'option' options
    esac
}

%%TOOLS%%
