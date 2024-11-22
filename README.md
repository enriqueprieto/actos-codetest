# Desafio Técnico: Desenvolvedor Full Stack

Este repositório foi criado para um desafio técnico para a vaga de **Desenvolvedor Full Stack**. Com o objetivo de mostrar minhas habilidades em desenvolvimento web.

O desafio é criar um sistema web com foco em gestão de dados georreferenciados e exibição em mapa.

## Dependências

Antes de irmos para a instalação do projeto, precisamos fazer algumas verificações antes. Existem algumas ferramentas que são necessárias estarem instaladas para o funcionamento correto do projeto, são eles:

- Git => `2.43.0`
- Docker => `2.30.3`
- Docker Compose => `2.30.3`
- PHP => `8.3.6`
- Composer => `2.8.3`
- `php-pgsql`
- Node => `18.20.4`
- Laravel => `11.31`
- TallStack => `8.0`
- Filementphp => `3.2`
- PostgreSQL => `14`
- PostGIS => `3.2.0`

## Rodando o projeto

Neste projeto você consegue rodar-lo de 2(duas) formas diferente: automatizado e manual. Irei mostrar e explicar cada uma.

### Automatizado

Para tornar a experiência de desenvolvimento mais leve, criei um arquivo chamado `start-app.sh` que se encontra na raíz deste repositório. Basta executar o seguinte comando:

```bash
sh start-app.sh
```

Com esse `script` ele vai subir o banco de dados, configurar o projeto, instalar as dependências e inicializar o servidor da aplicação. Se ocorrer tudo certo com a execução do script você poderá acessar a aplicação pela URL: [http://localhost:8000](http://localhost:8000). Caso tenha dado algum erro na execução do **script**, confirme se as dependências do projeto estão instaladas corretamente e tente novamente.

Caso você esteja usando um sistema **Windows** será preciso instalar ferramentas que permitam executar um script `bash`, como por exemplo

- Git Bash
- WSL

Mas caso queria rodar o projeto de forma manual, siga os passos abaixo

### Manual

#### Variável de ambiente

Caso tenha acabado de baixar o projeto, o arquivo `.env` não estará presente no projeto. Este arquivo é crucial para funcionamento da aplicação. Copie o arquivo `.env.example` ou rode o seguinte comando:

```bash
cp .env.example .env
```

Após criar o `.env` vamos precisar gerar uma `APP_KEY` para nosso projeto, então, execute o seguinte comando:

```bash
php artisan key:generate
```

Esse comando irá gerar nosso `APP_KEY` e atualizar nosso `.env`. Agora com essas configurações finalizadas, podemos começar a inicializar o projeto.

#### Inicializando o banco de dados

Pelo terminal acesse o diretório que está presente o código deste repositório e rode o seguinte comando:

```bash
docker-compose up -d --build
```

Com esse comando ele vai subir nosso banco de dados `PostgreSQL` junto com a extensão `PostGIS`. Para garantir que nosso banco esteja com todas alterações criadas nas migrations, rode o comando

```bash
php artisan migrate
```

Esse comando irá sincronizar o banco de dados e criar as tabelas necessárias no banco de dados.

#### Instalando as dependências

Para instalar as dependências do laravel, rode o seguinte comando:

```bash
composer install && npm install
```

Com esse comando será instalado as dependências do `composer` e `npm`.


#### Inicialiar aplicações

Após seguir os passos acima, vamos rodar os comandos que irão inicializar o projeto.

```bash
php artisan serve & npm run dev
```

Se ocorrer tudo certo com a execução do comando você poderá acessar a aplicação pela URL: [http://localhost:8000](http://localhost:8000).
