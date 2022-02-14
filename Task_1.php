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
$link = mysqli_connect('localhost', 'root', '97166687206856', 'info');
class Person
{
    /**
     * Класс 'Человек'
     * Конструктор класса либо создает человека в БД с заданной
     * информацией, либо берет информацию из БД по id
     * (В зависимости от того, есть ли человек с данным id в базе).
     * Присутствуют static методы для преобразования даты рождения 
     * в возраст (полных лет) и для преобразования пола из двоичной 
     * системы в текстовую.
     * Также есть нестатический метод для форматирования человека
     * с преобразованием возраста и (или) пола.
     */
    public int $Id;
    public string $name;
    public string $surname;
    public $birthdate;
    public bool $sex;
    public string $city;

    public function save_into_DB()
    {
        $link = mysqli_connect('localhost', 'root', '97166687206856', 'info');
        if ($link == false) {
            echo "Ошибка: Невозможно подключиться к MySQL." . mysqli_connect_error();
        } else {
            $query = "INSERT INTO info_people (id, name, surname, birthday, sex, city) values "
                . "({$this->Id},'{$this->name}','{$this->surname}',"
                . "'{$this->birthdate}',{$this->sex},'{$this->city}')";
            $result = mysqli_query($link, $query);
            if (!$result) {
                echo "<br>Произошла ошибка при выполнении запроса: " . mysqli_error($link);
            } else {
                echo "<br>Экземпляр добавлен";
            }
        }
        mysqli_close($link);
    }

    public function delete_person_by_id()
    {
        $link = mysqli_connect('localhost', 'root', '97166687206856', 'info');
        if ($link == false) {
            echo "Ошибка: Невозможно подключиться к MySQL. " . mysqli_connect_error();
        } else {
            $query = "DELETE FROM info_people WHERE id = {$this->Id}";
            $result = mysqli_query($link, $query);
            if (!$result) {
                echo "<br>Произошла ошибка при выполнении запроса: " . mysqli_error($link);
            } else {
                echo "<br>Человек удален из БД";
            }
        }
        mysqli_close($link);
    }

    public static function birthdate_into_age($example_Id)
    {
        /**
         * Метод 'Дата рождения в возраст'
         * Преобразование даты рождения в возраст (полных лет)
         */
        $link = mysqli_connect('localhost', 'root', '97166687206856', 'info');
        if ($link == false) {
            echo "Ошибка: Невозможно подключиться к MySQL." . mysqli_connect_error();
        } else {
            $query = "SELECT birthday FROM info_people WHERE id = {$example_Id}";
            $result = mysqli_query($link, $query);
            if (!$result) {
                echo "Произошла ошибка при выполнении запроса: " . mysqli_error($link);
            } else {
                $line = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $birthdate = strtotime(array_shift($line));
                if (date('m', $birthdate) >= date('m') && date('d', $birthdate) > date('d')) {
                    $age = date('Y') - date('Y', $birthdate) - 1;
                } else {
                    $age = date('Y') - date('Y', $birthdate);
                }
                echo "<br>Возраст пользователя с Id {$example_Id} : {$age} лет";

                return [$age, date('Y-m-d', $birthdate)];
            }
        }
        mysqli_close($link);
    }

    public static function convert_bool_to_text($example_Id)
    {
        /**
         * Класс 'Из двоичной в текстовую'
         * преобразование пола из двоичной системы в текстовую (муж, жен)
         */
        $link = mysqli_connect('localhost', 'root', '97166687206856', 'info');
        if ($link == false) {
            echo "Ошибка: Невозможно подключиться к MySQL." . mysqli_connect_error();
        } else {
            $query = "SELECT sex FROM info_people WHERE id = {$example_Id}";
            $result = mysqli_query($link, $query);
            if (!$result) {
                echo "Произошла ошибка при выполнении запроса: " . mysqli_error($link);
            } else {
                $line = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $sex_to_convert = array_shift($line);
                echo "\nПол пользователя с Id {$example_Id} : ";
                if (!$sex_to_convert) {
                    echo 'женский';
                    return 'жен';
                } else {
                    echo 'мужской';
                    return 'муж';
                }
            }
        }
        mysqli_close($link);
    }

