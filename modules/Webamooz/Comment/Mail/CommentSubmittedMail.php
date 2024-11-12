<?php

namespace Webamooz\Comment\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Webamooz\Comment\Models\Comment;

class CommentSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }


    public function envelope()
    {
        return new Envelope(subject: 'وب آموز | پاسخ دیدیگاه',);
    }


    public function content()
    {
        return new Content(markdown: 'Comments::mails.comment-submitted',);  //هر چی متغیر گلوبال باش درون ویو بهش دتسرسی دارم
    }

    public function attachments()
    {
        return [];
    }
}
