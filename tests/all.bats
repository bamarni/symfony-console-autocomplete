#!/usr/bin/env bats

@test "[All] fail for unsupported shells" {
  run symfony-autocomplete --shell=salmon

  echo "$output"

  [[ "$output" == *"Completion is only available for Bash and Zsh"* ]] || false

  [ "$status" -ne 0 ]
}
