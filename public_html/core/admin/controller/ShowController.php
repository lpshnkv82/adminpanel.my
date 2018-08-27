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
            $fields = [];
            foreach($res as $col){
                $columns[] = $col['Field'];
                if($col['Key'] == 'PRI') $fields['id_row'] = $col['Field'] . ' as id';
                if(!$fields['name']){
                    if(mb_strpos($col['Field'], 'name') !== false) $fields['name'] = $col['Field'] . ' as name';
                }

                if(!$fields['img']){
                    if(mb_strpos($col['Field'], 'img') !== false) $fields['img'] = $col['Field'] . ' as img';
                }
            }
        }

        if($columns && $fields){
            if(in_array('menu_pos', $columns)){
                $order = 'menu_pos';
                $this->data = $this->object_model->get($this->table, [
                    'fields' => $fields,
                    'order' => [$order]
                ]);
            }else{
                $this->data = $this->object_model->get($this->table, [
                    'fields' => $fields
                ]);
            }
        }else{
            $this->redirect(PATH.ADMIN_PATH);
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