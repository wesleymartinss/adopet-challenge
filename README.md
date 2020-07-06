# hashlab-challenge

Implementação do Teste de backend da [adopet](https://adopt.adopets.com/), o teste consiste em escrever uma API com os seguintes recursos (Cadastro de Usuario, Login e Logout) para o usuer e as operações de CRUD para products, com listagem e filtragem.

# Stack

O projeto está dividido entre Backend e Banco de Dados. Foi utilizado o Framework Laravel para a escrita do backend, e o banco de dados Postgresql. Os dois serviços foram disponibilizados Utilizando Docker.

-   Linguagens: PHP
-   Bancos de dados: PostgreSQL
-   Deploy: Docker

# Executando

`git clone https://github.com/wesleymartinss/adopet-challenge.git` para clonar o repositório em sua máquina.
`docker-compose up` inicia todos os serviços e os banco de dados (inserindo alguns produtos e usuários).

## Usuários disponíveis

Em `scripts/mongo/users.json` temos 29 usuários (com `birth_date` em UTC e com dias do mês de agosto de 2019) para testar o envio do header X-USER-ID.

# Endpoints

`GET /product` - Listagem de todos os produtos fazendo chamadas ao `discount-service` para cada produto quando for enviado o header X-USER-ID, por padrão a paginação está em 20 itens por página

Exemplo

```
{
    "data": [
        {
            "id": "19a06d17-b9a4-4e48-9e31-50b07c05f1d1",
            "price_in_cents": 10000,
            "title": "Product 1",
            "description": "Description 1",
            "discount": {
                "prc": 5.0,
                "value_in_cents": 500
            }
        },
        {
            "id": "2aa90a5e-8c31-4121-8529-751719c988fc",
            "price_in_cents": 18084,
            "title": "Product 2",
            "description": "Description 2",
            "discount": {
                "prc": 5.0,
                "value_in_cents": 904
            }
        },
        ...
    ],
    "links": {
        "self": {
            "number": 1,
            "href": "/product?page=1"
        },
        "last": {
            "number": 3,
            "href": "/product?page=3"
        },
        "first": {
            "number": 1,
            "href": "/product?page=1"
        },
        "next": {
            "number": 2,
            "href": "/product?page=2"
        }
    }
}
```

`GET /product/{product-id}` - Pegando um produto por id junto fazendo uma chamada ao `discount-service` quando for enviado o header X-USER-ID

Exemplo

```
{
    "id": "0f642355-3841-4bc7-8a3c-1985383578e3",
    "price_in_cents": 22202,
    "title": "Product 3",
    "description": "Description 3",
    "discount": {
        "prc": 5.0,
        "value_in_cents": 1110
    }
}
```

## Tolerância a falhas

Caso o `discount-service` ou `user-service` parar de executar/retornar algum erro, o `product-service` continua listando os produtos, a diferença é que o sub-resource de `discount` vai retornar como nulo

Exemplo

```
{
    "data": [
        {
            "id": "19a06d17-b9a4-4e48-9e31-50b07c05f1d1",
            "price_in_cents": 10000,
            "title": "Product 1",
            "description": "Description 1",
            "discount": null
        },
        {
            "id": "2aa90a5e-8c31-4121-8529-751719c988fc",
            "price_in_cents": 18084,
            "title": "Product 2",
            "description": "Description 2",
            "discount": null
        },
        ...
    ],
    "links": {
         "self": {
             "number": 1,
             "href": "/product?page=1"
         },
         "last": {
             "number": 3,
             "href": "/product?page=3"
         },
         "first": {
             "number": 1,
             "href": "/product?page=1"
         },
         "next": {
             "number": 2,
             "href": "/product?page=2"
         }
    }
}
```

## Erros

Quando não é encontrado nenhum produto ou o id requisitado não foi encontrado, vai ser retornado um erro similar a esse:

```
{
    "errors": [
        {
            "type": "ResourcesNotFoundError",
            "message": "Products not found"
        }
    ],
    "url": "/product?page=42"
}
```

# Variáveis de ambiente disponíveis

-   `HASHLAB_PRODUCTION_SERVICE_PER_PAGE` Configura a quantidade de produtos por página, padrão: `20`
-   `HASHLAB_DISCOUNT_SERVICE_URI` endereço do `discount-service`, padrão: `localhost:50051`
-   `HASHLAB_DISCOUNT_SERVICE_PORT` porta do `discount-service` padrão: `:50051`
-   `HASHLAB_USER_SERVICE_URI` endereço do `user-service` padrão: `localhost:50052`
-   `HASHLAB_USER_SERVICE_PORT` porta do `user-service` padrão: `:50052`
-   `HASHLAB_POSTGRES_CONNECTION_URI` URL JDBC para acessar o PostgreSQL padrão: `jdbc:postgresql://localhost:5432/hashlab?user=hashlab&password=hashlab`
-   `HASHLAB_MONGODB_HOST` endereço do MongoDB padrão: `localhost:27017`
-   `HASHLAB_MONGODB_USERNAME` nome de usuário do MongoDB padrão: `hashlab`
-   `HASHLAB_MONGODB_PASSWORD` senha do MongoDB padrão: `hashlab`
-   `HASHLAB_MONGODB_DATABASE` banco de dados do MongoDB padrão: `hashlab`
-   `HASHLAB_MONGODB_AUTH_SOURCE` banco de dados de autorização do MongoDB padrão: `admin`

# Bibliotecas

## Clojure

-   `pedestal` - web framework
-   `honeysql` - uma camada para converter mapas Clojure em SQL
-   `lein-protoc` - plugin para implementar arquivos `.proto` para Clojure
-   `environ` - acessar variáveis de ambiente

## Go

-   `go.mongodb.org/mongo-driver/mongo` - driver oficial de MongoDB para a linguagem Go
-   `github.com/golang/protobuf/protoc-gen-go` - plugin para implementar arquivos `.proto` para Go
-   `github.com/crgimenes/goconfig` - acessar variáveis de ambiente

# Estrutura

## user-service

```
user-service
├── database
│   └── database.go // configura a conexão com o MongoDB
├── main.go // implementa o serviço gRPC e inicializa o servidor gRPC
├── model
│   └── User.go // modelo para ter acesso aos dados do MongoDB
```

## discount-service

```
discount-service
├── logic
│   ├── logic.go // regras de negócio para conceder ou não desconto
│   └── logic_test.go // teste das regras de negócio
├── main.go // implementa o serviço gRPC e inicializa o servidor gRPC
└── util
    └── util.go // utilitários para lidar com dados e a comunicação entre o user-service
```

## product-service

```
product-service
├── src
│   └── com
│       └── hash
│           └── product
│               ├── client
│               │   └── discount.clj // interface com o discount-service
│               ├── config.clj // configurações
│               ├── controller.clj // responsável por chamar o banco de dados e discount-service para executar as regras de negócio
│               ├── db.clj // interface com o banco de dados PostgreSQL
│               ├── interceptors
│               │   └── components.clj // utilitários para criar conexões com banco de dados e discount-service
│               ├── logic.clj // regras de negócio para lidar com a resposta do discount-service e calcular ou não o valor do desconto
│               ├── server.clj // inicializa o servidor HTTP
│               ├── service.clj // rotas e funções para lidar com o HTTP (headers, querystring, path)
│               └── util.clj // utilitários para lidar com configurações, gRPC, banco de dados, paginação, valores em centavos e as respostas em JSON
└── test
    └── com
        └── hash
            └── product
                ├── controller_test.clj ;; testando as regras do controller
                └── logic_test.clj ;; testando as regras de negócio
```

# Deploy

O deploy foi feito utilizando o [Google Cloud Platform](https://cloud.google.com/) com Docker e [CoreOS](https://coreos.com/) para se aproximar ao máximo da stack da Hash.

# Links

-   https://blog.jmibanez.com/2018/07/22/grpc-with-clojure-and-leiningen.html
-   https://github.com/vrih/clojure-grpc-example
