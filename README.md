# SQLitePDO
> #### The Sqlite Multi Generator Is Library works with Sqlite in PHP using PDO  . Ability to support multiple Sqlite object sets , Ability to report and record errors and correct operations.
> ##### Writen by <a href="https://t.me/wsudo"> @Wsudo </a>
> ##### in <a href="https://t.me/wsudo"> Win Tab </a> Team
 
# 1. installation
> ### installation in composer
>```
>composer require Wsudo/SQLitePDO
>```
>
>
>
> ## installation in single project
>> download the `src/SQLitePDO.php` in your project and use `require_once` to use it
>
> ### like this :
>> ```
>> <?php
>> require_once('src/SQLitePDO.php');
>> use \Wsudo\SQLitePDO;
>>```


# 2. Get Start At First Installation
> ```
> <?php
> require_once('src/SQLitePDO.php');
> use \Wsudo\SQLitePDO;
>
> $sqlite = new \Wsudo\SQLitePDO\SQLitePDOGenerator("first.SQLitePDO.db");
>
> $tableName = "users" ;
>
> $tableRowsData = [ 
>
>    'username varchar' ,
>
>    'password varchar' ,
>];
>
>$sqlite->createTable( $tableName  ,  $tableRowsData  );
>
>$sqlite->insert( $tableName , 
>    [
>        'username' => "Wsudo" ,
>
>        'password' => "Wsudo1000"
>    ]
> );
>
> $select = $sqlite->select( "users" , "username='Wsudo'" )->fetch( PDO::FETCH_ASSOC );
>
> var_dump( $select );
>
>```


# 3. Document
## 1.
 ```
 $SQLite  =  new \Wsudo\SQLitePDO\SQLitePDOGenerator("Single.SQLitePDO.db");
 ```
> ##### this can create a single SQlitePDO Object 
 #### 2. Creating Table By Single SQLitePDO Single Object
> ```
> $SQLite -> createTable ( -- Parameter 1 -- , -- Parameter 2 -- );
> ```
>> ###### -- Parameter 1 --
>> The Table Name To Create
>> ###### -- Parameter 2 --
>> The Table Columns Like = [ 'username varchar' , 'passowrd varchar' ];
 #### 3. Inserting Datas To Table By SQLitePDO Object
> ```
> $SQLite -> insert ( -- Parameter 1 -- , -- Parameter 2 -- );
> ```
>> ###### -- Parameter 1 --
>> The Table Name To Insert Datas
>> ###### -- Parameter 2 --
>> The Columns Datas To Insert Like = [ 'username' => "Wsudo" ] ;
> #### 4. Updating Datas From Table By Single SQLitePDO Object
> ```
> $SQLite -> update ( -- Parameter 1 -- , -- Parameter 2 -- , -- Parameter 3 --) ;
> ```
>> ###### -- Parameter 1 --
>> ###### The Table Name To Updating Datas
>> ###### -- Parameter 2 --
>> ###### The Datas to Updating Like = [ 'password' => "1234567" ] ;
>> ###### -- Parameter 3 --
>> ###### Optional , (WHERE) Commants To The Updating
>> #### example :
>> ```
>> $SQLite -> update ( "users" , [ 'password' => '123456' ] , "username='Wsudo'");
>> ```
 #### 5. Deleting Data From SQLitePDO Single Object
> ```
> $SQLite -> delete ( -- Parameter 1 -- , -- Parameter 2 -- , -- Parameter 3 -- );
> ```
>> ##### -- Parameter 1 --
>> ##### Table Name To Deleting Data
>> ##### -- Parameter 2 --
>> ##### Optional , (WHERE) Commants To The Deleting
>> #### example :
>> ```
>> $SQLite -> delete ( "users" , "username='Wsudo'" );
>> ```
 #### 6. Selecting Datas From Table By SQLitePDO Object
> ```
> $SQLite -> select ( -- Parameter 1 -- , -- Parameter 2 );
> ```
>> ##### -- Parameter 1 --
>> ##### Tavle Name To Select Datas
>> ##### -- Parameter 2 --
>> ##### Optional , (WHERE) Commants To The Selecting Datas
>> #### example :
>> ```
>> $SQLite -> select ( "users" , "username='Wsudo'" )->fetch(PDO::FETCH_ASSOC);
>> ```








