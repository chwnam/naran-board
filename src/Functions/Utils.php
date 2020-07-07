<?php

namespace Naran\Board\Functions;

/**
 * 주어진 문자열로 시작하는지 검사.
 *
 * @param string $haystack 검사할 문자열
 * @param string $needle   시작하는 문자열
 *
 * @return bool
 */
function strStartsWith($haystack, $needle)
{
    return $needle === '' || strpos($haystack, $needle) === 0;
}


/**
 * 주어진 문자열로 끝나는지 검사.
 *
 * @param string $haystack
 * @param string $needle
 *
 * @return bool
 */
function strEndsWith($haystack, $needle)
{
    return
        $needle === '' ||
        (
            (($h = strlen($haystack)) >= ($n = strlen($needle))) &&
            substr($haystack, $h - $n) === $needle
        );
}


/**
 * 문자열 표기법을 스네이크 케이스로 변경
 *
 * thisIsASnakeCasedSentence ==> this_is_a_snake_cased_sentence
 *
 * @param string $string 입력 단어.
 * @param string $glue   띄어쓰기 문자. 기본은 언더바 '_'
 *
 * @return string
 */
function toSnakeCase($string, $glue = '_')
{
    return strtolower(preg_replace('/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', $glue, $string));
}


/**
 * 문자열 표기법을 파스칼 케이스로 변경.
 *
 * this_is_a_pascal_cased_sentence ==> ThisIsAPascalCasedSentence
 *
 * @param string $string 입력 단어.
 * @param string $glue   띄어쓰기 문자. 기본은 언더바 '_'
 *
 * @return string
 */
function toPascalCase($string, $glue = '_')
{
    return str_replace($glue, '', ucwords($string, $glue));
}


/**
 * 문자열 표기법을 카멜 표기법으로 변경.
 *
 * this_is_a_camel_cased_sentence ==> thisIsACamelCasedSentence
 *
 * @param string $string 입력 단어.
 * @param string $glue   띄어쓰기 문자. 기본은 언더바 '_'
 *
 * @return string
 */
function toCamelCase($string, $glue = '_')
{
    return lcfirst(toPascalCase($string, $glue));
}