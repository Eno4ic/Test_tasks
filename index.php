<?php

// Задание 1

function generateAnons($text, $link_path){
    // Получаем текст статьи и ссылку на нее
    // Готовим тег для ссылки
    $anons_link = '<a href="' . $link_path . '">';
    $anons_text = mb_substr($text, 0, 230);
    $anons_words = explode(' ', $anons_text);
    // Берем последние 3 слова из будущего анонса и вставляем их в ссылку
    for ($i = count($anons_words)-3; $i < count($anons_words)-1; $i++) { 
        $anons_link .= $anons_words[$i] . ' ';
    }
    // Создаем переменную анонс и заполняем ее словами за исключением 3 последних
    $anons = "";
    for ($i = 0; $i < count($anons_words)-3; $i++) { 
        $anons .= $anons_words[$i] . ' ';
    }
    // Меняем последние 3 символа в ссылке на многоточие и добавляем завершающий тег
    $anons_link = mb_substr($anons_link, 0, -4) . '...';
    $anons .= $anons_link . '</a>';
    echo $anons;
};



// Это просто пример для проверки
//$text = "Для того, чтобы это выяснить, FAA заказало анализ ситуации у Aerospace Corporation, некоммерческой организации, которая управляет финансируемым из федерального бюджета Центром исследований и разработок. Согласно техническому отчёту Aerospace, годовая вероятность того, что один или несколько человек на борту самолёта пострадают или погибнут из-за столкновения с обломками космического корабля в 2021 году, составит 0,1 процента. Это во многом зависит от количества возвращающегося на Землю мусора. По оценкам Aerospace, каждый космический корабль SpaceX может произвести три куска мусора по 300 граммов каждый.  Соответственно, фрагментов становится больше, и риск возрастает. Крупнейшей группировкой спутников на данный момент является группировка SpaceX Starlink. SpaceX заявляет, что её космический корабль полностью подлежит утилизации, что означает отсутствие уцелевших частей. FCC приняла заявление SpaceX об отсутствии мусора на орбите.";
//generateAnons($text,'/');


// Задание 2

// Создание

/*
 CREATE TABLE Authors (ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                       name VARCHAR(100) NOT NULL,
                       last_name VARCHAR(100) NOT NULL);


 CREATE TABLE Articles (ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        title VARCHAR NOT NULL,
                        anons VARCHAR NOT NULL,
                        text TEXT NOT NULL,
                        tag  VARCHAR(30));


 CREATE TABLE Authors_Articles (Article_ID INT,
                                Author_ID INT,
                                FOREIGN KEY (Article_ID) REFERENCES Articles(ID),
                                FOREIGN KEY (Author_ID) REFERENCES Authors(ID));


 CREATE TABLE Likes (Article_ID INT,
                     likes INT,
                     FOREIGN KEY (Article_ID) REFERENCES Articles(ID));


 CREATE TABLE Comments (Article_ID INT,
                        comment VARCHAR NOT NULL,
                        FOREIGN KEY (Article_ID) REFERENCES Articles(ID));
*/

// Наполнение

/*
 INSERT INTO Authors (name, last_name) VALUES ('Alex', 'First'), ('Sergey', 'Second');

 INSERT INTO Articles (title, anons, text) VALUES ('Article One', 'About Article One', 'Text Article One'), 
                                                  ('Article Two', 'About Article Two', 'Text Article Two');

 INSERT INTO Authors_Articles (Author_ID, Article_ID) VALUES (0, 1), (1, 0)

 INSERT INTO Likes (Article_ID, likes) VALUES (0, 5), (1, 10);

 INSERT INTO Comments (Article_ID, comment) VALUES (0, 'Comment One'), (1, 'Comment Two');
*/

// Запрос

/*
SELECT L.likes, C.comment 
FROM  Authors 
INNER JOIN Authors_Articles AS AA
ON Authors.ID = AA.Author_ID 
INNER JOIN Likes AS L
ON L.Article_ID = AA.Article_ID
INNER JOIN Comments AS C
ON C.Article_ID = AA.Article_ID 
WHERE Authors.ID = 0')
*/



// Задание 3

function Anonymous($encrypted_word, $code_word){
    // Принимаем зашифрованное слово и кодовое слово
    // Задаем словарь
    $dictionary = array('|'=>'000000','000000'=>'|','А'=>'000001','000001'=>'А','Б'=>'000010','000010'=>'Б',
                        'В'=>'000011','000011'=>'В','Г'=>'000100','000100'=>'Г','Д'=>'000101','000101'=>'Д',
                        'Е'=>'000110','000110'=>'Е','Ж'=>'000111','000111'=>'Ж','З'=>'001000','001000'=>'З',
                        'И'=>'001001','001001'=>'И','К'=>'001010','001010'=>'К','Л'=>'001011','001011'=>'Л',
                        'М'=>'001100','001100'=>'М','Н'=>'001101','001101'=>'Н','О'=>'001110','001110'=>'О',
                        'П'=>'001111','001111'=>'П','Р'=>'010000','010000'=>'Р','С'=>'010001','010001'=>'С',
                        'Т'=>'010010','010010'=>'Т','У'=>'010011','010011'=>'У','Ф'=>'010100','010100'=>'Ф',
                        'Х'=>'010101','010101'=>'Х','Ц'=>'010110','010110'=>'Ц','Ч'=>'010111','010111'=>'Ч',
                        'Ш'=>'011000','011000'=>'Ш','Щ'=>'011001','011001'=>'Щ','Ъ'=>'011010','011010'=>'Ъ',
                        'Ы'=>'011011','011011'=>'Ы','Ь'=>'011100','011100'=>'Ь','Э'=>'011101','011101'=>'Э',
                        'Ю'=>'011110','011110'=>'Ю','Я'=>'011111','011111'=>'Я');
    // Задаем переменные
    $decrypted_word = '';
    $second_iter = 0;
    $encrypted_word = mb_str_split($encrypted_word);
    $code_word = mb_str_split($code_word);
    for ($i = 0; $i < count($encrypted_word); $i++) {
        // Итерируемся по индексам зашифрованного слова

        // Проверка закончилось ли кодовое слово, если закончилось, то идем заново
        if($second_iter == count($code_word)){
            $second_iter = 0;
        };
        // Получаем код от символа зашифрованного слова и кодового слова в текущей итерации 
        $enc_symbol = $dictionary[$encrypted_word[$i]];
        $code_symbol = $dictionary[$code_word[$second_iter]];
        $decrypted_symbol = '';

        // Итерируемся по символам кода и на выходе получаем код символа расшифрованного слова
        for ($j = 0; $j < 6; $j++) { 
            if(($enc_symbol[$j] == '0' && $code_symbol[$j] == '1') || ($enc_symbol[$j] == '1' && $code_symbol[$j] == '0')){
                $decrypted_symbol .= '1';
            }
            else {
                $decrypted_symbol .= '0';
            }
        }
        // Прибовляем символ к расшифрованному слову, соответствующий полученному коду 
        $decrypted_word .= $dictionary[$decrypted_symbol];

        $second_iter++;
    }
    // Выводим результат
    echo $decrypted_word;
};


// Примеры для проверки
//Anonymous('А|ЛРУМЭЩГЮ|СБЯХВ', 'ПОДРЯДЧИК');
//Anonymous('РЗМШМГЮПЬДЕКБГЧЛВШ', 'СЕМИДЕСЯТИМГОЛЬНИК');