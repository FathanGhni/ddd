<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\System\MailerModel;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $receiver = null;
    protected $subject = null;
    protected $params = null;
    protected $template = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($receiver, $subject, $params, $template)
    {
        $this->receiver = $receiver;
        $this->subject = $subject;
        $this->params = $params;
        $this->template = $template;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $body = view($this->template, $this->params);
        $mailId = MailerModel::insert($this->receiver, $this->subject, $body);
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'smtp.office365.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = env('ADMIN_EMAIL_ADDRESS');
            $mail->Password   = 'Pimd3veloper2021';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom($mail->Username, 'Cortech System');
            $mail->addAddress($this->receiver);

            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body    = $body;
            $mail->AltBody = $this->subject;

            
            $mail->send();
            MailerModel::updateStatus($mailId);
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
