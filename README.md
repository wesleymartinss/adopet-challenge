# adopet-challenge

Implementação do Teste de backend da [adopet](https://adopt.adopets.com/), o teste consiste em escrever uma API com os seguintes recursos (Cadastro de Usuario, Login e Logout) para o usuer e as operações de CRUD para products, com listagem e filtragem. A Autenticação dessa aplicação é feita utilizando [Json Web Token](https://jwt.io/),

# Stack

O projeto está dividido entre Backend e Banco de Dados. Foi utilizado o Framework Laravel para a escrita do backend, e o banco de dados Postgresql. Os dois serviços foram disponibilizados Utilizando Docker.

-   Linguagens: PHP
-   Bancos de dados: PostgreSQL
-   Deploy: Docker

# Executando

`git clone https://github.com/wesleymartinss/adopet-challenge.git` para clonar o repositório em sua máquina.
`docker-compose up --build` inicia todos os serviços e os banco de dados (inserindo alguns produtos e usuários).

# Endpoints User

`POST api/user` - Criação de um novo usuário. O JSON esperado deve parecido com o exemplo:

```
{
	"name": "wesley",
	"email": "mail@teste.com",
	"password": "1234567",
	"password_confirmation": "1234567"
}
```

O retorno esperado deve ser

```

{
  "message": "Successfully registered",
  "id": "69e7851c-f12b-4f0c-a2b1-a42563b680ee"
}

```

Caso ocorra algum erro de validação, as informações de validação podem ser contratadas com mais detalhes no formRequest de cada requisição. Irá retornar uma mensagem parecido com essa:

```
{
  "message": "The given data was invalid.",
  "errors": {
    "name": [
      "The name field is required."
    ],
    "email": [
      "The email field is required."
    ],
    "password": [
      "The password field is required."
    ]
  }
}
```
`GET api/user` - Pegando um usuario quando for enviado o header x-user-id

Exemplo

```

{
  "id": "45827d7e-22db-4e4b-88f8-84e14f170b41",
  "name": "Jonathan",
  "email": "Laurence.Stanbury.78@yahoo.co.in",
  "created_at": "1980-09-30T08:32:10.000000Z",
  "updated_at": "2019-10-13T03:07:11.000000Z"
}
```
`POST api/login` - Criação de um novo Token de autenticação

Exemplo:

```
{
    "email":"mail@teste.com",
    "password":"1234567",
    "password_confirmation": "1324567"
}
```

Retorno esperado:

```
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8zNS4yMjIuNTUuMTk4XC9hcGlcL2xvZ2luIiwiaWF0IjoxNTk0MDc1OTI4LCJleHAiOjE1OTQwNzk1MjgsIm5iZiI6MTU5NDA3NTkyOCwianRpIjoiVnhLanE3cmJ3clppTFVyNSIsInN1YiI6IjY5ZTc4NTFjLWYxMmItNGYwYy1hMmIxLWE0MjU2M2I2ODBlZSIsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.B1D4f5lcq1PtLENX9y_8W37ypFdJad4dXZ-XPku_siY",
    "token_type": "bearer",
    "expires_in": 3600
}
```

`GET api/refresh` - Irá dar um refresh no token informado. Irá invalidar o token informado no header e gerar um novo. Esta rota está autenticada.

Exemplo:

Deve ser informado um token no header da requisicao como

Header -------Value
Authorization Bearer <tokenaqui>

Resposta esperada:

```
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8zNS4yMjIuNTUuMTk4XC9hcGlcL3JlZnJlc2giLCJpYXQiOjE1OTQwNzYwNjEsImV4cCI6MTU5NDA3OTc2NCwibmJmIjoxNTk0MDc2MTY0LCJqdGkiOiI5TFhmejRKVUx4dFlEOUxmIiwic3ViIjoiNjllNzg1MWMtZjEyYi00ZjBjLWEyYjEtYTQyNTYzYjY4MGVlIiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSJ9.zYUu7NX9cyaLB7gVFzdvDzaMhoq3UNYLGQu0m3ag-BI",
    "token_type": "bearer",
    "expires_in": 3600
}
```

Caso o token informado não seja valido ou o usuário não estiver autenticado, irá retornar uma mensagem parecida com esta.

```
{
    "message": "Unauthenticated."
}
```

`DELETE api/logout` - Irá deletar o token informado, invalidando a seção desse token específico. Esta rota está autenticada.

Exemplo:

Deve ser informado um token no header da requisicao como

Header -------Value
Authorization Bearer <tokenaqui>

Resposta Esperadaa

```
{
  "message": "Successfully logged out"
}
```

Caso o token informado não seja valido ou o usuário não estiver autenticado, irá retornar uma mensagem parecida com esta.

```
{
    "message": "Unauthenticated."
}
```

# Endpoints Products

`GET api/products` - Listagem de todos os produtos, por padrão a paginação está em 5 itens. porem pode ser enviado no header um atributo paginate com quantidade de itens desejados
Exemplo

```
{
  "current_page": 1,
  "data": [
    {
      "id": "34f52671-3814-49bc-8a87-48154000e3ea",
      "name": "Pearl",
      "description": "dolor sit amet, co",
      "categorie": "Talitha",
      "price": "0.38",
      "stock_quantity": 440,
      "created_at": "2000-07-06T05:31:46.000000Z",
      "updated_at": "2001-06-12T07:30:25.000000Z"
    },
    {
      "id": "985071f5-1a78-4e82-aa8b-1ea5e3bf2167",
      "name": "Marina",
      "description": "irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fug",
      "categorie": "Leanne",
      "price": "0.92",
      "stock_quantity": 3194,
      "created_at": "1988-04-27T15:31:49.000000Z",
      "updated_at": "1999-02-10T17:12:23.000000Z"
    },
    {
      "id": "7dd38e32-ccd4-42e2-9dd9-7f132b195ab4",
      "name": "Berenice",
      "description": "exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in",
      "categorie": "Kathleen",
      "price": "0.53",
      "stock_quantity": 3074,
      "created_at": "1998-11-30T14:59:25.000000Z",
      "updated_at": "2000-10-20T16:57:56.000000Z"
    },
    {
      "id": "06b6fa24-67c2-4e6d-a8f6-4ded08cc2e60",
      "name": "Rowland",
      "description": "exercitati",
      "categorie": "Vivian",
      "price": "0.25",
      "stock_quantity": 2308,
      "created_at": "2003-12-06T17:45:31.000000Z",
      "updated_at": "2003-06-01T04:22:08.000000Z"
    },
    {
      "id": "f4c6e17e-c2a1-40bc-9928-f88bbe123ccd",
      "name": "Matthew",
      "description": "nulla pariatur. Excepteur s",
      "categorie": "Helene",
      "price": "0.10",
      "stock_quantity": 3869,
      "created_at": "1994-08-20T05:47:54.000000Z",
      "updated_at": "2003-02-01T22:17:12.000000Z"
    },
    {
      "id": "9db9753b-6eaf-44d3-ac66-4e44baeb077a",
      "name": "Jim",
      "description": "adipisc",
      "categorie": "Susanne",
      "price": "0.57",
      "stock_quantity": 4828,
      "created_at": "2015-07-21T19:55:56.000000Z",
      "updated_at": "2019-12-16T07:16:25.000000Z"
    },
    {
      "id": "ba1a1739-c7f4-4774-a386-d9a53175a8b7",
      "name": "Aviva",
      "description": "est laborum.Lorem ipsum dolor sit amet, consectetur adipisc",
      "categorie": "Marta",
      "price": "0.38",
      "stock_quantity": 2287,
      "created_at": "2009-03-24T20:26:00.000000Z",
      "updated_at": "1990-04-18T12:11:11.000000Z"
    },
    {
      "id": "9c0e24fe-5f27-4efe-9dea-85ca31006be1",
      "name": "Johnson",
      "description": "ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco ",
      "categorie": "Genevieve",
      "price": "0.40",
      "stock_quantity": 480,
      "created_at": "2000-10-31T21:28:28.000000Z",
      "updated_at": "2015-01-03T18:36:16.000000Z"
    },
    {
      "id": "4fa1c774-97c5-46ca-826a-316d6aa10575",
      "name": "Travis",
      "description": "consectetur adipiscing elit, sed do e",
      "categorie": "Leah",
      "price": "0.90",
      "stock_quantity": 1861,
      "created_at": "2016-05-03T07:14:07.000000Z",
      "updated_at": "2008-05-04T10:41:27.000000Z"
    },
    {
      "id": "fe9f15fe-aa06-44b4-bc89-28f371afa0b2",
      "name": "Suzanne",
      "description": "cillum dolore eu fugiat nulla ",
      "categorie": "Ethelreda",
      "price": "0.24",
      "stock_quantity": 3472,
      "created_at": "1999-04-23T23:02:35.000000Z",
      "updated_at": "1995-12-28T11:50:58.000000Z"
    }
  ],
  "first_page_url": "http:\/\/35.222.55.198\/api\/products?page=1",
  "from": 1,
  "last_page": 2168,
  "last_page_url": "http:\/\/35.222.55.198\/api\/products?page=2168",
  "next_page_url": "http:\/\/35.222.55.198\/api\/products?page=2",
  "path": "http:\/\/35.222.55.198\/api\/products",
  "per_page": "10",
  "prev_page_url": null,
  "to": 10,
  "total": 21679
}
```

`GET api/product` - Pegando um produto quando for enviado o header x-product-id

Exemplo

```

{
    "id": "34f52671-3814-49bc-8a87-48154000e3ea",
    "name": "Pearl",
    "description": "dolor sit amet, co",
    "categorie": "Talitha",
    "price": "0.38",
    "stock_quantity": 440,
    "created_at": "2000-07-06T05:31:46.000000Z",
    "updated_at": "2001-06-12T07:30:25.000000Z"
}


```

`PUT api/product` - Passando um id sendo um uuid válido, irá modificar os dados do produto com o UUID informado, nenhum dos campos para a edição é obrigatorio, apenas o UUID, para a identificação do produto

Exemplo do corpo da requisição a ser enviado

```
{
	"id": "e444513d-7bb3-4e1c-b695-78eed1bfde8b",
	"name": "food updated",
	"description": "new modified food",
	"categorie": "changed",
	"price": 1.8,
	"stock_quantity": "7894561"
}

```

Exemplo da resposta esperada

```
{
  "message": "Successfully updated",
  "id": "1f5b39ab-4b43-4281-b7bb-2e432537c7dc"
}

```

Caso tenha algum problema com o UUID Passado

```
{
  "message": "The given data was invalid.",
  "errors": {
    "id": [
      "The id must be a valid UUID."
    ]
  }
}

```

Caso o Produto informado não for encontrado

```
{
  "message": "Passed product doenst exist, any resource was updated"
}
```

`DELETE api/product` - Passando um id via header como x-product-id sendo um uuid válido, irá APAGAR os dados do produto com o UUID informado

Exemplo:
Caso a operação ocorra com sucesso, ira retornar vazio com status code 204.

`POST api/product` - Irá criar um produto de acordo com as informações informadas no body

Exemplo :

```
    {
        "name": "food",
        "description": "dog food",
        "categorie": "new",
        "price": 1.2,
        "stock_quantity": "1234567"
    }
```

Retorno esperado

```
   {
        "message": "Successfully registered",
        "id": "4d362742-79b2-490f-be80-272d35787870"
   }
```

Caso ocorra algum erro de validação, as informações de validação podem ser contratadas com mais detalhes no formRequest de cada requisição. Irá retornar uma mensagem parecido com essa:

```
{
  "message": "The given data was invalid.",
  "errors": {
    "name": [
      "The name field is required."
    ],
    "description": [
      "The description field is required."
    ],
    "categorie": [
      "The categorie field is required."
    ],
    "price": [
      "The price field is required."
    ],
    "stock_quantity": [
      "The stock quantity field is required."
    ]
  }
}
```

`GET api/products/filter?filter[name]=food&sort=-price` - Pega todos os produtos de acordo com os filtros solicitados de acordo com a ordenação informada.

Exemplo: Nesse caso todos os produtos que tenham o name food e ordenando de acordo com os preços ( A notação do sort se passado o atributo ´-´, decidirá se a ordenação deve ser feita por ordem ascendente ou descendente)

```
{
  "current_page": 1,
  "data": [
    {
      "id": "0be9834e-33c6-4447-bd54-f67c0b10712c",
      "name": "food",
      "description": "dog food",
      "categorie": "new",
      "price": "1.90",
      "stock_quantity": 1234567,
      "created_at": "2020-07-06T22:20:59.000000Z",
      "updated_at": "2020-07-06T22:20:59.000000Z"
    },
    {
      "id": "9a69da2c-c83c-4402-a370-e69a5a56fe59",
      "name": "food",
      "description": "dog food",
      "categorie": "new",
      "price": "1.90",
      "stock_quantity": 1234567,
      "created_at": "2020-07-06T22:21:02.000000Z",
      "updated_at": "2020-07-06T22:21:02.000000Z"
    },
    {
      "id": "4d362742-79b2-490f-be80-272d35787870",
      "name": "food",
      "description": "dog food",
      "categorie": "new",
      "price": "1.20",
      "stock_quantity": 1234567,
      "created_at": "2020-07-06T22:10:03.000000Z",
      "updated_at": "2020-07-06T22:10:03.000000Z"
    }
  ],
  "first_page_url": "http:\/\/35.222.55.198\/api\/products\/filter?page=1",
  "from": 1,
  "last_page": 1,
  "last_page_url": "http:\/\/35.222.55.198\/api\/products\/filter?page=1",
  "next_page_url": null,
  "path": "http:\/\/35.222.55.198\/api\/products\/filter",
  "per_page": "3",
  "prev_page_url": null,
  "to": 3,
  "total": 3
}


```

Caso o filtro informado sejá inválido, será retornado uma mensagem parecida com essa com status code 400:

```
{
  "message": "Requested filter are not allowed",
  "error_filter": [
    "teste"
  ],
  "available_filter": [
    "name",
    "description",
    "categorie",
    "price",
    "stock_quantity",
    "min_price",
    "max_price"
  ]
}
```

Ou para ordenacao

```
{
  "message": "Requested sort are not allowed",
  "error_sort": [
    "teste"
  ],
  "available_sort": [
    "name",
    "description",
    "categorie",
    "price",
    "stock_quantity"
  ]
}
```

## Logs

Os logs da aplicação estão sendo salvos em `storage\logs\` por padrão do laravel. Somente as ações do usuário conforme solicitado, estão sendo gravadas pela aplicação. Assim como, possiveis erros que venham disparar e sejam capturados pelo sistema.

## PHP

-   `laravel` - Web framework
-   `eloquent` - ORM para fazer o mapeamento dos models
-   `composer` - Gerenciador de pacotes do PHP
-   `laravel-eloquent-uuid` - Pacote responsável por fazer o mapeamento de chave primaria e ID e auto generate para UUID
-   `jwt-auth` - Pacote responsável por gerenciar todas as dependencias relacionados a autenticação
-   `spatie-query-builder``- Pacote utilizado para lidar com queryparams e criar os filtros e ordenações

# Deploy

O deploy foi feito utilizando o [Google Cloud Platform](https://cloud.google.com/) com Docker e [CoreOS](https://coreos.com/)

# Referencias

-   https://github.com/goldspecdigital/laravel-eloquent-uuid
-   https://github.com/tymondesigns/jwt-auth
-   https://github.com/spatie/laravel-query-builder
-   https://laravel.com/docs/7.x
-   https://getcomposer.org/
