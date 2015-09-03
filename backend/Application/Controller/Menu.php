<?php

/**
 * Menu controller
 */
namespace App\Controller;
use App\App;
use App\Controller\Controller;
use mikehaertl\wkhtmlto\Pdf;
class Menu extends Controller {

    public function generateEatInMenu($id) {
        $templates = App::getTemplater();
        $menu = App::model('menu')->load($id);
        $pdf = new Pdf(['page-size' => 'A5']);

        $items = App::model('item')
            ->getCollection()
            ->filter('menu',$menu->getId())
            ->filter('parent',0)
            ->filter('eatin',1);
        $itemlist = [];
        foreach($items as $item) {
            $item->loadChildren();
            $itemlist[] = $item->getData();
        }

        $html =  $templates->render('menu.html', [
                    'title' => $menu->get('title'),
                    'subheading' => $menu->get('subheading'),
                    'items' => $itemlist,
                    'time' => date("H:ia",strtotime($menu->get('starts'))),
                    'pricing' => 'eatin'
            ]
        );

        $pdf->addPage($html);

        if(!$pdf->send($menu->get('title').'.pdf')) {
            return $pdf->getError();
        }
        return "";
    }

    public function generateTakeOutMenu($id) {
        $templates = App::getTemplater();
        $menu = App::model('menu')->load($id);
        $pdf = new Pdf(['page-size' => 'A5']);

        $items = App::model('item')
            ->getCollection()
            ->filter('menu',$menu->getId())
            ->filter('parent',0)
            ->filter('takeout',1);
        $itemlist = [];
        foreach($items as $item) {
            $children = $item->getCollection()
                ->filter('parent',$item->getId())
                ->filter('takeout',1)
                ->toJson();
            $item->set('children',json_decode($children));
            $itemlist[] = $item->getData();
        }

        $html =  $templates->render('menu.html', [
                    'title' => $menu->get('title'),
                    'subheading' => $menu->get('subheading'),
                    'items' => $itemlist,
                    'time' => date("H:ia",strtotime($menu->get('starts'))),
                    'pricing' => 'takeout'
            ]
        );

        $pdf->addPage($html);

        if(!$pdf->send($menu->get('title').'-takeout.pdf')) {
            return $pdf->getError();
        }
        return "";
    }
}
