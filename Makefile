.DEFAULT_GOAL := help

# Reset
Color_Off='\033[0m'       # Text Reset

# Regular Colors
Black='\033[0;30m'        # Black
Red='\033[0;31m'          # Red
Green='\033[0;32m'        # Green
Yellow='\033[0;33m'       # Yellow
Blue='\033[0;34m'         # Blue
Purple='\033[0;35m'       # Purple
Cyan='\033[0;36m'         # Cyan
White='\033[0;37m'        # White

# Bold
BBlack='\033[1;30m'       # Black
BRed='\033[1;31m'         # Red
BGreen='\033[1;32m'       # Green
BYellow='\033[1;33m'      # Yellow
BBlue='\033[1;34m'        # Blue
BPurple='\033[1;35m'      # Purple
BCyan='\033[1;36m'        # Cyan
BWhite='\033[1;37m'       # White

# Underline
UBlack='\033[4;30m'       # Black
URed='\033[4;31m'         # Red
UGreen='\033[4;32m'       # Green
UYellow='\033[4;33m'      # Yellow
UBlue='\033[4;34m'        # Blue
UPurple='\033[4;35m'      # Purple
UCyan='\033[4;36m'        # Cyan
UWhite='\033[4;37m'       # White

# Background
On_Black='\033[40m'       # Black
On_Red='\033[41m'         # Red
On_Green='\033[42m'       # Green
On_Yellow='\033[43m'      # Yellow
On_Blue='\033[44m'        # Blue
On_Purple='\033[45m'      # Purple
On_Cyan='\033[46m'        # Cyan
On_White='\033[47m'       # White

# High Intensity
IBlack='\033[0;90m'       # Black
IRed='\033[0;91m'         # Red
IGreen='\033[0;92m'       # Green
IYellow='\033[0;93m'      # Yellow
IBlue='\033[0;94m'        # Blue
IPurple='\033[0;95m'      # Purple
ICyan='\033[0;96m'        # Cyan
IWhite='\033[0;97m'       # White

# Bold High Intensity
BIBlack='\033[1;90m'      # Black
BIRed='\033[1;91m'        # Red
BIGreen='\033[1;92m'      # Green
BIYellow='\033[1;93m'     # Yellow
BIBlue='\033[1;94m'       # Blue
BIPurple='\033[1;95m'     # Purple
BICyan='\033[1;96m'       # Cyan
BIWhite='\033[1;97m'      # White

# High Intensity backgrounds
On_IBlack='\033[0;100m'   # Black
On_IRed='\033[0;101m'     # Red
On_IGreen='\033[0;102m'   # Green
On_IYellow='\033[0;103m'  # Yellow
On_IBlue='\033[0;104m'    # Blue
On_IPurple='\033[0;105m'  # Purple
On_ICyan='\033[0;106m'    # Cyan
On_IWhite='\033[0;107m'   # White

#ifneq (,$(wildcard ./.env))
#    include .env
#    export
#    ENV_FILE_PARAM = --env-file .env
#endif

VAR=ps

install: 	## First Install project
	make init
	command cd ./docker && docker-compose build --no-cache
	command cd ./docker && docker-compose up -d
	make vendor_install
	make migrate
	make server_info
	@echo ${Green}"Project successfully installed!"

init:	## Initialize Project
	cp .env.example .env
	cd ./docker && cp .env.example .env
	@echo ${Green}"Project Initialized!"${NC}

ps:		## Show 'processing' project Docker containers
	command cd ./docker && docker-compose ps ${service}
	@echo ${Green}"Docker Processing containers!"

up: 	## Upping project
	command cd ./docker && docker-compose up -d ${service}
	@echo ${Green}"Project successfully upped!"${NC}

up_build:	## Build upping project
	command cd ./docker && docker-compose up --build -d
	@echo ${Green}"Project Built and successfully upped!"${NC}

restart:	## Restarting project
	command cd ./docker && docker-compose restart ${service}
	@echo ${Green}"Project successfully restarted!"${NC}

down:		## Downing & Stopping project
	command cd ./docker && docker-compose down ${service}
	@echo ${Green}"Project successfully downed!"${NC}

bash:		## Exec Docker 'php_fpm' interactive bash
	command cd ./docker && docker-compose exec php_fpm bash
	@echo ${Green}"PHP FPM bash!"${NC}

vendor_install:		## Install project dependencies
	command cd ./docker && docker-compose exec php_fpm composer install --ignore-platform-reqs
	command cd ./docker && docker-compose exec php_fpm ./artisan key:generate

migrate:	## Start project migration & Database Seeder
	command cd ./docker && docker-compose exec php_fpm ./artisan migrate:fresh
	make seed

seed:		## Run Database Seeder
	command cd ./docker && docker-compose exec php_fpm ./artisan db:seed

.PHONY: server_info
server_info:	## Show Server Info
	make ps
	@echo ${Yellow}"Please go to the like show project: http://127.0.0.1:8088"
	@echo ${Yellow}"Project OpenAPI Documentation: http://127.0.0.1:8088/api/documentation"

.PHONY: help
help:	## Show Project commands
	@#echo ${Cyan}"\t\t This project 'poligon' REST API!"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
	@echo ${Red}"#######################################################################################"
	@echo ${Yellow}"Please go to the like show project: http://127.0.0.1:8088"
	@echo ${Yellow}"Project OpenAPI Documentation: http://127.0.0.1:8088/api/documentation"