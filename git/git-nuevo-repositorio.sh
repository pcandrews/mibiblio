#!/bin/bash
# '$1' sirve para introducir por la consola el nombre.
# Situarse en la carpeta del proyecto.
# Añadir nombre del proyecto, si lleva espacios escribir el nombre entre comillas simples

git init  &&
git remote add origin git@github.com:pcandrews/$1.git