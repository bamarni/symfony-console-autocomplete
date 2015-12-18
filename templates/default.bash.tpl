#!/bin/bash

_symfony()
{
    local cur prev script command
    COMPREPLY=()
    _get_comp_words_by_ref -n : cur prev words

    for word in ${words[@]}
    do
        if [[ $word != -* && ${words[0]} != $word ]]; then
            command=$word
            break
        fi
    done

    if [[ ${cur} == --* ]] ; then
        PHP=$(cat <<'HEREDOC'
array_shift($argv);
$script = array_shift($argv);
$command = array_shift($argv);

$xmlHelp = shell_exec($script.' help --format=xml '.$command.' 2>/dev/null');
$options = array();
if (!$xml = @simplexml_load_string($xmlHelp)) {
    exit(0);
}
foreach ($xml->xpath('/command/options/option') as $option) {
    $options[] = (string) $option['name'];
}

echo implode(' ', $options);
HEREDOC
)

        options=$($(which php) -r "$PHP" ${words[0]} "${command}");
        COMPREPLY=($(compgen -W "${options}" -- ${cur}))
        __ltrim_colon_completions "$cur"

        return 0
    fi

    if [[ $cur == $command ]]; then
        commands=$(${words[0]} list --raw 2>/dev/null | sed -E 's/(([^ ]+ )).*/\1/')
        COMPREPLY=($(compgen -W "${commands}" -- ${cur}))
        __ltrim_colon_completions "$cur"

        return 0;
    fi

}

%%TOOLS%%
