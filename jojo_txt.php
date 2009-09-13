<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007 Harvey Kane <code@ragepank.com>
 * Copyright 2007 Michael Holt <code@gardyneholt.co.nz>
 * Copyright 2007 Melanie Schulz <mel@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @author  Michael Cochrane <code@gardyneholt.co.nz>
 * @author  Melanie Schulz <mel@gardyneholt.co.nz>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */



class JOJO_Plugin_Jojo_txt extends JOJO_Plugin
{

    function _getContent()
    {
        global $smarty, $templateoptions;
        Jojo::noFormInjection();

        $price = 0.12; //12 cents per message

        $content = array();

        $submit = Util::getFormData('submit','');
        //$phone = Util::getFormData('phone','');
        $phone = Jojo::getOption('mobilenumber');
        $from = Util::getFormData('from','');
        $msg = Util::getFormData('msg','');


        if ($submit != '') {
          $errors = array();

          /* check fields are completed correctly */
          if  ($phone == '') $errors[] = 'Please enter the mobile number to send to';
          if  ($from == '') $errors[] = 'Please enter your name in the from field';
          if  (strlen($from) > 11) $errors[] = 'From name cannot be longer than 11 characters';
          if  ($msg == '') $errors[] = 'Please enter a message';
          if  (strlen($msg) > 160) $errors[] = 'The message is too long - messages can be a maximum of 160 characters';

          $captchacode = Util::getFormData('CAPTCHA','');
          if (!PhpCaptcha::Validate($captchacode)) {
              $errors[] = 'Invalid code entered';
          }

          /* ensure there is credit available */
          if (count($errors) == 0) {
            require_once (_BASEDIR.'/external/sms-api/sms_api.php');
            $sms = new sms();
            if  ($sms->getbalance() <= 0) $errors[] = 'There are no SMS credits remaining on this account';
          }

          /* send message */
          if (count($errors) == 0) {
            $sms->send ($phone, $from, $msg);
            Jojo::insertQuery("INSERT INTO {smslog} SET `sent`= ?, `from`= ?, `phone`= ?, `message` = ?", array(strtotime('now'), $from, $phone, $msg));
            $smarty->assign('message','Your SMS message has been sent. It may take a few minutes to arrive.');
          } else {
            $smarty->assign('phone',$phone);
            $smarty->assign('msg',$msg);
          }

          $smarty->assign('error',implode('<br />',$errors));
        }

        //if ($from == '') $from = $_SESSION['username'];
        $smarty->assign('from',$from);

        /* get balance */
        $data = Jojo::selectQuery("SELECT * FROM smslog");
        $smarty->assign('pricecents',($price * 100));
        $smarty->assign('balance', count($data) * $price);
        $smarty->assign('content',$this->page['pg_body']);
        $smarty->assign('mobilenumber',Jojo::getOption('mobilenumber'));
        $content['content'] = $smarty->fetch('jojo_txt.tpl');
        return $content;
    }
}