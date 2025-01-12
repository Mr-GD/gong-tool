<?php

namespace common\Tool\MessageSent;

use gong\helper\Data;
use gong\helper\Instance;
use PHPMailer\PHPMailer\PHPMailer;


/**
 * @example
 * Email::instance()
 * ->setAddressee('1026709547@qq.com')
 * ->setSubject('测试邮件')
 * ->setBody('这是测试的内容')
 * ->send()
 */

/**
 * @method $this setAddressee(string $addressee) 设置收件人邮箱
 * @method $this setAddresser(string $addresser) 设置发件人邮箱
 * @method $this setSenderName(string $senderName) 设置发件人名字
 * @method $this setSubject(string $subject) 设置邮件主题
 * @method $this setBody(string $body) 设置邮件内容
 */
class Email
{
    use Data, Instance;

    /** @var string 收件人邮箱 */
    public string $addressee = '';

    /** @var string 发件人邮箱 */
    public string $addresser = '';

    /** @var string 发件人名字 */
    public string $senderName = '';

    /** @var string 邮件主题 */
    public string $subject = '';

    /** @var string */
    public string $body = '';
    public PHPMailer $email;

    public function __construct()
    {
        $this->email = new PHPMailer();
    }

    public function send()
    {
        // 配置SMTP服务器
        $this->email->isSMTP();
        $this->email->SMTPDebug  = 2;
        $this->email->Host       = env('EMAIL_SEND_HOST'); // 邮件服务器主机名
        $this->email->SMTPAuth   = true;
        $this->email->Username   = env('EMAIL_SEND_USERNAME'); // 邮件服务器用户名
        $this->email->Password   = env('EMAIL_SEND_PASSWORD'); // 邮件服务器密码
        $this->email->CharSet    = 'UTF-8'; //设置发送的邮件的编码 可选GB2312 据说utf8在某些客户端收信下会乱码
        $this->email->SMTPSecure = 'ssl'; // 使用加密连接
        $this->email->Port       = env('EMAIL_SEND_PORT');

        // 设置发件人和收件人
        $this->addresser  = $this->addresser ?: env('EMAIL_SEND_USERNAME');
        $this->senderName = $this->senderName ?: env('EMAIL_SENDER_NAME');
        $this->email->setFrom($this->addresser, $this->senderName);  // 设置发件人邮箱和名称
        $this->email->addAddress($this->addressee);  // 设置收件人邮箱

        // 设置邮件内容
        $this->email->isHTML();
        $this->email->Subject = $this->subject;
        $this->email->Body    = $this->body;

        // 发送邮件
        return $this->email->send();
    }
}
