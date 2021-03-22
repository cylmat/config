#!/bin/bash

############
# GIT PROMPT
# https://thucnc.medium.com/how-to-show-current-git-branch-with-colors-in-bash-prompt-380d05a24745
############

# https://raw.githubusercontent.com/git/git/master/contrib/completion/git-completion.bash
# https://github.com/git/git/blob/master/contrib/completion/git-prompt.sh

### CHANGE PROMPT
if [[ ! -f ~/prompt/git-prompt.sh ]] || [[ ! -f ~/prompt/git-completion.bash ]]
then
  echo "git prompt or completion not found"
  return 1
fi

source ~/prompt/git-completion.bash
source ~/prompt/git-prompt.sh

parse_git_branch() {
    git branch 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/(\1)/'
}

export PS1="\u@\h \[\e[32m\]\w \[\e[91m\]\$(parse_git_branch)\[\e[00m\]$ "
