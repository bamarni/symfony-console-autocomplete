#!/bin/bash

_symfony()
{
    local cur prev script
    COMPREPLY=()
    _get_comp_words_by_ref -n : cur prev words

    if [[ ${cur} == -* ]] ; then
        PHP=$(cat <<'HEREDOC'
array_shift($argv);
$script = array_shift($argv);
$command = '';
foreach ($argv as $v) {
    if (0 !== strpos($v, '-')) {
        $command = $v;
        break;
    }
}

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

        args=$(printf "%s " "${words[@]}")
        options=$($(which php) -r "$PHP" ${args});
        COMPREPLY=($(compgen -W "${options}" -- ${cur}))

        return 0
    fi

    commands=$(${words[0]} list --raw 2>/dev/null | sed -E 's/(([^ ]+ )).*/\1/')
    COMPREPLY=($(compgen -W "${commands}" -- ${cur}))

    return 0;
}

%%TOOLS%%
