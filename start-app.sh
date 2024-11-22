#!/bin/bash

# Exibir erro e sair caso ocorra falha em qualquer comando
set -e

# Função para limpar recursos ao encerrar o script
cleanup() {
  echo "Encerrando o Docker e limpando recursos..."
  docker-compose down
  echo "Recursos limpos. Saindo do script."
}

# Configurar o trap para capturar SIGINT (CTRL+C) e SIGTERM
trap cleanup INT TERM

# Verificar se o arquivo .env existe
if [ ! -f .env ]; then
  echo "Arquivo .env não encontrado. Criando um arquivo padrão..."
  cp .env.example .env
  echo "Arquivo .env criado com base em .env.example. Por favor, revise as configurações antes de continuar."
fi

# Verificar se a APP_KEY precisa ser gerada
APP_KEY=$(grep ^APP_KEY= .env | cut -d '=' -f 2)
if [ -z "$APP_KEY" ]; then
  echo "APP_KEY está ausente ou vazia. Gerando uma nova chave de aplicação..."
  php artisan key:generate
else
  echo "APP_KEY já está configurada."
fi

echo "Iniciando o container Docker para o banco de dados..."
docker-compose up -d --build

echo "Rodando 'php artisan install' localmente..."
composer install

echo "Rodando 'php artisan migrate' localmente..."
php artisan migrate

echo "Instalando dependências do npm..."
npm install

echo "Iniciando 'php artisan serve' de forma assíncrona..."
php artisan serve --host=127.0.0.1 --port=8000 &

echo "Iniciando 'npm run dev' de forma assíncrona..."
npm run dev &

echo "Tudo configurado e rodando! Os comandos assíncronos continuam em execução."

# Manter o script ativo até que os processos assíncronos sejam encerrados
wait
