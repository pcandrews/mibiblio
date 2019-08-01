#!/bin/bash
# '$1' sirve para introducir por la consola el nombre.
# /git-push.sh 'este es un commit'
git add . &&
git commit -m "$1" &&
git push -u origin master
