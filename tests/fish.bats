#!/usr/bin/env bats

@test "[Fish] default script" {
  run symfony-autocomplete --shell=fish --disable-default-tools --aliases=acme

  echo "$output"

  [ "$status" -eq 0 ]

  diff <(echo "$output") $BATS_TEST_DIRNAME/fixtures/fish/default.txt
}

@test "[Fish] cached script" {
  run symfony-autocomplete --shell=fish acme

  echo "$output"

  [ "$status" -eq 0 ]

  diff <(echo "$output") $BATS_TEST_DIRNAME/fixtures/fish/cached.txt
}
