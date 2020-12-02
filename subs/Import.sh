#!/bin/bash

#################################################################
# Script de Importação de banco mysql dockerizado
# Não utilize no windows                                                                                                    
# Autor: Erik Tonon                                                             
# Data: 15/01/2020                                                              
# Descrição: Script utilizado para facilitar atualização de database
#         
# uso: bash import.sh   ou sh import.sh                                                  
#                                                                               
#################################################################

docker exec mysql-tcc mysql -u tcc -p tcc tcc < database.sql