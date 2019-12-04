#!/bin/bash

# Run the script only in the Travis job,
# corresponding to the minumim php version supported
if [ ${TRAVIS_PHP_VERSION} = "7.2" ]
then
  # Run the commands only when push on master branch or when a new tag is added
  if [ ${TRAVIS_BRANCH} = "master" ] || [ ${TRAVIS_BRANCH} = ${TRAVIS_TAG} ]
  then
    if [ ${TRAVIS_PULL_REQUEST} = "false" ]
    then
      git clone https://github.com/cristianoc72/monorepo-tools
      git remote add dep_collection https://${GITHUB_TOKEN}@github.com/phootwork/collection
      git remote add dep_file https://${GITHUB_TOKEN}@github.com/phootwork/file
      git remote add dep_json https://${GITHUB_TOKEN}@github.com/phootwork/json
      git remote add dep_lang https://${GITHUB_TOKEN}@github.com/phootwork/lang
      git remote add dep_tokenizer https://${GITHUB_TOKEN}@github.com/phootwork/tokenizer
      git remote add dep_xml https://${GITHUB_TOKEN}@github.com/phootwork/xml
      monorepo-tools/monorepo_split.sh dep_collection:src/collection dep_file:src/file dep_json:src/json dep_lang:src/lang dep_tokenizer:src/tokenizer dep_xml:src/xml
    fi
  fi
fi