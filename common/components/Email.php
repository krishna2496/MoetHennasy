<?php
namespace common\components;

use Yii;

class Email
{
    public $email      = '';
    public $setFrom    = '';
    public $subject    = '';
    public $body   = '';
    public $attachment = array();
    private $values    = array();

    public function set($key, $value) {
        $this->values[$key] = $value;
    }

    public function attach($attachment = '') {
        $this->attachment[] = $attachment;
    }
      
    private function parse($content = '') {
        
        $output = $content;

        $searchArray = array();
        $replaceArray = array();
        foreach ($this->values as $key => $value) {
            $searchArray[] = "[$key]";
            $replaceArray[] = $value;
        }
      
        $output = str_replace($searchArray, $replaceArray, $output);

        return $output;
    }

    public function send()
    {
        /*$header = Yii::$app->view->render('@common/mail/layouts/email_header');
        $footer = Yii::$app->view->render('@common/mail/layouts/email_footer');*/

        $header = '';
        $footer = '';
                
        $email   = $this->email;
        $setFrom = $this->setFrom;
        $subject = $this->parse($this->subject);
        $body    = $this->parse($this->body);

        $content = '';
        $content .= $header;
        $content .= $body;
        $content .= $footer;

         $mail =  Yii::$app->mailer->compose(['html' => 'email-html', 'text' => 'email-text'], ['content' => $content])
                  ->setTo($email)
                  ->setFrom($setFrom)
                  ->setSubject($subject);
        
        if(!empty($this->attachment)){
            foreach($this->attachment as $attachment) {
                $mail->attach($attachment);
            }
        }
        
        return $mail->send();
    }
}