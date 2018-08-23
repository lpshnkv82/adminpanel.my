<?php

class Pager{
    protected $page;
    protected $tableName;
    protected $where;
    protected $order;
    protected $asc;
    protected $match;
    protected $operand;
    protected $postNumber;
    protected $linkNumber;
    protected $inst_driver;
    protected $totalCount;
    protected $fields;

    public function __construct($page,
                                $table_name,
                                $post_number,
                                $link_number,
                                $fields = array(),
                                $where = array(),
                                $order = '',
                                $asc = '',
                                $operand = '=',
                                $match = array()
                                ){
        $this->page = $page;
        $this->tableName = $table_name;
        $this->fields = $fields;
        $this->where = $where;
        $this->order = $order;
        $this->asc = $asc;
        $this->match = $match;
        $this->postNumber = $post_number;
        $this->linkNumber = $link_number;
        $this->operand = $operand;

        $this->testProperties();
        $this->inst_driver = ModelDriver::getInstance();
    }

    protected function testProperties(){

            if(!empty($this->fields)) {
                $fields = '';
                foreach ($this->fields as $value) {
                    if (empty($fields)) {
                        $fields = "$value";
                    } else {
                        $fields .= ", $value";
                    }
                }
                $this->fields = $fields;
            }else{
                $this->fields = '*';
            }

        if(!empty($this->where)){
            $where = '';
            foreach($this->where as $key => $value){
                if(empty($where)){
                    $where = "WHERE $key $this->operand '$value'";
                }else{
                    $where .= " AND WHERE $key $this->operand '$value'";
                }
            }
            $this->where = $where;
        }else{
            $this->where = '';
        }

        if(!empty($this->order)){
            $order = "ORDER BY $this->order";
            $this->order = $order;
        }
        if(!empty($this->match)){
            $key = array();
            $key = array_keys($this->match);
            $match_search = 'MATCH('.$key[0].') AGAINST("'.$this->match[$key[0]].'*" IN BOOLEAN MODE)';
            if($this->where){
                $this->match = ' AND '.$match_search;
            }else{
                $this->match = 'WHERE '.$match_search;
            }
        }else{
            $this->match = '';
        }
    }

    protected function getPagesNumber(){

        $total = $this->getTotal();
        $number_pages = (int)($total/$this->postNumber);

        if(($total%$this->postNumber) != 0){
            $number_pages++;
        }
        return $number_pages;

    }

    public function getTotal(){
        if(!$this->totalCount){

            $query = "SELECT COUNT(*) as count FROM $this->tableName $this->where $this->order";
            $res = $this->inst_driver->select($query);

            $this->totalCount = $res[0]['count'];
        }

        return $this->totalCount;
    }

    public function getPosts(){

        $number_pages = $this->getPagesNumber();

        if($this->page <= 0 || $this->page > $number_pages){
            return false;
        }

        $start = ($this->page - 1) * $this->postNumber;

        $query = "SELECT $this->fields FROM $this->tableName $this->where $this->match $this->order $this->asc LIMIT $start, $this->postNumber";

        $res = $this->inst_driver->select($query);

        return $res;
    }

    public function getPagination(){

        $number_pages = $this->getPagesNumber();

        if ($number_pages == 1 || $this->page > $number_pages) {
            return false;
        }

        $res = array();

        if ($this->page != 1) {
            $res['first'] = 1;
            $res['back'] = $this->page - 1;
        }

        if($this->page > $this->linkNumber + 1){
            for($i = $this->page - $this->linkNumber; $i < $this->page; $i++){
                $res['previos'][] = $i;
            }
        }else{
            for($i = 1; $i < $this->page; $i++){
                $res['previos'][] = $i;
            }
        }

        $res['current'] = $this->page;

        if($this->page + $this->linkNumber < $number_pages){
            for($i = $this->page + 1; $i <= $this->page + $this->linkNumber; $i++){
                $res['next'][] = $i;
            }
        }else{
            for($i = $this->page + 1; $i <= $number_pages; $i++){
                $res['next'][] = $i;
            }
        }

        if ($this->page != $number_pages) {
            $res['forward'] = $this->page + 1;
            $res['last'] = $number_pages;
        }

        return $res;
    }
}