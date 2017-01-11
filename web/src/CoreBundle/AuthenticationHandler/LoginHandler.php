<?php

namespace CoreBundle\AuthenticationHandler;

use CoreBundle\Model\EmpTimePeer;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

use CoreBundle\Model\EmpAcc;
use CoreBundle\Model\EmpAccQuery;
use CoreBundle\Model\EmpProfile;
use CoreBundle\Model\EmpProfilePeer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraints\DateTime;

class LoginHandler implements AuthenticationSuccessHandlerInterface
{
	protected $router;
    protected $security;

    public function __construct(Router $router, SecurityContext $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token){

    	$response = new RedirectResponse($this->router->generate('error403'));

        $session    = new Session();

        $user       = $token->getUser();
        $id         = $user->getId();
        $data       = EmpProfilePeer::getInformation($id);
        $empStatus  = $data->getProfileStatus();
        $timedata   = EmpTimePeer::getEmpLastTimein($id);
        $isTimeout  = false;

        if(!is_null($timedata)){
            $timeout = $timedata->getTimeOut();
            $timeoutdate = $timedata->getDate('M d Y');
            $datetoday = date('M d Y');
            if(empty($timeout) && $timeoutdate != $datetoday){
              $isTimeout = true;
            }
        }

        if ($token->getUser() instanceof EmpAcc)
        {
            echo json_encode($empStatus); exit;
            if($empStatus == 1)
            {
                //get date today
                if(!empty($timedata))
                {
                    $date       = date('Y/m/d H:i:s');
                    $timein     = $timedata->getTimeIn();
                    $dateTimeIn = $timein->format('Y/m/d H:i:s');

                    $datetime1 = date_create($date);
                    $datetime2 = date_create($dateTimeIn);
                    $interval  = date_diff($datetime1, $datetime2);

                    $hours = $interval->format('%h') + ($interval->format('%d') * 24);

                    //check if not time out
                    if(empty($timedata->getTimeOut()))
                    {
                        //if not yet timeout and currently within max hours(16)
                        if($hours <= 16)
                        {
                            //check if another day
                            $session->set('timeout', 'false');

                            if($timein->format('Y/m/d') != date('Y/m/d')) $session->set('isSameDay', 'false');
                            else $session->set('isSameDay', 'true');
                        }
                        //if more than 16 hours not timeout
                        else
                        {
                            //auto-time out the employee by 12am the of last time in +1day at 12am
                            $timedout = $timein->modify('+1 day')->format('Y/m/d 00:00:00');
                            $timedata->setTimeOut($timedout);

                            if ($timedata->save())
                            {
                                $timeOutQry = array('timeout_qry' => 'true', 'timeout_date' => $timedout);
                                $session->set('timeout', 'true');
                                $session->set('isSameDay', '');
                            }
                        }
                    }
                }

                $refererUrl = $this->router->generate('admin_homepage', !empty($timeOutQry) ? $timeOutQry : array());
                $response = new RedirectResponse($refererUrl);
            }else{
                $response = array("Invalid Account");
                echo json_encode($response);
                exit;
            }
        }
        return $response;
    }
    	// if (isset($token)) {
    	// 	if (($request->request->get('_username') === 'superadmin' && $request->request->get('_password') === 'sominc123')) {
    	// 		$refererUrl = $request->getSession()->get('_security.secured_area.target_path');

     //            if ($refererUrl != null) {
     //                $refererUrl = $this->router->generate('admin_homepage');
     //            }

    	// 	} else {
     //            echo $request->request->get('_username');

     //        	$refererUrl = $request->getSession()->get('_security.secured_area.target_path');
     //            $user = $token->getUser();

     //        	if ($token->getUser() instanceof EmpAcc) {

     //                // $level = $user->getRole();
     //                // if(strcasecmp($level, 'admin') == 0){
     //                    $refererUrl = $this->router->generate('admin_homepage');
     //                // }else if (strcasecmp($level, 'employee') == 0){
     //                //     $refererUrl = $this->router->generate('main_homepage');
     //                // }
     //        	}
     //        }
     //        $response = new RedirectResponse($refererUrl);
    	// }

        // return $response;
    // }
}

?>