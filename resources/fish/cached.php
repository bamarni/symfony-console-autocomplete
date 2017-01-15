function __fish_<?php echo $vars['script'] ?>_no_subcommand
    for i in (commandline -opc)
        if contains -- $i <?php echo join(' ', $vars['commands']) ?>

            return 1
        end
    end
    return 0
end

# global options
<?php foreach ($vars['options_global'] as $option): ?>
complete -c <?php echo $vars['script'] ?> -n '__fish_<?php echo $vars['script'] ?>_no_subcommand' -l <?php echo substr($option, 2) ?> -d '<?php echo str_replace("'","\\'", $vars['options_descriptions'][$option]) ?>'
<?php endforeach; ?>

# commands
<?php foreach ($vars['commands'] as $command): ?>
complete -c <?php echo $vars['script'] ?> -f -n '__fish_<?php echo $vars['script'] ?>_no_subcommand' -a <?php echo $command ?> -d '<?php echo str_replace("'","\\'", $vars['commands_descriptions'][$command]) ?>'
<?php endforeach; ?>

# command options
<?php foreach ($vars['options_command'] as $command => $options): ?>

# <?php echo $command ?>

<?php foreach (array_diff($options, $vars['options_global']) as $option): ?>
complete -c <?php echo $vars['script'] ?> -A -n '__fish_seen_subcommand_from <?php echo $command ?>' -l <?php echo substr($option, 2) ?> -d '<?php echo str_replace("'","\\'", $vars['commands_options_descriptions'][$command][$option]) ?>'
<?php endforeach; ?>
<?php endforeach; ?>
