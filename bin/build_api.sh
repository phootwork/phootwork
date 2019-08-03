#!/bin/bash

#Run the commands only when push on master branch
if [ ${TRAVIS_BRANCH} = "master" ]
then
 if [ ${TRAVIS_PULL_REQUEST} = "false" ]
 then
    git clone https://${GITHUB_TOKEN}@github.com/phootwork/phootwork.github.io docs
    php sami.phar update sami.php
    cd docs
    git add ./
    git commit -m"Build api documentation via Travis build nr. ${TRAVIS_BUILD_NUMBER}"
    git push origin master
 fi
fi
