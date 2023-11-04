<?php
function SlugCreateByText(string $text) {
  $transliteration = array(
    'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e',
    'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm',
    'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
    'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '',
    'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    'А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ё' => 'e',
    'Ж' => 'zh', 'З' => 'z', 'И' => 'i', 'Й' => 'y', 'К' => 'k', 'Л' => 'l', 'М' => 'm',
    'Н' => 'n', 'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't','У' => 'u',
    'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sch', 'Ъ' => '',
    'Ы' => 'y', 'Ь' => '', 'Э' => 'e', 'Ю' => 'yu', 'Я' => 'ya',
    ' ' => '-'
  );

  // Заменяем символы, которые не являются буквами или цифрами, на пробелы
  $text = preg_replace('/[^a-zA-Zа-яА-ЯЁё0-9]/u', ' ', $text);

  // Переводим текст в нижний регистр
  $text = mb_strtolower($text, 'UTF-8');

  // Транслитерируем русские символы
  $text = strtr($text, $transliteration);

  // Заменяем пробелы на дефисы
  $text = preg_replace('/\s+/', '-', $text);

  // Удаляем подряд идущие дефисы
  $text = preg_replace('/-{2,}/', '-', $text);
  $text = mb_substr($text, 0, 64);

  // Удаляем дефисы в начале и конце строки
  $text = trim($text, '-');

  return $text;
}
