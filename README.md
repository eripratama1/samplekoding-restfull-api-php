Running server PHP : php -S localhost:8000

List endpoint :
- Create (http://localhost:8000/api/v1/create.php) use method POST
Sample request : JSON format
{
      "name":"Nama Produk",
      "description":"Produk deskripsi",
      "price":112233445566,
}

- Read   (http://localhost:8000/api/v1/read.php) use method GET

- Update (http://localhost:8000/api/v1/update.php) use method PUT or PATCH
  Sample request : JSON format
  {
       "id":1122
       "name":"Nama Produk",
       "description":"Produk deskripsi",
       "price":112233445566,
   }
      
- Delete (http://localhost:8000/api/v1/delete.php) use method DELETE
  Sample request : JSON format
  {
       "id":"Id Produk",
  }
  
- Search (http://localhost:8000/api/v1/search.php?keyword=namaProduk) use method GET
