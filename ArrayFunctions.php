<?php

class ArrayFunctions
{
    /**
     * Группирует данные по нескольким ключам, формируя многоуровневый массив.
     * По последнему ключу из $keys будет сгруппированный массив по подходящим последовательно значениям $keys.
     * Если $value_key === null - там будут сами записи целиком
     * Если $value_key - строка, то там будет массив из значений по этому ключу
     * Если $value_key - массив, то там будут сами записи, но только эти указанные ключи.
     *
     * @param array $array
     * @param string|array $keys
     * @param string|string[]|null $value_key
     *
     * @return array
     */
    public static function groupIn(array $array, string|array $keys, string|array|null $value_key = null): array
    {
        $result = [];
        if (!isset($value_key)) {
            foreach ($array as $v) {
                $inner = &$result;
                foreach ((array)$keys as $key) {
                    $inner = &$inner[$v[$key]];
                }
                $inner[] = $v;
            }
        } elseif(is_array($value_key)) {
            $array_flip = array_flip($value_key);
            foreach ($array as $v) {
                $inner = &$result;
                foreach ((array)$keys as $key) {
                    $inner = &$inner[$v[$key]];
                }
                $inner[] = array_intersect_key($v, $array_flip);
            }
        } else {
            foreach ($array as $v) {
                $inner = &$result;
                foreach ((array)$keys as $key) {
                    $inner = &$inner[$v[$key]];
                }
                $inner[] = $v[$value_key];
            }
        }
        return $result;
    }

    /**
     * Группирует данные по нескольким ключам, формируя многоуровневый массив.
     * По последнему ключу из $keys будет одна запись, подходящая последовательно по значениям $keys.
     * Если $value_key === null - там будут сама записи целиком
     * Если $value_key - строка, то там будет значение по этому ключу
     * Если $value_key - массив, то там будут массив, состоящий из значений по указанным ключам.
     *
     * @param array $array
     * @param string|array $keys
     * @param string|string[]|null $value_key
     * @return array
     */
    public static function columnMulti(array $array, string|array $keys, string|array|null $value_key = null): array
    {
        $result = [];
        if (!isset($value_key)) {
            foreach ($array as $v) {
                $inner = &$result;
                foreach ((array)$keys as $key) {
                    $inner = &$inner[$v[$key]];
                }
                $inner = $v;
            }
        } elseif(is_array($value_key)) {
            $array_flip = array_flip($value_key);
            foreach ($array as $v) {
                $inner = &$result;
                foreach ((array)$keys as $key) {
                    $inner = &$inner[$v[$key]];
                }
                $inner = array_intersect_key($v, $array_flip);
            }
        } else {
            foreach ($array as $v) {
                $inner = &$result;
                foreach ((array)$keys as $key) {
                    $inner = &$inner[$v[$key]];
                }
                $inner = $v[$value_key];
            }
        }
        return $result;
    }

    /**
     * Сортирует массив по ключу
     * @param array       $array
     * @param string|null $key
     * @param bool        $desc
     *
     * @return array
     */
    public static function sortByColumn(array $array, string $key = null, bool $desc = false): array
    {
        if (isset($key)) {
            uasort($array, static function ($a, $b) use ($key, $desc) {
                return $desc ? $b[$key] <=> $a[$key] : $a[$key] <=> $b[$key];
            });
        } else {
            uksort($array, static function ($a, $b) use ($desc) {
                return $desc ? $b <=> $a : $a <=> $b;
            });
        }
        return $array;
    }

    /**
     * Фильтрует массив, оставляя только указанные ключи
     * @param $array
     * @param $whitelist
     *
     * @return array
     */
    public static function whiteList(array $array, array $whitelist)
    {
        return array_intersect_key($array, array_flip($whitelist));
    }

    /**
     * Возвращает первое значение по указанным значениям из $keys, которое определено и не null.
     * Если таких значений нет, то возвращает $defaultValue
     *
     * @param array $array
     * @param array $keys
     * @param mixed $defaultValue
     *
     * @return mixed|null
     */
    public static function coalesceKeys(array $array, array $keys, $defaultValue = null)
    {
        foreach ($keys as $key) {
            if (isset($array[$key])) {
                return $array[$key];
            }
        }
        return $defaultValue;
    }
}