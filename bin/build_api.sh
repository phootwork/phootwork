#!/bin/bash

# Run the script only in the Travis job,
# corresponding to the minumim php version supported
if [ ${TRAVIS_PHP_VERSION} = "7.2" ]
then
  # Run the commands only when push on master branch
  if [ ${TRAVIS_BRANCH} = "master" ]
  then
    if [ ${TRAVIS_PULL_REQUEST} = "false" ]
    then
      wget http://get.sensiolabs.org/sami.phar
      git clone https://${GITHUB_TOKEN}@github.com/phootwork/phootwork.github.io docs
      php sami.phar update sami.php
      cd docs || exit
      git add ./
      git commit -m"Build api documentation via Travis build nr. ${TRAVIS_BUILD_NUMBER}"
      git push origin master
    fi
  fi
fi
