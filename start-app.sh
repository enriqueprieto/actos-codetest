#!/bin/bash

# Exibir erro e sair caso ocorra falha em qualquer comando
set -e

# Função para limpar recursos ao encerrar o script
cleanup() {
  echo "Encerrando o Docker e limpando recursos..."
  docker-compose down
  echo "Recursos limpos. Saindo do script."
}

trap cleanup INT TERM

echo "# Verificando se .env está presente no projeto..."
if [ ! -f .env ]; then
  echo "Arquivo .env não encontrado. Criando um arquivo padrão..."
  cp .env.example .env
  echo "Arquivo .env criado com base em .env.example."
else
  echo "Arquivo .env já configurado no projeto."
fi
echo "\n========================================\n"

# Verificar se a APP_KEY precisa ser gerada
echo "# Verificando se no .env está presente a variável APP_KEY com valor configurado"
APP_KEY=$(grep ^APP_KEY= .env | cut -d '=' -f 2)
if [ -z "$APP_KEY" ]; then
  echo "APP_KEY está ausente ou vazia. Gerando uma nova chave de aplicação..."
  php artisan key:generate
else
  echo "APP_KEY já está configurada."
fi
echo "\n========================================\n"

echo "# Iniciando o container Docker para o banco de dados..."
docker-compose up -d --build
echo "\n========================================\n"

echo "# Instalando as dependências do projeto..."
composer install && npm install
echo "\n========================================\n"

echo "# Rodando migrate no projeto para deixar o banco de dados sincronizado."
php artisan migrate
echo "\n========================================\n"

echo "# Iniciando a aplicação..."
php artisan serve --host=127.0.0.1 --port=8000 & npm run dev &

wait