    public function __construct(
        int $example_Id,
        string $example_name,
        string $example_surname,
        string $example_birthdate,
        bool $example_sex,
        string $example_city
    ) {
        /**
         * TODO:
         * [*] Исправить валидацию данных. Валидация при помощи preg_match()
         * не работает с русскими буквами, видимо что-то не так с кодировкой.
         * Других вариантов валидации, увы, не знаю.
         */

        /*$flag_validation = 0;
            $errors = '';
            if($example_Id<1){
                $errors .= "<br>Неверно введен id.";
                $flag_validation = 1;
            }       
            if (!preg_match('/^[А-ЯЁ]{1}[а-яё]{29}+$/ui', $example_name)) {
                $errors .= "<br>Неверно введено имя.";
                $flag_validation = 1;
            }
            if (!preg_match('/^[А-ЯЁ]{1}[а-яё]{29}+$/ui', $example_surname)) {
                $errors .= "<br>Неверно введена фамилия.";
                $flag_validation = 1;
            }
            if (!preg_match('/[А-Яа-я]{29}/u', $example_city)) {
                $errors .= "<br>Неверно введен город.";
                $flag_validation = 1;
            }
            if ($example_sex != 1 || $example_sex != 0) {
                $errors .= "<br>Неверно введен пол.";
                $flag_validation = 1;
            }
            if (!preg_match("/(19[0-9]{2}|20[0-1][0-9])(-|:|.)(0[1-9]|1[0-2])"
                    . "(-|:|.)(0[1-9]|[1-2][0-9]|3[0-1])/", $example_birthdate)
                || !preg_match("/(0[1-9]|[1-2][0-9]|3[0-1])(-|:|.)(0[1-9]|1[0-2])"
                    . "(-|:|.)(19[0-9]{2}|20[0-2][0-2])$/", $example_birthdate)) {
                $errors .= "<br>Неверно введена дата рождения.";
                $flag_validation = 1;
            }*/
        $this->Id = $example_Id;
        $this->name = $example_name;
        $this->surname = $example_surname;
        $this->birthdate = Date('Y-m-d', strtotime($example_birthdate));
        $this->sex = $example_sex;
        $this->city = $example_city;
        $link = mysqli_connect('localhost', 'root', '97166687206856', 'info');
        if ($link == false) {
            echo "Ошибка: Невозможно подключиться к MySQL." . mysqli_connect_error();
        } else {
            $query = "SELECT id FROM info_people";
            $result = mysqli_query($link, $query);
            if (!$result) {
                echo "Произошла ошибка при выполнении запроса: " . mysqli_error($link);
            } else {
                $flag = 0;                   //in_array() по неизвестной мне причине не сработал,  
                while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {     //поэтому 
                    foreach ($line as $value) { //перебираю значения массива и при совпадении
                        if ($value == $this->Id) {            //изменяю значение $flag на 1
                            $flag = 1;
                        }
                    }
                }
                if ($flag) {
                    echo "<br>В базе данных уже есть элемент с данным Id:<br>";
                    $query = "SELECT * FROM info_people WHERE id = {$this->Id}";
                    $result = mysqli_query($link, $query);
                    if (!$result) {
                        echo "<br>Произошла ошибка при выполнении запроса: " . mysqli_error($link);
                    } else {
                        $row = $result->fetch_assoc();
                        $this->name = $row['name'];
                        $this->surname = $row['surname'];
                        $this->birthdate = Date('Y-m-d', strtotime($row['birthday']));
                        $this->sex = $row['sex'];
                        $this->city = $row['city'];
                    }
                } else {
                    $this->save_into_DB();
                }
            }
        }
        mysqli_close($link);
    }

    public function format_person($sex_to_change, $age_to_change)
    {
        /**
         * Метод 'Форматировать человека'
         * Форматирование человека с преобразованием возраста и (или) пола
         * в зависимотси от параметров
         * $age_to_be_changed - возраст, которыЙ нужно ИЗМЕНИТЬ
         * $age_to_change - возраст, которыМ нужно ЗАМЕНИТЬ (аналогично с полом)
         */
        $link = mysqli_connect('localhost', 'root', '97166687206856', 'info');
        if ($link == false) {
            echo "Ошибка: Невозможно подключиться к MySQL." . mysqli_connect_error();
        } else {
            $sex_to_be_changed = Person::convert_bool_to_text($this->Id);
            [$age_to_be_changed, $birthdate_to_be_changed] = Person::birthdate_into_age($this->Id);
            $birthdate_to_change = $birthdate_to_be_changed;
            if ($age_to_be_changed != $age_to_change) {
                $age_difference = $age_to_be_changed - $age_to_change;
                $birthdate_to_be_changed = date_create($birthdate_to_be_changed);
                date_modify($birthdate_to_be_changed, "{$age_difference} year");
                $birthdate_to_change = date_format($birthdate_to_be_changed, 'Y-m-d');
                $this->birthdate = $birthdate_to_change;
            }
            if ($sex_to_change == 'муж') {
                $sex_to_change_bool = 1;
            } else {
                $sex_to_change_bool = 0;
            }
            if (
                $sex_to_be_changed == $sex_to_change &&
                $age_to_be_changed == $age_to_change
            ) {
                echo "<br>Введенные данные совпадают с данными в базе";
            } elseif (
                ($sex_to_be_changed != $sex_to_change) &&
                ($age_to_be_changed != $age_to_change)
            ) {
                $query = "UPDATE info_people SET sex = {$sex_to_change_bool}," .
                    " birthday = '{$birthdate_to_change}' WHERE id = {$this->Id}";
                $result = mysqli_query($link, $query);
                if (!$result) {
                    echo "Произошла ошибка при выполнении запроса: " . mysqli_error($link);
                } else {
                    echo "<br>Возраст и пол человека изменены";
                }
            } elseif (
                ($sex_to_be_changed != $sex_to_change) &&
                ($age_to_be_changed == $age_to_change)
            ) {
                $query = "UPDATE info_people SET sex = {$sex_to_change_bool}"
                    . " WHERE id = {$this->Id}";
                $result = mysqli_query($link, $query);
                if (!$result) {
                    echo "Произошла ошибка при выполнении запроса: " . mysqli_error($link);
                } else {
                    echo "<br>Пол человека изменен. Введенный возраст совпадает"
                        . " с возрастом человека в базе данных";
                }
            } else {
                $query = "UPDATE info_people SET birthday = '{$birthdate_to_change}'"
                    . " WHERE id = {$this->Id}";
                $result = mysqli_query($link, $query);
                if (!$result) {
                    echo "Произошла ошибка при выполнении запроса: " . mysqli_error($link);
                } else {
                    echo "<br>Возраст человека изменен. Введенный пол совпадает"
                        . " с полом человека в базе данных";
                }
            }
            $this->sex = $sex_to_change_bool;
        }
        mysqli_close($link);
    }

}