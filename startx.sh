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

DIR="/subs"


echo $'Tcc V1.0'
echo "Escolha uma Opção do Menu :"
echo
echo "1 - Realizar Deploy"
echo "2 - Realizar Manutenção"
echo "3 - Startar containers"
echo "q - sair"
echo
read -p "Opção: " opcao
echo

# Opções de deploy
case "$opcao" in
   1)
        echo "Opcao 1 escolhida."
        (cd $DIR && sh Deploy.sh)
        sleep 3
        ;;
   2)
        echo "Opcao 2 escolhida."
        
        (sh docker-compose down -v)

        sleep 3
        ;;
   3)
        echo "Opcao 3 escolhida."
        echo "Reiniciando os Containers"
        (sh Restart.sh)
        sleep 3
        ;;

   q)
        echo "Saindo..."
        sleep 3
        exit 0
        ;;
   *)
        echo "Opção Inválida"
        exit 2
        ;;
esac