<?php

namespace App\Classes\Common;

class Tree
{
    private $array = [];
    private $key_as = [];
    private $sub_key = '_sub';
    private $id_key = 'id';
    private $parent_key = 'parent_id';
    private $level_key = false;
    private $select_key = false;
    private $excluding = false;
    private $tree_mode = true;
    private $links_mode = false;
    private $links_all_mode = false;

    /**
     * Установить опцию - выводить плоским или деревом
     * @param $bool
     * @return $this
     */
    public function set_tree_mode($bool){
        $this->tree_mode = (bool)$bool;
        return $this;
    }
    /**
     * Установить обрабатываемый массив
     * @param $array
     * @return $this
     */
    public function set_array($array){
        $this->array = (is_array($array) && count($array)>0) ? $array : false;
        return $this;
    }
    /**
     * Установить ключ для уровня вложенности
     * (по умолчанию не установлен)
     * @param $key
     * @return $this
     */
    public function set_level_key($key){
        $this->level_key = (string)$key;
        return $this;
    }
    /**
     * Исключить поля
     * @param $bool
     * @return $this
     */
    public function excluding($bool){
        $this->excluding = (bool)$bool;
        return $this;
    }
    /**
     * Установить название ключа для массива дочрних элементов
     * (по умолчанию '_sub')
     * @param $key
     * @return $this
     */
    public function set_sub_key($key){
        $this->sub_key = (string)$key;
        return $this;
    }
    /**
     * Переименовать ключ поля
     * @param $key
     * @param $as
     * @return $this
     */
    public function set_key_as($key, $as){
        $this->key_as[(string)$key] = (string)$as;
        return $this;
    }
    /**
     * Установить ключ родителя
     * (по умолчанию 'parent_id')
     * @param $parent_key
     * @return $this
     */
    public function set_parent($parent_key){
        $this->parent_key = (string)$parent_key;
        return $this;
    }
    /**
     * Установить ключ id
     * (по умолчанию 'id')
     * @param $id_key
     * @return $this
     */
    public function set_id($id_key){
        $this->id_key = (string)$id_key;
        return $this;
    }
    /**
     * Установить поле для нумерации ключей в массиве
     * @param $select_key
     * @return $this
     */
    public function select_key($select_key){
        $this->select_key = (string)$select_key;
        return $this;
    }
    /**
     * Добавить в результирующий массив связи родитель-дети
     * @param $bool
     * @return $this
     */
    public function set_links_mode($bool){
        $this->links_mode = (bool)$bool;
        return $this;
    }
    /**
     * Добавить в результирующий массив связи родитель-(все дети)
     * @param $bool
     * @return $this
     */
    public function set_links_all_mode($bool){
        $this->links_all_mode = (bool)$bool;
        return $this;
    }
    /**
     * Запуск сборки дерева
     * @param int $parent_id
     * @param int $level
     * @return array|bool
     */
    public function build($parent_id = 0, $level = 0){
        $result = [];
        $level++;
        if(is_array($this->array) && count($this->array)>0){
            foreach ($this->array as $k => $item){
                if($this->level_key){
                    $item[$this->level_key] = $level;
                }
                if($item[$this->parent_key] == $parent_id){
                    $_sub = $this->build($item[$this->id_key], $level);
                    if(is_array($_sub) && count($_sub)>0){
                        if($this->tree_mode){
                            $item[$this->sub_key] = $_sub;
                        }
                    }
                    if(is_array($this->key_as) && count($this->key_as)>0){
                        foreach ($this->key_as as $key => $as) {
                            if(isset($item[$key])){
                                $item[$as] = $item[$key];
                                unset($item[$key]);
                            }
                        }
                    }
                    if($this->excluding){
                        unset($this->array[$k]);
                    }
                    if($this->select_key && isset($item[$this->select_key]) && !empty($item[$this->select_key])){
                        $result[$item[$this->select_key]] = $item;
                    }else{
                        $result[] = $item;
                    }
                    if(!$this->tree_mode){
                        if(is_array($_sub) && count($_sub)>0){
                            foreach($_sub as $_sub_item){
                                if($this->select_key && isset($_sub_item[$this->select_key]) && !empty($_sub_item[$this->select_key])){
                                    $result[$_sub_item[$this->select_key]] = $_sub_item;
                                }else{
                                    $result[] = $_sub_item;
                                }
                            }
                        }
                    }
                }
            }
            if($parent_id == 0){
                $res = [];
                $res['result'] = $result;
                if($this->links_mode && is_array($this->array) && count($this->array)>0) {
                    foreach($this->array as $k => $item) {
                        $res['_links'][$item[$this->parent_key]][$item[$this->id_key]] = $item[$this->id_key];
                    }
                }
                if($this->links_all_mode && is_array($this->array) && count($this->array)>0){
                    foreach($this->array as $k => $item) {
                        if($item[$this->parent_key] > 0){
                            $res['_links_all'][$item[$this->parent_key]] = $this->getAllChildrenIds($item[$this->parent_key]);
                        }
                    }
                }
                return $res;
            }
            return $result;
        }
        return false;
    }
    /**
     * Поиск всех детей (вместе с внуками и т д)
     * @param int $parent
     * @return array
     */
    public function getAllChildrenIds($parent = 0){
        $children = [];
        if (is_array($this->array) && count($this->array) > 0) {
            foreach ($this->array as $k => $item) {
                if($parent == $item[$this->parent_key]){
                    $children[$item[$this->id_key]] = $item[$this->id_key];
                    $children_children = $this->getAllChildrenIds($item[$this->id_key]);
                    if(is_array($children_children) && count($children_children)>0){
                        foreach($children_children as $child_key => $children_child){
                            $children[$child_key] = $children_child;
                        }
                    }
                }
            }
        }
        return $children;
    }
}