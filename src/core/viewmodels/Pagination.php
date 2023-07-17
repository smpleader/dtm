<?php
/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace DTM\core\viewmodels;  

use SPT\Web\ViewModel;

class Pagination extends ViewModel
{
    public static function register()
    {
        return ['widget' => 'pagination'];
    }

    public function pagination($layoutData, $viewData)
    {
        $total = 0;
        if( isset($viewData['list']) )
        {
            $list = $viewData['list'];
            $page = $viewData['page'];
            
            $total = $list->getTotal();
            return [
                'total' => $total,
                'page' => $page ? $page : 1,
                'totalPage' => $list->getTotalPage(),
                'limit' => $list->getLimit(),
                'path_current' => $this->router->get('actualPath'),
            ];
        }
        return [
            'total' => 0,
        ];
    }
}
