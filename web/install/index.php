<html lang="ru">
<head>
<meta charset="UTF-8"> 

<title>Установка базы и демо-данных</title>
 
</head>
<body>
 
<h1>  Установка базы и демо-данных  </h1>

<?php

if (isset($_POST["install"]) && isset($_POST["dbhost"]) && isset($_POST["dbuser"]) && isset($_POST["db_name"])) {


 
 
        $dbhost = $_POST["dbhost"];
        $dbuser = $_POST["dbuser"];
        $db_name = $_POST["db_name"];

        $dbpass = '';
        $conn =mysqli_connect($dbhost, $dbuser, $dbpass); 
               mysqli_set_charset($conn, "utf8");
 
        $db_selected = mysqli_select_db($conn, $db_name);


        if (!$db_selected) {

            $sql = 'CREATE DATABASE '. $db_name;

            if (mysqli_query($conn, $sql)) {
                $db_selected = mysqli_select_db($conn,  $db_name);

                echo "<h1>База " .$db_name. " успешно создана</h1>";
            } else {
                echo 'Ошибка создания базы: ' . mysqli_connect_error() . "\n";
            }
        }


        $link = mysqli_connect($dbhost, $dbuser, $dbpass, $db_name);
             
			  mysqli_set_charset($link, "utf8");
			  

        if (mysqli_connect_errno()) {
            printf("Fail to connect database: %s\n", mysqli_connect_error());
            exit();
        }


        $query_file =  'sql.sql';
        $sql = file_get_contents($query_file);
        $retval = mysqli_multi_query($link, $sql);

        if (!$retval) {
            die('Ошибка создания и заполнения таблиц: ' . mysqli_error());
        } else { 
            echo "<h1>Таблицы созданы и заполнены </h1>";
            echo "<h1><a href='/'>Перейти на сайт</a>  </h1>";
        }
    
    
    
    
    
    
} else {
    ?>
    <form method="POST" action="/install/" > 
        <table>
            <tr>
                <td width="200px"> Сервер  </td>
                <td> <input type="text"  name="dbhost" value="localhost"></td>

            </tr>
            <tr>
                <td> Имя пользователя  </td>
                <td> <input type="text"  name="dbuser" value="root"></td>

            </tr> 
            <tr>
                <td> Название базы  </td>
                <td> <input type="text"  name="db_name" value="test040416"></td>

            </tr> 
			
			 <tr>
                <td colspan="2"> *Значения по умолчанию уже прописаны в /config/db.php </td>
                 
            </tr> 
			
            <tr>
                <td>    </td>
                <td> <input type="submit"  name="install" value="Install"></td> 
            </tr>  
        </table> 
    </form>

    <?
}
?>
</body>
</html>

