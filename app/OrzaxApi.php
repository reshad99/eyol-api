<?php

/**
 * @license Apache 2.0
 */

namespace App;



/**
 * 

* 
*     @OA\Info(
*     version="1.0.0",
*     title="Laravel API Documentation",
*     description="This is a sample API Documentation",
*     termsOfService="http://127.0.0.1:8000/api/terms",
*     @OA\Contact(email="test@gmail.com"),
*     @OA\License(name="Apache 2.0", url="http://www.apache.org")
*     )
* 
* @OA\Schema(
* schema="apiResponse",
* title ="apiResponse",
* type ="object",
* description="apiResponse",
* properties={
* @OA\Property(property="success", type="boolean"),
* @OA\Property(property="message", type="string"),
* @OA\Property(property="data", type="array",
*      @OA\Items(
*          type="object",
*          @OA\Property(property="product_id", type="integer", format="int64"),
*          @OA\Property(property="product_name", type="string"),
*      ),
* ),
* }
* )

* @OA\Schema(
* schema="fetchResponse",
* title ="fetchResponse",
* type ="object",
* description="fetchResponse",
* properties={
* @OA\Property(property="success", type="boolean"),
* @OA\Property(property="message", type="string"),
* @OA\Property(property="data", type="array",
*      @OA\Items(
*          type="object",
*          @OA\Property(property="product_id", type="integer", format="int64"),
*          @OA\Property(property="product_name", type="string"),
*      ),
* ),
* @OA\Property(property="total_page", type="integer", description="total_page column"),
* }
* )


* 
* @OA\Schema(
* schema="post",
* title ="post",
* type ="object",
* description="post",
* properties={
* @OA\Property(property="name", type="string"),
* @OA\Property(property="price", type="double"),
* }
* )

* @OA\Schema(
* schema="postResponse",
* title ="postResponse",
* type ="object",
* description="postResponse",
* properties={
* @OA\Property(property="success", type="boolean"),
* @OA\Property(property="message", type="string"),
* @OA\Property(property="data", type="array",
*     @OA\Items(
*       type="object",
*       @OA\Property(property="name", type="string"),
*       @OA\Property(property="price", type="double"),
*       @OA\Property(property="slug", type="string"),
*       @OA\Property(property="id", type="integer"),
*     )
* ),
* }
* )

* 
* @OA\Schema(
* schema="put",
* title ="Update",
* type ="object",
* description="Update data",
* properties={
* @OA\Property(property="name", type="string"),
* @OA\Property(property="price", type="double"),
* }
* )

* @OA\Schema(
* schema="putResponse",
* title ="putResponse",
* type ="object",
* description="putResponse",
* properties={
* @OA\Property(property="success", type="boolean"),
* @OA\Property(property="message", type="string"),
* @OA\Property(property="data", type="array",
*     @OA\Items(
*       type="object",
*       @OA\Property(property="name", type="string"),
*       @OA\Property(property="price", type="double"),
*       @OA\Property(property="slug", type="string"),
*       @OA\Property(property="id", type="integer"),
*     )
* ),
* }
* )

* @OA\SecurityScheme(
* type="apiKey",
* name ="api_token",
* securityScheme ="api_token",
* in="query",
* )

* 
* 
* 
*      * @OA\Get(
*    path="/api/products",
*    tags={"Product"},
*    summary="List all products",
*    @OA\Response(
*      response=200,
*      description="A paged array of products",
*    @OA\JsonContent(
*        type="object",
*        ref="#/components/schemas/fetchResponse"
*       )
*    ),
*    @OA\Response(
*      response=401,
*      description="Unauthorized",
*    ),
*    @OA\Response(
*      response="default",
*      description="Unexpected Error",
*    ),
*    @OA\Parameter(
*      name="limit",
*      in="query",
*      description="How many items to return at one time",
*      required=false,
*      @OA\Schema(type="integer",  format="int32")
*    ),
*    security={
*     {"api_token": {} }
*    }
*    
* )
* 
* 
* * @OA\Get(
*    path="/api/products/{productId}",
*    tags={"Product"},
*    summary="Info of a specific product",
*    operationId="show",
*    @OA\Parameter(
*      name="productId",
*      in="path",
*      description="Id parameter to retrieve specific data",
*    @OA\Schema(type="integer", format="int32")
*    ),
*    @OA\Response(
*      response=200,
*      description="A specific product",
*    @OA\JsonContent(
*        type="object",
*        ref="#/components/schemas/apiResponse",
*       )
*    ),
*    @OA\Response(
*      response=401,
*      description="Unauthorized",
*    ),
*    @OA\Response(
*      response="default",
*      description="Unexpected Error",
*    ),
*    security={
*     {"api_token": {} }
*    }
*   

*    
*    
* )

* 
* @OA\POST(
*    path="/api/products",
*    tags={"Product"},
*    summary="Create a product",
*    @OA\RequestBody(
*      required=true,
*      description="Store a product",
*    @OA\JsonContent(
*        ref="#/components/schemas/post"
*       )
*    ),
*    @OA\Response(
*      response=201,
*      description="Product created",
*    @OA\JsonContent(
*        type="object",
*        ref="#/components/schemas/postResponse",
*       )
*    ),
*    @OA\Response(
*      response=401,
*      description="Unauthorized",
*    ),
*    @OA\Response(
*      response="default",
*      description="Unexpected Error",
*    ),
*    
*    security={
*     {"api_token": {} }
*    }
*   
* )


* @OA\PUT(
*    path="/api/products/{productId}",
*    tags={"Product"},
*    summary="Create a product",
*    @OA\RequestBody(
*      required=true,
*      description="Store a product",
*    @OA\JsonContent(
*        ref="#/components/schemas/put"
*       )
*    ),
*    @OA\Parameter(
*      name="productId",
*      in="path",
*      required=true,
*      description="Id parameter to update data",
*    @OA\Schema(type="integer", format="int32")
*    ),
*    @OA\Response(
*      response=201,
*      description="Product created",
*    @OA\JsonContent(
*        type="object",
*        ref="#/components/schemas/postResponse",
*       )
*    ),
*    @OA\Response(
*      response=401,
*      description="Unauthorized",
*    ),
*    @OA\Response(
*      response="default",
*      description="Unexpected Error",
*    ),
*    
*    security={
*     {"api_token": {} }
*    }
*   
* )
*/ 



class OrzaxApi
{
}


