<?php

/**
 * Menu controller
 */
namespace App\Controller;
use App\App;
use App\Controller\Controller;
use mikehaertl\wkhtmlto\Pdf;
class Menu extends Controller {

    public function generateMenu($id) {
        $templates = App::getTemplater();
        $menu = App::model('menu')->load($id);
        $pdf = new Pdf(['page-size' => 'A5']);

        $items = App::model('item')
            ->getCollection()
            ->filter('menu',$menu->getId())
            ->filter('parent',0);
        $itemlist = [];
        foreach($items as $item) {
            $item->loadChildren();
            $itemlist[] = $item->getData();
        }

        $html =  $templates->render('menu.html', [
                    'title' => $menu->get('title'),
                    'subheading' => $menu->get('subheading'),
                    'items' => $itemlist,
                    'time' => date("H:ia",strtotime($menu->get('starts')))
            ]
        );
        $pdf->addPage($html);

        if(!$pdf->send('menu.pdf')) {
            return $pdf->getError();
        }
        return "";
    }
}
