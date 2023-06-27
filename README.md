## API E-commerce

## Instalação

### 1 - Copie os dados do .env.example para um .env novo e popule o .env de acordo com seus dados

### 2 - Execute os códigos:

```
$ composer install
$ php artisan migrate
$ php artisan db:seed
$ php artisan jwt:secret
```

### 3 - Inicie o servidor com o comando:
```
$ php artisan serve
```
#### O servidor normalmente irá se iniciar na url [http://127.0.0.1:8000]

### Os endpoints com seus respectivos testes estarão na pasta docs, foi utilizado o Postman

### Para as funções de admnistrador, use o usuário administrator do banco de dados após o seed ou modifique o type do seu usuário no banco para "admin"

#Integrantes:
1. Gustavo Vicente Ozorio
2. Pedro Kopsch
3. Matheus José Vieira dos Santos    

#Atividades desenvolvildas:
    Gustavo: desenvolvimento da parte models, resources e banco de dados 
    Pedro: desenvolvimento dos controllers, resources, rotas  e realização de teste 
    Matheus: Desenvolvilmento da parte de models, migrations 

