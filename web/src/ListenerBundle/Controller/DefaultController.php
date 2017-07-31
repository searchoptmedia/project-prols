<?php

namespace ListenerBundle\Controller;

use CoreBundle\Model\EmpRequestPeer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    public function indexAction($name)
    {

    }

    public function approveRequestAction($type, Request $request)
    {
        $ids = $request->get('id');
        $ids = explode(",", $ids);

        $requestList = array();

        if(count($ids)) {
            foreach($ids as $id) {
                $req = EmpRequestPeer::retrieveByPK($id);

                if($req)
                    $requestList[] = $req;
            }

            if(count($requestList)) {
                return $this->render('ListenerBundle::request.html.twig');
            }
        }

        throw new AccessDeniedException("Access Denied!");
    }
}
