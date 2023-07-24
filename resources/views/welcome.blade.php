<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory-api</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/prism.css')}}">
</head>
<body class="container mt-3">
<div>
    <p>Base Url</p>
    <pre><code class="language-html">
        https://inventory-api.malejah.com/api
        </code></pre>
    <p>Headers</p>
    <pre><code class="language-html">
        Content-Type:application/json
        Accept:application/json
        </code></pre>
    <p class="mt-3">Products</p>

    <pre><code class="language-json">
        POST /products  Create product
            Payload
            {
                "name":"name",
                "category_id":1,
                "sub_category":1,
                "code":"code",
                "photo":"photo",
                "stock":24,
                "unit_id":1,
                "supplier_id":1,
                "sale_price":20000,
                "wholesale_price":20000,
                "other_price":20000,
                "discount":10
            }

        GET /products  Get all products
        [
            {
                "id": 1,
                "name": "product name",
                "category_id": 1,
                "sub_category": 1,
                "code": "1234",
                "photo": "http://inventory-api.test/storage/images/1688230205.jpg",
                "stock": 2567,
                "unit_id": 1,
                "supplier_id": 1,
                "sale_price": 10000,
                "wholesale_price": 9000,
                "other_price": 9500,
                "discount": "10.00",
                "created_at": "01-07-2023",
                "updated_at": "2023-07-01T16:50:06.000000Z",
                "category": {
                    "id": 1,
                    "name": "pesticides",
                    "photo": "http://inventory-api.test/storage/images/1688394702.jpg",
                    "created_at": "2023-07-03T14:31:42.000000Z",
                    "updated_at": "2023-07-03T14:31:42.000000Z"
                },
                "supplier": {
                    "id": 1,
                    "name": "bukoola",
                    "photo": null,
                    "created_at": "2023-07-03T14:47:17.000000Z",
                    "updated_at": "2023-07-03T14:47:17.000000Z"
                },
                "unit": {
                    "id": 1,
                    "name": "Liters",
                    "symbol": "ltr",
                    "created_at": "2023-07-03T15:08:03.000000Z",
                    "updated_at": "2023-07-03T15:08:03.000000Z"
                }
            }
        ]

        </code></pre>

    <pre><code class="language-html">
        GET  /filter-category-products?category_id=1&from=01-07-2023&to=01-07-2023   Filters products in a category
        </code></pre>

    <p class="mt-3">Categories</p>
    <pre><code class="language-json">
        POST /categories  Create Category
            Payload
            {
                "name":"name",
                "photo":"photo",
            }
        GET  /categories    Get all categories

        GET  /category-products        Get all products for a category
        Output
         [
            {
                "id": 1,
                "name": "pesticides",
                "photo": "http://inventory-api.test/storage/images/1688394702.jpg",
                "created_at": "2023-07-03T14:31:42.000000Z",
                "updated_at": "2023-07-03T14:31:42.000000Z",
                "products": [
                    {
                        "id": 1,
                        "name": "product name",
                        "category_id": 1,
                        "sub_category": 1,
                        "code": "1234",
                        "photo": "http://inventory-api.test/storage/images/1688230205.jpg",
                        "stock": 2567,
                        "unit_id": 1,
                        "supplier_id": 1,
                        "sale_price": 10000,
                        "wholesale_price": 9000,
                        "other_price": 9500,
                        "discount": "10.00",
                        "created_at": "01-07-2023",
                        "updated_at": "2023-07-01T16:50:06.000000Z",
                        "supplier": {
                            "id": 1,
                            "name": "bukoola",
                            "photo": null,
                            "created_at": "2023-07-03T14:47:17.000000Z",
                            "updated_at": "2023-07-03T14:47:17.000000Z"
                        },
                        "unit": {
                            "id": 1,
                            "name": "Liters",
                            "symbol": "ltr",
                            "created_at": "2023-07-03T15:08:03.000000Z",
                            "updated_at": "2023-07-03T15:08:03.000000Z"
                        }
                    }
                ]
            }
        ]
        </code></pre>

    <p class="mt-3">Suppliers</p>
    <pre><code class="language-json">
        POST /suppliers  Create Supplier
            Payload
            {
                "name":"name",
                "photo":"photo",
            }
        GET  /suppliers    Get all suppliers
        Output
        [
            {
                "id": 2,
                "name": "new",
                "photo": "http://inventory-api.test/storage/images/1688396435.jpg",
                "created_at": "2023-07-03T15:00:35.000000Z",
                "updated_at": "2023-07-03T15:00:35.000000Z"
            }
        ]

        </code></pre>

    <p class="mt-3">SubCategory</p>
    <pre><code class="language-json">
        POST /subcategories  Create Subcategory
            Payload
            {
                "name":"name"
            }
        GET  /subcategories    Get all subcategories
        Output
       [
            {
                "id": 1,
                "name": "subcategory",
                "created_at": "2023-07-10T05:07:59.000000Z",
                "updated_at": "2023-07-10T05:07:59.000000Z"
            }
       ]

        </code></pre>

    <p class="mt-3">Units</p>
    <pre><code class="language-json">
        POST /units  Create Unit
            Payload
            {
                "name":"Kilogram",
                "symbol":"Kg"
            }
        GET  /units    Get all units
        Output
        [
            {
                "id": 1,
                "name": "Liters",
                "symbol": "ltr",
                "created_at": "2023-07-03T15:08:03.000000Z",
                "updated_at": "2023-07-03T15:08:03.000000Z"
            }
        ]

        </code></pre>

    <p class="mt-3">Sales</p>
    <pre><code class="language-json">
        POST /sales  Create Sale
            Payload
          {
            "amount":200000,
            "product_id":1,
            "mode":"cash",
            "quantity":30
          }
        GET  /sales    Get all sales
        Output
        [
            {
                "id": 1,
                "amount": 10000,
                "product_id": 1,
                "quantity": 4,
                "mode": "cash",
                "created_at": "2023-07-08T03:02:31.000000Z",
                "updated_at": "2023-07-08T03:02:31.000000Z"
            }
        ]

        </code></pre>

    <p class="mt-3">Expenses</p>
    <pre><code class="language-json">
        POST /expenses  Create Expenses
            Payload
           {
            "name":"name",
            "amount":200000,
            "date":"08/07/2023"
            }
        GET  /expenses    Get all expenses
        Output
        [
            {
                "id": 1,
                "name": "Yaka",
                "amount": 20000,
                "date": "2023-08-07 00:00:00",
                "created_at": "2023-07-08T03:31:59.000000Z",
                "updated_at": "2023-07-08T03:31:59.000000Z"
            }
        ]
        </code></pre>


</div>
<script src="{{asset('js/prism.js')}}"></script>
</body>
</html>
