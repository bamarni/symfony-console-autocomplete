#!/usr/bin/env bats

@test "[Bash] default script" {
  run symfony-autocomplete --shell=bash

  echo "$output"

  [ "$status" -eq 0 ]

  diff <(echo "$output") $BATS_TEST_DIRNAME/fixtures/bash/default.txt
}

@test "[Bash] cached script" {
  run symfony-autocomplete --shell=bash acme

  echo "$output"

  [ $status -eq 0 ]

  diff <(echo "$output") $BATS_TEST_DIRNAME/fixtures/bash/cached.txt
}

@test "[Bash] cached script with alias" {
  run symfony-autocomplete --shell=bash --aliases=tnt acme

  echo "$output"

  [ $status -eq 0 ]

  diff <(echo "$output") <(cat $BATS_TEST_DIRNAME/fixtures/bash/cached.txt; echo "complete -o default -F _acme tnt")
}
