#!/usr/bin/env bats

@test "[Zsh] default script" {
  run symfony-autocomplete --shell=zsh --disable-default-tools --aliases=acme

  echo "$output"

  [ "$status" -eq 0 ]

  diff <(echo "$output") $BATS_TEST_DIRNAME/fixtures/zsh/default.txt
}

@test "[Zsh] cached script" {
  run symfony-autocomplete --shell=zsh acme

  echo "$output"

  [ "$status" -eq 0 ]

  diff <(echo "$output") $BATS_TEST_DIRNAME/fixtures/zsh/cached.txt
}

@test "[Zsh] cached script with extra tool" {
  run symfony-autocomplete --shell=zsh --aliases=tnt acme

  echo "$output"

  [ "$status" -eq 0 ]

  diff <(echo "$output") <(cat $BATS_TEST_DIRNAME/fixtures/zsh/cached.txt; echo "compdef _acme tnt")
}
