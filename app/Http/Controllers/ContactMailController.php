<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Mail\ContactMessage;
use Exception;
use Resend\Laravel\Facades\Resend;

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
      
            Mail::to($developer)->send(new ContactMessage($fields));

            Mail::to($fields['email'])->send(new ContactMail($fields));

            // Resend::emails()->send([
            //     // 'from' => env('MAIL_FROM_ADDRESS'),
            //     'from' => "env('MAIL_FROM_ADDRESS')",
            //     'to' => [
            //         $fields['email']
            //     ],
            //     'subject' => 'Message Received Successfully',
            //     'html' => "
            //         <p>
            //             Dear {$fields['name']}
            //         </p>

            //         <p>
            //             Thank you for reaching out!
            //         </p>

            //         <p>
            //             This email is to confirm that I have successfully received your message.
            //             I'll review it and get back to you as soon as I can.
            //         </p>

            //         <p>
            //             Best regards,
            //         </p>

            //         <p>
            //             Ricardo Jose David
            //         </p>

            //         <p>
            //             " . env('MAIL_FROM_ADDRESS') . "
            //         </p>
            //     "
            // ]);

            // Resend::emails()->send([
            //     'from' => env('MAIL_FROM_ADDRESS'),
            //     'to' => [
            //         env('MAIL_FROM_ADDRESS')
            //     ],
            //     'subject' => 'New Contact Message from ' . $fields['name'],
            //     'html' => "
            //         <p>
            //             Dear Mr. David
            //         </p>

            //         <p>
            //             {$fields['message']}
            //         </p>

            //         <p>
            //             Best Regards,
            //         </p>

            //         <p>
            //             {$fields['name']}
            //         </p>

            //         <p>
            //             {$fields['email']}
            //         </p>
            //     "
            // ]);

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
