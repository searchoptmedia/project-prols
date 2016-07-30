<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appDevUrlMatcher.
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appDevUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        if (0 === strpos($pathinfo, '/_')) {
            // _wdt
            if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_wdt')), array (  '_controller' => 'web_profiler.controller.profiler:toolbarAction',));
            }

            if (0 === strpos($pathinfo, '/_profiler')) {
                // _profiler_home
                if (rtrim($pathinfo, '/') === '/_profiler') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_profiler_home');
                    }

                    return array (  '_controller' => 'web_profiler.controller.profiler:homeAction',  '_route' => '_profiler_home',);
                }

                if (0 === strpos($pathinfo, '/_profiler/search')) {
                    // _profiler_search
                    if ($pathinfo === '/_profiler/search') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchAction',  '_route' => '_profiler_search',);
                    }

                    // _profiler_search_bar
                    if ($pathinfo === '/_profiler/search_bar') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchBarAction',  '_route' => '_profiler_search_bar',);
                    }

                }

                // _profiler_purge
                if ($pathinfo === '/_profiler/purge') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:purgeAction',  '_route' => '_profiler_purge',);
                }

                // _profiler_info
                if (0 === strpos($pathinfo, '/_profiler/info') && preg_match('#^/_profiler/info/(?P<about>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_info')), array (  '_controller' => 'web_profiler.controller.profiler:infoAction',));
                }

                // _profiler_phpinfo
                if ($pathinfo === '/_profiler/phpinfo') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:phpinfoAction',  '_route' => '_profiler_phpinfo',);
                }

                // _profiler_search_results
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/search/results$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_search_results')), array (  '_controller' => 'web_profiler.controller.profiler:searchResultsAction',));
                }

                // _profiler
                if (preg_match('#^/_profiler/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler')), array (  '_controller' => 'web_profiler.controller.profiler:panelAction',));
                }

                // _profiler_router
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/router$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_router')), array (  '_controller' => 'web_profiler.controller.router:panelAction',));
                }

                // _profiler_exception
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception')), array (  '_controller' => 'web_profiler.controller.exception:showAction',));
                }

                // _profiler_exception_css
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception\\.css$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception_css')), array (  '_controller' => 'web_profiler.controller.exception:cssAction',));
                }

            }

            if (0 === strpos($pathinfo, '/_configurator')) {
                // _configurator_home
                if (rtrim($pathinfo, '/') === '/_configurator') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_configurator_home');
                    }

                    return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::checkAction',  '_route' => '_configurator_home',);
                }

                // _configurator_step
                if (0 === strpos($pathinfo, '/_configurator/step') && preg_match('#^/_configurator/step/(?P<index>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_configurator_step')), array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::stepAction',));
                }

                // _configurator_final
                if ($pathinfo === '/_configurator/final') {
                    return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::finalAction',  '_route' => '_configurator_final',);
                }

            }

            // _twig_error_test
            if (0 === strpos($pathinfo, '/_error') && preg_match('#^/_error/(?P<code>\\d+)(?:\\.(?P<_format>[^/]++))?$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_twig_error_test')), array (  '_controller' => 'twig.controller.preview_error:previewErrorPageAction',  '_format' => 'html',));
            }

        }

        // login
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'login');
            }

            return array (  '_controller' => 'CoreBundle\\Controller\\DefaultController::loginAction',  '_route' => 'login',);
        }

        // sms_homepage
        if (0 === strpos($pathinfo, '/sms/hello') && preg_match('#^/sms/hello/(?P<name>[^/]++)$#s', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, array('_route' => 'sms_homepage')), array (  '_controller' => 'SmsBundle\\Controller\\DefaultController::indexAction',));
        }

        if (0 === strpos($pathinfo, '/main')) {
            // admin_homepage
            if (rtrim($pathinfo, '/') === '/main') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'admin_homepage');
                }

                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::indexAction',  '_route' => 'admin_homepage',);
            }

            if (0 === strpos($pathinfo, '/main/admin/time_in')) {
                // admin_time_in
                if (preg_match('#^/main/admin/time_in/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'admin_time_in')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::timeInAction',));
                }

                // admin_time_in_no_params
                if (rtrim($pathinfo, '/') === '/main/admin/time_in') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'admin_time_in_no_params');
                    }

                    return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::timeInAction',  '_route' => 'admin_time_in_no_params',);
                }

            }

            if (0 === strpos($pathinfo, '/main/send/request/email')) {
                // request_email
                if (preg_match('#^/main/send/request/email/(?P<value>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'request_email')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::sendRequestAction',));
                }

                // request_email_no_params
                if (rtrim($pathinfo, '/') === '/main/send/request/email') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'request_email_no_params');
                    }

                    return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::sendRequestAction',  '_route' => 'request_email_no_params',);
                }

            }

            if (0 === strpos($pathinfo, '/main/admin/time_out')) {
                // admin_time_out
                if (preg_match('#^/main/admin/time_out/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'admin_time_out')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::timeOutAction',));
                }

                // admin_time_out_no_params
                if (rtrim($pathinfo, '/') === '/main/admin/time_out') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'admin_time_out_no_params');
                    }

                    return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::timeOutAction',  '_route' => 'admin_time_out_no_params',);
                }

            }

            if (0 === strpos($pathinfo, '/main/request')) {
                if (0 === strpos($pathinfo, '/main/request/meeting')) {
                    // request_meeting_w_params
                    if (preg_match('#^/main/request/meeting/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'request_meeting_w_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::requestMeetingAction',));
                    }

                    // request_meeting
                    if (rtrim($pathinfo, '/') === '/main/request/meeting') {
                        if (substr($pathinfo, -1) !== '/') {
                            return $this->redirect($pathinfo.'/', 'request_meeting');
                        }

                        return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::requestMeetingAction',  '_route' => 'request_meeting',);
                    }

                }

                if (0 === strpos($pathinfo, '/main/request/leave')) {
                    // request_leave
                    if (rtrim($pathinfo, '/') === '/main/request/leave') {
                        if (substr($pathinfo, -1) !== '/') {
                            return $this->redirect($pathinfo.'/', 'request_leave');
                        }

                        return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::requestLeaveAction',  '_route' => 'request_leave',);
                    }

                    // request_leave_w_params
                    if (preg_match('#^/main/request/leave/(?P<id>[^/]++)/(?P<id2>[^/]++)/(?P<id3>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'request_leave_w_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::requestLeaveAction',));
                    }

                }

            }

            if (0 === strpos($pathinfo, '/main/status/accept')) {
                // status_accept_with_params
                if (preg_match('#^/main/status/accept/(?P<id>[^/]++)/(?P<id2>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'status_accept_with_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::statusAcceptAction',));
                }

                // status_accept
                if (rtrim($pathinfo, '/') === '/main/status/accept') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'status_accept');
                    }

                    return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::statusAcceptAction',  '_route' => 'status_accept',);
                }

            }

            if (0 === strpos($pathinfo, '/main/declined')) {
                // status_declined
                if (rtrim($pathinfo, '/') === '/main/declined') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'status_declined');
                    }

                    return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::declinedRequestAction',  '_route' => 'status_declined',);
                }

                // status_declined_w_params
                if (preg_match('#^/main/declined/(?P<id>[^/]++)/(?P<id2>[^/]++)/(?P<id3>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'status_declined_w_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::declinedRequestAction',));
                }

            }

            // check_time
            if ($pathinfo === '/main/checktime') {
                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::checkTimeAction',  '_route' => 'check_time',);
            }

        }

        if (0 === strpos($pathinfo, '/log')) {
            // _security_check
            if ($pathinfo === '/login_check') {
                return array('_route' => '_security_check');
            }

            // logout
            if ($pathinfo === '/logout') {
                return array('_route' => 'logout');
            }

        }

        // error403
        if ($pathinfo === '/403') {
            return array('_route' => 'error403');
        }

        // profile_page
        if ($pathinfo === '/profile') {
            return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileAction',  '_route' => 'profile_page',);
        }

        if (0 === strpos($pathinfo, '/emp')) {
            // emp_page_w_params
            if (preg_match('#^/emp/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'emp_page_w_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::employeeProfileAction',));
            }

            // emp_page
            if (rtrim($pathinfo, '/') === '/emp') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'emp_page');
                }

                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::employeeProfileAction',  '_route' => 'emp_page',);
            }

        }

        if (0 === strpos($pathinfo, '/teleupdate')) {
            // profile_tele
            if (rtrim($pathinfo, '/') === '/teleupdate') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'profile_tele');
                }

                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileTelUpdateAction',  '_route' => 'profile_tele',);
            }

            // profile_tele_with_params
            if (preg_match('#^/teleupdate/(?P<id>[^/]++)/(?P<id2>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'profile_tele_with_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileCellUpdateAction',));
            }

        }

        if (0 === strpos($pathinfo, '/cellupdate')) {
            // profile_mobile
            if (rtrim($pathinfo, '/') === '/cellupdate') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'profile_mobile');
                }

                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileCellUpdateAction',  '_route' => 'profile_mobile',);
            }

            // profile_mobile_with_params
            if (preg_match('#^/cellupdate/(?P<id>[^/]++)/(?P<id2>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'profile_mobile_with_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileTelUpdateAction',));
            }

        }

        if (0 === strpos($pathinfo, '/addupdate')) {
            // profile_address
            if (rtrim($pathinfo, '/') === '/addupdate') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'profile_address');
                }

                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileAddressUpdateAction',  '_route' => 'profile_address',);
            }

            // profile_address_with_params
            if (preg_match('#^/addupdate/(?P<id>[^/]++)/(?P<id2>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'profile_address_with_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileAddressUpdateAction',));
            }

        }

        if (0 === strpos($pathinfo, '/emailupdate')) {
            // profile_email
            if (rtrim($pathinfo, '/') === '/emailupdate') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'profile_email');
                }

                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileEmailUpdateAction',  '_route' => 'profile_email',);
            }

            // profile_email_with_params
            if (preg_match('#^/emailupdate/(?P<id>[^/]++)/(?P<id2>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'profile_email_with_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileEmailUpdateAction',));
            }

        }

        if (0 === strpos($pathinfo, '/bdayupdate')) {
            // profile_bday
            if (rtrim($pathinfo, '/') === '/bdayupdate') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'profile_bday');
                }

                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileBdayUpdateAction',  '_route' => 'profile_bday',);
            }

            // profile_bday_with_params
            if (preg_match('#^/bdayupdate/(?P<id>[^/]++)/(?P<id2>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'profile_bday_with_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileBdayUpdateAction',));
            }

        }

        if (0 === strpos($pathinfo, '/deptupdate')) {
            // profile_dept
            if (rtrim($pathinfo, '/') === '/deptupdate') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'profile_dept');
                }

                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileDeptpdateAction',  '_route' => 'profile_dept',);
            }

            // profile_dept_with_params
            if (preg_match('#^/deptupdate/(?P<id>[^/]++)/(?P<id2>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'profile_dept_with_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileDeptUpdateAction',));
            }

        }

        // view_request
        if ($pathinfo === '/requests') {
            return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::requestAction',  '_route' => 'view_request',);
        }

        // manage_employee
        if ($pathinfo === '/manage') {
            return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::manageAction',  '_route' => 'manage_employee',);
        }

        if (0 === strpos($pathinfo, '/add')) {
            // add_employee
            if ($pathinfo === '/addemp') {
                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::addEmployeeAction',  '_route' => 'add_employee',);
            }

            if (0 === strpos($pathinfo, '/addpos')) {
                // add_position
                if (rtrim($pathinfo, '/') === '/addpos') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'add_position');
                    }

                    return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::addPositionAction',  '_route' => 'add_position',);
                }

                // add_position_w_params
                if (preg_match('#^/addpos/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'add_position_w_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::addPositionAction',));
                }

            }

            if (0 === strpos($pathinfo, '/adddept')) {
                // add_department
                if (rtrim($pathinfo, '/') === '/adddept') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'add_department');
                    }

                    return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::addDepartmentAction',  '_route' => 'add_department',);
                }

                // add_department_w_params
                if (preg_match('#^/adddept/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'add_department_w_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::addDepartmentAction',));
                }

            }

        }

        // notif_page
        if ($pathinfo === '/notif') {
            return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::notifAction',  '_route' => 'notif_page',);
        }

        if (0 === strpos($pathinfo, '/delete')) {
            // emp_delete
            if (rtrim($pathinfo, '/') === '/delete') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'emp_delete');
                }

                return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::empDeleteAction',  '_route' => 'emp_delete',);
            }

            // emp_delete_w_params
            if (preg_match('#^/delete/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'emp_delete_w_params')), array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::empDeleteAction',));
            }

        }

        // update_profile
        if ($pathinfo === '/update') {
            return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::profileUpdateAction',  '_route' => 'update_profile',);
        }

        // request_access_accepted
        if ($pathinfo === '/requestaccepted') {
            return array (  '_controller' => 'AdminBundle\\Controller\\DefaultController::acceptRequestAction',  '_route' => 'request_access_accepted',);
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
