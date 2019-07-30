#!/bin/bash
# '$1' sirve para introducir por la consola el nombre.
# Sube los cambios a git
# Para usar espacios en el commit usar comillas simples
# Sino, no dejar espacios
git add . &&
git commit -m "$1" &&
git push -u origin master
