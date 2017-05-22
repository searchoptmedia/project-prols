<?php
namespace Propelrr\CommunicationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Vendor\GlobeLabs\GlobeClient;


class SmsController extends Controller
{
    public function sendTwilioSmsAction()
    {
        header("Access-Control-Allow-Origin: *");
        $request = $this->getRequest();

        if ($request->getMethod() == "POST") {

            $headers = getallheaders();
            $auth = base64_decode(str_replace('Basic ', '', $headers['Authorization']));

            if ($auth == "Tw1L10smsR0CkXx") {
                $to = $request->request->get('to');
                $from = $request->request->get('from');
                $message = $request->request->get('message');
                $msid = $request->request->get('msid');

                $twilio = $this->get('twilio.api');

                $message = $twilio->account->messages->create(array(
                    'From' => $from,
                    'To' => $to,
                    'MessagingServiceSid' => $msid,
                    'Body' => $message,
                ));

                echo $message->sid;
                exit;
            } else {
                echo -1;
                exit;
            }

        } else {
            echo -1;
            exit;
        }
    }

    public function sendGlobeSmsAction(Request $request)
    {
        header("Access-Control-Allow-Origin: *");

        if ($request->getMethod() == "POST") {
            //Local and Staging
            $globeShortCode = '21586000';
            $otherShortCode = '29290586000';
            $appId = 'gxKXfK959nuXki8o5Ec5KGuyRxA9fdEp';
            $appSecret = 'b20ac84fb3dbd7afbcf0e9748564a890641279db31c1e1a42221df2f701dc7e5';

            $headers = getallheaders();
            $auth = base64_decode(str_replace('Basic ', '', $headers['Authorization']));

            if ($auth == "Gl0b3L@bSAp1") {
                $to = $request->request->get('mobile');
                $msg = $request->request->get('message');

                $client = new GlobeClient();
                $sms = $client->sms($globeShortCode);
                $response = $sms->sendMessage($to, $msg, 'z5hNQ1gXzx', $appId, $appSecret);

                echo json_encode($response);
                exit;
            }
        } else {
            echo -1;
            exit;
        }
    }
}