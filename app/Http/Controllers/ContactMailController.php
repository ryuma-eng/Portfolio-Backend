<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Mail\ContactMessage;

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
