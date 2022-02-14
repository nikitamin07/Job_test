/**
* Автор: Никита Минчуков
*
* Дата реализации: 14.02.2022 10:00
*
* Дата изменения: 14.02.2022 10:00
*
* Visual Studio Code
*/
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Название страницы</title>
    <meta name="description" content="Описание страницы" />
</head>

<body>
    <?php
	require_once 'Task_1.php';
    require_once 'Task_2.php';
    $tom = new Person(111, 'Вася', 'Пупкин', '2004-05-04', 1, 'Минск');  //для теста
    /*  $query = 'SELECT * FROM info_people';
    $result = mysqli_query($link, $query) or die("Запрос не удался: "
     . mysqli_error($link));
    echo "<table>\n";
    while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo "\t<tr>\n";
        foreach ($line as $col_value) {
            echo "\t\t<td>$col_value</td>\n";
        }
        echo "\t</tr>\n";
    }
    echo "</table>\n";
    mysqli_close($link);*/
    ?>
</body>

</html>