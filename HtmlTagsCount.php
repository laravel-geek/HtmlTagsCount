<?php

// This is PHP developer test assignment. Time: 1 hour.

/* 
    Идея простая!
    1) чтобы не заморачиваться с регулярками и для упрощения задачи в качестве открывающего или одиночного тега возьмем "<tagname", закрывающие не ищем, хотя могли бы. 
    2) все "tagnames" - в массив 
    3) url - в строку и дальше поиск по строке
    4) понятно, что в реальных парсерах делается через curl или аналогичные инструменты, но для упрощения будем использовать file_get_contents.

    РЕШЕНИЕ 1 
    Ради такой простой задачи ООП, конечно, избыточно. Первое решение - показать, что обычная  функция отлично справляется. 

    РЕШЕНИЕ 2
    ООП-решение
*/


// РЕШЕНИЕ 1

function openTagsCount($url)
{
    $tagNames = ['a', 'b', 'blockquote', 'br', 'p', 'div', 'h1', 'h2', 'h3'];
    $html = file_get_contents($url);
    $html = strtolower($html);
    $count = 0;
    foreach ($tagNames as $tagName) {
        $count = substr_count($html, '<' . $tagName);
        $openTagsCount[$tagName] = $count;
    }
    return $openTagsCount;
}

// $tags = openTagsCount('http://www.knigochei.ru/');
// var_dump($tags); die;



// РЕШЕНИЕ 2
// два класса - граббер, парсер (можно было еще сделать класс для вывода)
// конечно, все должно быть в отдельных файлах, но так проще будет проверить


class Grabber
{
    private $url;
    public $html;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function grabHtml()
    {
        // переводим в нижний регистр, иначе будут проблемы с substr_count
        $this->html = strtolower(file_get_contents($this->url));
        return $this->html;
    }
}



// интерфейс зачем-то я решил вставить, но раз написали оверкодинг...
interface Taglist
{
    // в задании указано "всех тегов", все теги перепечатывать не стал, но и так понятно)
    const TAG = ['a', 'b', 'blockquote', 'br', 'p', 'div', 'h1', 'h2', 'h3'];

    public function count($html);
}



class TagsCount implements Taglist
{

    public $html;
    public $tagsCount;

    public function count($html)
    {
        foreach (self::TAG as $tagName) {
            $count = substr_count($html, '<' . $tagName);
            $tagsCount[$tagName] = $count;
        }
        return $tagsCount;
    }
}



$content  = new Grabber('http://www.knigochei.ru/');
$content->grabHtml();

$tags = new TagsCount();
$finalTags = $tags->count($content->html);


echo '<pre>';
var_dump($finalTags);
