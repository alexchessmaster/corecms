<?php

namespace App\Http\Controllers\NordicStandard\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NordicStandard\ContactStoreRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function submitContactForm(ContactStoreRequest $contactStoreRequest)
    {
        Mail::to('contact_us@nordicstandard.net')->queue(new ContactFormMail(request()->all()));

        return response()->json(['ok' => true, 'message' => 'Your request has been submitted']);
    }
}
