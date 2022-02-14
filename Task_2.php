/**
* Автор: Никита Минчуков
*
* Дата реализации: 14.02.2022 10:00
*
* Дата изменения: 14.02.2022 10:00
*
* Visual Studio Code
*/
<?php
require_once 'Task_1.php';
if (class_exists('Person')) {
    class Arrays
    {

        /**
         * Класс 'Массивы'
         * Конструктор класса ведет поиск id людей по всем полям БД
         * и удаляет id из массива, если в базе нет такого id.
         * Имеются нестатический метод для получения массива
         * экземпляров класса 1 из массива с id людей полученного в конструкторе
         * и метод для удаления людей из БД с помощью экземпляров класса 1 в
         * соответствии с массивом, полученным в конструкторе.
         */

        public array $IdArray = array();

        public function __construct(array $exampleArray)
        {
            $this->IdArray = $exampleArray;
            $link = mysqli_connect('localhost', 'root', '97166687206856', 'info');
            if ($link == false) {
                echo "Ошибка: Невозможно подключиться к MySQL. " . mysqli_connect_error();
            } else {
                for ($i = 0; $i < count($this->IdArray); $i++) {
                    $flag = 0;
                    $query = "SELECT * FROM info_people";
                    $result = mysqli_query($link, $query) or die("Запрос не удался: "
                        . mysqli_error($link));
                    while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        foreach ($line as $value) {
                            if ($value == $this->IdArray[$i]) {
                                $flag = 1;
                                echo "<br>id №{$i} Найден.";
                            }
                        }
                    }
                    if ($flag == 0) {
                        echo "<br>id №{$i} Не найден. Удален из массива.";
                        array_splice($this->IdArray, $i, 1);
                        $i--;
                    }
                }
                if ($this->IdArray) {
                    $this->get_example_class1();
                    $this->delete_person_by_example();
                }
            }
            mysqli_close($link);
        }

        public function get_example_class1()
        {
            $example_array = array();
            for ($i = 0; $i < count($this->IdArray); $i++) {
                $example_array[$i] = new Person($this->IdArray[$i], '', '', '', 1, '');
            }
            return $example_array;
        }

        public function delete_person_by_example()
        {
            $example_array = $this->get_example_class1();
            $link = mysqli_connect('localhost', 'root', '97166687206856', 'info');
            if ($link == false) {
                echo "Ошибка: Невозможно подключиться к MySQL. " . mysqli_connect_error();
            } else {
                for ($i = 0; $i < count($example_array); $i++) {
                    $person_example = $example_array[$i];
                    $query = "DELETE FROM info_people WHERE id = {$person_example->Id}";
                    $result = mysqli_query($link, $query);
                    if (!$result) {
                        echo "<br>Произошла ошибка при выполнении запроса: " . mysqli_error($link);
                    } else {
                        echo "<br>Человек удален из БД";
                    }
                }
            }
            mysqli_close($link);
        }

    }
} else {
    echo "Ошибка при подключении к классу 1";
}
