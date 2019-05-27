# Quake Log Parser

## Introdução

O presente projeto, foi realizado utilizando as liguagens de programação PHP e Javascript, com auxílio dos frameworks [Laravel](https://laravel.com) e [VueJs](https://vuejs.org), respectivamente, seguindo a arquitetura MVC, e o banco de dados utilizado foi o Mysql.

Visando os requisitos propostos para realização do teste, escolhi fazer com as tecnologias acima, visando produtividade, qualidade, o fato de serem tecnologias em alta na atualidade, e neste caso, principalmente um pouco da extensão do que tenho experiência.

Para a leitura do log e persistência dos dados no banco de dados, deixei no lado backend, como o PHP, e para apresentação na tela, no lado frontend, com VueJs (javascript), que por sua vez, foi criado um Controller ao qual foi configurado uma rota de API para acesso ao mesmo, ao qual é retornado os dados do ranking, sendo consumido no frontend para apresentação desses dados.

## Estrutura do projeto
A estrutura do framework Laravel, é um pouco extensa, por essa razão, decidi listar apenas o principais diretórios, visto como mais importantes para entender esse projeto.

```
|-- app
|    |-- Http
|    |    |-- Controllers
|    |    |    |-- ApiController.php
|    |    |    |-- HomeController.php
|    |    |    |-- Controller.php
|    |    |    |-- LogController.php
|    |-- Models
|    |    |-- Game.php
|    |    |-- Kill.php
|    |    |-- MeansOfDeath.php
|    |    |-- Palyer.php
|-- database
|    |-- migrations
|    |    |-- 2019_05_25_162523_create_games_table.php
|    |    |-- 2019_05_25_162720_create_players_table.php
|    |    |-- 2019_05_25_163003_create_means_of_death_table.php
|    |    |-- 2019_05_25_163231_create_game_player_table.php
|    |    |-- 2019_05_25_164022_create_kills_table.php
|    |-- seeds
|    |    |-- DatabaseSeeder.php
|    |    |-- LogSeeder.php
|    |    |-- MeansOfDeathSeeder.php
|-- public
|    |-- css
|    |    |-- app.css
|    |-- insumos
|    |    |-- games.log
|    |    |-- layout.png
|    |    |-- modelagem.mwb
|    |    |-- modelagem.png
|    |    |-- poison.svg
|    |    |-- script.sql
|    |-- js
|    |    |-- app.js
|    |-- logs
|    |    |-- games.log
|    |-- svg
|    |    |-- loading.svg
|    |    |-- poison.svg
|-- resources
|    |-- js
|    |    |-- components
|    |    |    |-- Ranking.vue
|    |    |-- app.js
|    |    |-- bootstrap.js
|    |-- sass
|    |    |-- _variables.scss
|    |    |-- app.scss
|    |-- views
|    |    |-- index.blade.php
|    |    |-- relatorio.blade.php
|-- routes
|    |-- api.php
|    |-- web.php
|-- tests
|-- .env
|-- .env.example
|-- composer.json
|-- package.json
|-- phpunit.xml
|-- readme.md
|-- server.php
|-- webpack.mix.js
```

EXPLICAÇÃO:
Seguindo a arquitetura MVC, na pasta _app/Http/Controllers_, contém os controllers usados no projeto, ao qual, para seu acesso, é necesário configurar suas rotas no diretórios _routes_, em que o _api.php_ configura as rotas para consumir API, e _web.php_ para acessar o sistema normalmente, via navegador.

No caso do _LogController_, foi criado para implementar a lógica de ler e popular os dados do _games.log_, de forma que sua rota, após ter concluído esta tarefa, deixei comentada.

No _HomeController_, quando o usuário acessar a rota "/" do projeto, é renderizado para a view _index.blade.php_, em que seu conteúdo é preenchido pelo component _resources/js/components/Ranking.vue_ do VueJS. Esse component consome o _ApiController.php_ para apresentar os dados de ranking. Nele também contém a opção de fazer o download do _relatório de mortes agrupado por motivo_, em que o mesmo será baixado com extensão _.xls_.

Para estilizar o component _Ranking.vue_, utilizei o _sass_.

OBS: A escrita de um component vue é separada em 3 (três) partes, sendo um padrão de projeto do próprio VueJs, mantê-los assim no mesmo component (arquivo):
- O template HTML, identificado pela tag **template**;
- O código Javascript, identificado pela tag **script**;
- A estilização, identificada pela tag **style**;

## Modelagem de dados do projeto

![](/public/insumos/modelagem.png)

## Requisitos para rodar o projeto

- [PHP >= 7.1](https://www.php.net/downloads.php)
- [MySql](https://www.mysql.com/downloads/)
- [Composer](https://getcomposer.org/)
- [NodeJs](https://nodejs.org/en/)
- [Git](https://git-scm.com/downloads)

## Passo a passo para executar o projeto

**1º** - Faça o clone do projeto, executando no diretório que deseja o comando no terminal: **git clone https://github.com/taciobrito/quake_log_parser.git**


**2º** - Vá até o diretório do projeto clonado, ainda no terminal e rode o comando: **composer install**. Esse comando fará com que sejam baixadas todas as bibliotecas utilizadas pelo framework e/ou adicionados a parte, para a pasta **vendor/**.


**3º** - Após o passo anterior, ainda no terminal, rode o comando: **npm install**. Esse comando baixará todas as dependências do node para a pasta **node_modules/**.


**4º** - Crie, na conexão de sua preferência, um banco de dados no mysql com o nome de **quake_log_parser**.


**5º** - Localize no diretório raiz do projeto, o arquivo _.env.example_, copie todo conteúdo que há nele, crie um novo arquivo chamado _.env_ e cole o conteúdo, este é o arquivo que armazena as varáveis de ambiente do projeto. Nas linhas de 10 a 15 desse arquivo, encontra-se a configuração da conexão com o banco de dados, configure conforme sua conexão utilizada no passo anterior. Ex:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quake_log_parser
DB_USERNAME=root
DB_PASSWORD=

A URL base para consumir a API também é configurada neste arquivo, caso deseje alterar, ela se encontra na linha 6, com valor padrão conforme abaixo:

MIX_API_URL=http://localhost:8000/api


**6º** -  vá no terminal, no diretório raiz do projeto e execute o comando **php artisan key:generate**. Ele criará uma chave a ser atribuida na variável _APP_KEY_ no arquivo _.env_, linha 3. Sem essa chave, o projeto não rodará.

**7º** -  Nesse passo, iremos criar a estrutura do banco de dados. Para tal, apesar de haver o arquivo _/public/insumos/script.sql_, foi desenvolvida algumas migrations, localizadas no diretório _/database/migrations/_, ao qual, cada uma é responsável pela criação de uma tabela no banco de dados e seus relacionamentos. Para executá-las, no diretório raíz do projeto, pelo terminal, execute o comando **php artisan migrate**. Após isso, basta verificar no banco de dados, que todas foram criadas, além disso, é criada uma tabela _migrations_, que guarda o histórico de execução das migrations.


**8º** - Seguindo os passos anteriores, projeto está pronto para rodar, basta ir no terminal, na pasta raíz do projeto e executar o comando **php artisan serve**. Por padrão, o projeto será executado na porta _8000_ do seu localhost, caso deseje executar em outra porta, utilize o parâmetro _--port_, passando a porta que deseja, como mostrado a seguir, utilizando a porta _8003_: **php artisan serve --port=8003**. Após isso, vá até o browser e abra a URL _localhost:8000_, então verá a página de ranking, porém sem nenhum dado a ser exibido. Para isso, prossiga para a próxima etapa.

OBS: caso utilize uma porta diferente da porta 8000, é necessário que altere no _.env_ a variável _MIX_API_URL_, para que possa consumir os dados corretamente da API. Em consequência, como o código escrito em VueJs é compilado para o arquivo _/public/js/app.js_, em que o mesmo é utilizado na(s) views, é necessário que rode no terminal, no diretório raíz do projeto o comando **npm run dev**, para que seja novamente compilado.

## Lendo o arquivo games.log e populando o bando de dados

Para ler o arquivo de _log_ e popular os dados, foram criadas duas seeders, localizadas em _/database/seeds/_. Para executá-las, basta executar na raíz do projeto, pelo terminal, o comando *php artisan db:seed*, e aguardar até que finalize o procedimento.

EXPLICAÇÃO: 
- A seed _MeansOfDeathSeeder_ popula na tabela _means_of_death_, os possíveis motivos de morte durante os jogos.
- A seed _LogSeeder_ lê o arquivo _games.log_, percorrendo linha a linha, de forma que resgate os dados do _game_, dos _players_ e dos _kills_, agrupando por _game_, de forma que facilite ao salvar no banco de dados. Primeiro é identificado o início de um _game_, e enquanto não é localizado seu fim, é resgatado quais foram os players participantes, em que, caso ainda não existam na tabela _players_ do banco de dados, seu registro é criado. Da mesma forma, é resgatado as informações de _kills_ e agrupado no _game_. Quando finalmente o _game_ é finalizado, então ocorre o processo de registrá-lo no banco de dados, associando os players participantes e os _kills_ ocorridos.

Após o processo finalizar, basta atualizar a url no browser, conforme o _passo 8_ da etapa anterior, e verá a lista de ranking com os dados já populados.

Para visualizar os detalhes de cada jogo, acesse a rota _/games_ no navegador. Ex: _http:localhost:8000/games_.


## Executando os testes unitários


## Conclusão e agradecimentos

Conforme requisitado na descrição do teste, com o passo de popular os dados por meio das seeds, a **TASK 1** foi concluída, podendo ver seus detalhes na rota _/games_ no navegador. Ex: _http:localhost:8000/games_. Em consequência, ao acessar a rota "/", é possível visualizar o que se pede na **TASK 3** e também no **PLUS**, tendo em vista que nesta página há um botão, que ao ser clicado, baixa o relatório pedido. Como cumprimento da **TASK 2**, no diretório _/public/insumos/_, encontra-se tanto a imagem da modelagem em PNG: _modelagem.png_, quanto o arquivo de edição da modelagem, sendo editado no _Mysql WorkBench_, nomeado como _modelagem.mwb_.


Gostaria de agradecer pela oportunidade, gostei bastante de realizar esse teste, pois é um teste que requer dedicação, e pude aplicar vários conceitos, não todos que sei, mas o suficiente para mostrar meus conhecimentos e capacidade de desenvolver soluções.
