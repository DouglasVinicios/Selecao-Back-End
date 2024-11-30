### Requisitos do sistema
#### Obrigatórios:

- O sistema deverá gerenciar os usuários, permitindo-os se cadastrar e editar seu cadastro; 
- O sistema deverá criptografar a senha do usuário;

POST     | api/create/user         | Cria um novo usuário

# Request
```json
{
    "name": "teste",
    "email": "example@example.com",
    "password": "1234",
    "isAdmin": false
}
```
# Response
```json
{
    "name": "teste",
    "email": "example@example.com",
    "updated_at": "2024-11-30T20:37:38.000000Z",
    "created_at": "2024-11-30T20:37:38.000000Z",
    "id": 18
}
```

- O sistema poderá autenticar o usuário através do e-mail e senha do usuário e, nas outras requisições, utilizar apenas um token de identificação;

POST     | api/login               | Faz o login

# Request
```json
{
    "name": "teste",
    "email": "example@example.com",
}
```
# Response
```json
{
    "token": "user-access-token"
}
```

PUT      | api/update/user         | Edita um usuário

# Request
Headers
```json
{
    "Authorization":"Bearer user-access-token",
}
```
```json
{
    "name": "teste",
    "email": "example@example.com",
    "id": 18,
    "isAdmin": true
}
```
# Response
```json
{
    "id": 18,
    "name": "teste",
    "email": "example@example.com",
    "email_verified_at": null,
    "created_at": "2024-11-30T20:39:28.000000Z",
    "updated_at": "2024-11-30T20:42:47.000000Z"
}
```

- O sistema deverá retornar comentários a todos que o acessarem, porém deverá permitir inserir comentários apenas a usuários autenticados;
- O sistema deverá retornar qual é o autor do comentário e dia e horário da postagem;

GET|HEAD | api/comments            | Lista todos os 

# Response
```json
{
    "data": [
        {
            "id": 1,
            "comment": "Um comentario",
            "author_id": 1,
            "created_at": "2024-11-30T20:00:56.000000Z",
            "updated_at": "2024-11-30T20:00:56.000000Z",
            "deleted_at": null,
            "author": {
                "id": 1,
                "name": "teste",
                "email": "example@example.com",
                "email_verified_at": null,
                "created_at": "2024-11-30T20:00:09.000000Z",
                "updated_at": "2024-11-30T20:00:09.000000Z"
            }
        }
    ]
}
```

POST     | api/create/comments     | Cria um novo 

# Request
Headers
```json
{
    "Authorization":"Bearer user-access-token",
}
```json
{
    "comment": "Um comentario"
}
```
# Response
```json
{
    "comment": "Um comentario",
    "author_id": 18,
    "updated_at": "2024-11-30T20:51:50.000000Z",
    "created_at": "2024-11-30T20:51:50.000000Z",
    "id": 15,
    "author": {
        "id": 18,
        "name": "teste",
        "email": "example@example.com",
        "email_verified_at": null,
        "created_at": "2024-11-30T20:39:28.000000Z",
        "updated_at": "2024-11-30T20:42:47.000000Z"
    }
}
```

- O sistema deverá permitir o usuário editar os próprios comentários (exibindo a data de criação do comentário e data da última edição);

PUT      | api/update/comments     | Edita um comentário

# Request
Headers
```json
{
    "Authorization":"Bearer user-access-token",
}
```
```json
{
    "id": 15,
    "comment": "Um outro teste de comentario"
}
```
# Response
```json
{
    "comment": "Um outro teste de comentario",
    "author_id": 18,
    "updated_at": "2024-11-30T20:54:53.000000Z",
    "created_at": "2024-11-30T20:54:53.000000Z",
    "id": 16,
    "author": {
        "id": 18,
        "name": "teste",
        "email": "example@example.com",
        "email_verified_at": null,
        "created_at": "2024-11-30T20:39:28.000000Z",
        "updated_at": "2024-11-30T20:42:47.000000Z"
    }
}
```

- O sistema deverá possuir histórico de edições do comentário;

GET|HEAD | api/history/comments    | Lista o histórico de todos os comentários

# Request
Headers
```json
{
    "Authorization":"Bearer user-access-token",
}
```
# Response
```json
{
    "data": [
        {
            "id": 16,
            "comment": "Um outro teste de comentario",
            "author_id": 18,
            "created_at": "2024-11-30T20:54:53.000000Z",
            "updated_at": "2024-11-30T20:54:53.000000Z",
            "deleted_at": null,
            "author": {
                "id": 18,
                "name": "teste",
                "email": "example@example.com",
                "email_verified_at": null,
                "created_at": "2024-11-30T20:39:28.000000Z",
                "updated_at": "2024-11-30T20:42:47.000000Z"
            }
        },
        {
            "id": 15,
            "comment": "Um comentario",
            "author_id": 18,
            "created_at": "2024-11-30T20:51:50.000000Z",
            "updated_at": "2024-11-30T20:54:53.000000Z",
            "deleted_at": "2024-11-30T20:54:53.000000Z",
            "author": {
                "id": 18,
                "name": "teste",
                "email": "example@example.com",
                "email_verified_at": null,
                "created_at": "2024-11-30T20:39:28.000000Z",
                "updated_at": "2024-11-30T20:42:47.000000Z"
            }
        }
    ]
}
```

- O sistema deverá permitir o usuário excluir os próprios comentários;
- O sistema deverá possuir um usuário administrador que pode excluir todos os comentários;

DELETE   | api/delete/comment/{id} | Deleta um comentário

# Request
Headers
```json
{
    "Authorization":"Bearer user-access-token",
}
```
# Response
- status code 200

- Implementação de testes automatizados utilizando phpunit

- php artisan test