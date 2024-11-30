# Requisitos do sistema
## Cria um novo usuário
- O sistema deverá gerenciar os usuários, permitindo-os se cadastrar e editar seu cadastro; 
- O sistema deverá criptografar a senha do usuário;

### Endpoint
```http
POST     | api/create/user
```

### Request
```json
{
    "name": "teste",
    "email": "example@example.com",
    "password": "1234",
    "isAdmin": false
}
```
### Response
```json
{
    "name": "teste",
    "email": "example@example.com",
    "updated_at": "2024-11-30T20:37:38.000000Z",
    "created_at": "2024-11-30T20:37:38.000000Z",
    "id": 18
}
```
---
## Realizar o login
- O sistema poderá autenticar o usuário através do e-mail e senha do usuário e, nas outras requisições, utilizar apenas um token de identificação;

### Endpoint
```http
POST     | api/login               
```

### Request
```json
{
    "name": "teste",
    "email": "example@example.com",
}
```
### Response
```json
{
    "token": "user-access-token"
}
```
---
## Editar um usuário
### Endpoint
```http
PUT      | api/update/user         
```
### Request
Headers
```json
{
    "Authorization":"Bearer user-access-token",
}
```
Body
```json
{
    "name": "teste",
    "email": "example@example.com",
    "id": 18,
    "isAdmin": true
}
```
### Response
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
---
## Listar todos os comentários
- O sistema deverá retornar comentários a todos que o acessarem, porém deverá permitir inserir comentários apenas a usuários autenticados;
- O sistema deverá retornar qual é o autor do comentário e dia e horário da postagem;

### Endpoint
```http
GET | api/comments             
```

### Response
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
---
## Criar um novo comentário
### Endpoint
```http
POST     | api/create/comments      
```
### Request
Headers
```json
{
    "Authorization":"Bearer user-access-token",
}
```
Body
```json
{
    "comment": "Um comentario"
}
```
### Response
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
---
## Editar um comentário
- O sistema deverá permitir o usuário editar os próprios comentários (exibindo a data de criação do comentário e data da última edição);

### Endpoint
```http
PUT      | api/update/comments     
```
### Request
Headers
```json
{
    "Authorization":"Bearer user-access-token",
}
```
Body
```json
{
    "id": 15,
    "comment": "Um outro teste de comentario"
}
```
### Response
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
---
## Listar o histórico de todos os comentários
- O sistema deverá possuir histórico de edições do comentário;

## Endpoint
```http
GET | api/history/comments    
```
### Request
Headers
```json
{
    "Authorization":"Bearer user-access-token",
}
```
### Response
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
---
## Deletar um comentário
- O sistema deverá permitir o usuário excluir os próprios comentários;
- O sistema deverá possuir um usuário administrador que pode excluir todos os comentários;

### Endpoint
```http
DELETE   | api/delete/comment/{id} 
```
### Request
Headers
```json
{
    "Authorization":"Bearer user-access-token",
}
```
### Response
```http
status code 200
```
---
### Implementação de testes automatizados utilizando phpunit
```code
php artisan test
```