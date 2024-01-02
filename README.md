## Restful API PHP 8

```markdown
Running server php : php -S localhost:NomorPort
```

### List endpoint :
- #### EndPoint Create data

```php
URL     : http://localhost:8000/api/v1/create.php
method  : POST
Request : JSON format {
 "name":"Nama Produk",
 "description":"Produk deskripsi",
 "price":112233445566,
}
```

- #### EndPoint Read data

```php
URL          : http://localhost:8000/api/v1/read.php
URL Paginasi : http://localhost:8000/api/v1/read.php?page=numberpage
method  : GET
```

- #### EndPoint Update data

```php
URL     : http://localhost:8000/api/v1/update.php
method  : PUT or PATCH
Request : JSON format {
 "id":1122
 "name":"Nama Produk",
 "description":"Produk deskripsi",
 "price":112233445566,
}
```
- #### EndPoint Delete data

```php
URL     : http://localhost:8000/api/v1/delete.php
method  : DELETE
Request : JSON format {
 "id":id
}
```

- #### EndPoint Search data

```php
URL     : http://localhost:8000/api/v1/search.php?keyword=namaProduk
method  : GET
```
