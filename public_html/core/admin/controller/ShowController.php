<?php
namespace core\admin\controller;

class ShowController extends BaseAdmin{

    protected function inputData($parameters){

        parent::inputData();

        if($parameters){
            $this->table = array_keys($parameters)[0];
        }else{
            $this->table = 'pages';
        }

        $res = $this->object_model->showColumns($this->table);


        if($res){
            foreach($res as $col){
                $columns[] = $col['Field'];
                if($col['Key'] == 'PRI') $fields['id_row'] = $col['Field'];
                if($col['Fields'] == 'name') $fields['name'] = $col['Field'];
                if($col['Fields'] == 'img') $fields['img'] = $col['Field'];
            }
        }
        if(in_array('menu_pos', $columns)){
            $order = 'menu_pos';
            $this->data = $this->object_model->get($this->table, [
                'fields' => ['id', 'name', 'img'],
                'order' => [$order]
            ]);
        }else{
            $this->data = $this->object_model->get($this->table, [
                'fields' => ['id', 'name', 'img']
            ]);
        }
    }

    protected function outputData(){
        
        $this->content = $this->render(ADMIN_TEMPLATE.'all', array(
                                                'data' => $this->data,
                                                'table' => $this->table
                                                ));

        $this->page = parent::outputData();
        return $this->page;

    }
}