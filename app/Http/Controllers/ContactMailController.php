<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Mail\ContactMessage;
use Exception;
use Resend\Laravel\Facades\Resend;
use Illuminate\Support\Facades\Http;


class ContactMailController extends Controller
{
    public function mail(Request $request){
        $fields = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required|max:255',
            'message' => 'required'
        ]);


        $developer = env('MAIL_FROM_ADDRESS');
        
        try {
      
            // Mail::to($developer)->send(new ContactMessage($fields));

            // Mail::to($fields['email'])->send(new ContactMail($fields));

            Http::withHeaders([
                'api-key' => env('BREVO_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('MAIL_FROM_NAME'),
                    'email' => env('MAIL_FROM_ADDRESS')
                ],
                'to' => [
                    [
                        'email' => $fields['email'],
                        'name' => $fields['name']
                    ]
                ],
                'subject' => 'Thank You for Contacting Me',
                'htmlContent' => "
                    <div style='font-family: Arial, Helvetica, sans-serif; font-size: 15px; line-height: 1.7; color: #333; max-width: 650px; margin: auto;'>

                        <p>Dear {$fields['name']},</p>

                        <p>
                            Thank you for contacting me through my portfolio website.
                            This email is to confirm that I have successfully received your message.
                        </p>

                        <p>
                            I appreciate you taking the time to reach out. I will review your inquiry carefully
                            and respond as soon as possible.
                        </p>

                        <p>
                            If your message is urgent, please allow a reasonable amount of time for my response.
                        </p>

                        <p>
                            Thank you once again, and I look forward to speaking with you.
                        </p>

                        <br>

                        <p>
                            Kind regards,<br>
                            <strong>Ricardo Jose David</strong><br>
                            Full-Stack Web Developer
                        </p>

                    </div>
                "
            ]);


            Http::withHeaders([
                'api-key' => env('BREVO_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('MAIL_FROM_NAME'),
                    'email' => env('MAIL_FROM_ADDRESS')
                ],
                'to' => [
                    [
                        'email' => env('MAIL_FROM_ADDRESS'),
                        'name' => 'Ricardo Jose David'
                    ]
                ],
                'subject' => 'New Contact Form Submission: ' . $fields['subject'],
                'htmlContent' => "
                    <div style='font-family: Arial, Helvetica, sans-serif; font-size: 15px; line-height: 1.7; color: #333; max-width: 650px; margin: auto;'>

                        <h2 style='color:#2c3e50;'>New Contact Form Submission</h2>

                        <p>You have received a new message from your portfolio website.</p>

                        <table style='border-collapse: collapse; width: 100%; margin-top: 20px;'>
                            <tr>
                                <td style='padding: 8px; font-weight: bold; width: 120px;'>Name:</td>
                                <td style='padding: 8px;'>{$fields['name']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; font-weight: bold;'>Email:</td>
                                <td style='padding: 8px;'>{$fields['email']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; font-weight: bold;'>Subject:</td>
                                <td style='padding: 8px;'>{$fields['subject']}</td>
                            </tr>
                        </table>

                        <hr style='margin: 25px 0;'>

                        <h3>Message</h3>

                        <div style='background:#f8f9fa; padding:15px; border-left:4px solid #0d6efd; white-space: pre-wrap;'>
                            {$fields['message']}
                        </div>

                    </div>
                "
            ]);

            return response()->json([
                'message' => 'Emails sent successfully.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to send email.',
                'error' => $e->getMessage(), 
            ], 500);
        }

        // $fields = "secret";

        // Mail::to('rjdavid061504@gmail.com')->send(new ContactMail($fields));

        return response([
            'message' => 'Email Successfully Sent'
        ]);
    }
}
