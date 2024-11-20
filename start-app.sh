#!/bin/bash

# Exibe informações do processo
echo "Iniciando o processo de build e subida dos containers..."

# Navega até o diretório onde o docker-compose.yml está localizado
cd "$(dirname "$0")"

# Faz o build das imagens do Docker
echo "Construindo as imagens Docker..."
docker-compose build

# Levanta os containers em segundo plano
echo "Subindo os containers..."
docker-compose up -d

# Verifica se os containers estão rodando
docker-compose ps

echo "Containers iniciados com sucesso!"
