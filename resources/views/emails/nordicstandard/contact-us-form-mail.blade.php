<h1>New Contact Form Submission</h1>

<p><strong>First name:</strong> {{ $contactData['first_name'] }}</p>
<p><strong>Last name:</strong> {{ $contactData['last_name'] }}</p>
<p><strong>Email:</strong> {{ $contactData['email'] }}</p>
<p><strong>Phone number:</strong> {{ $contactData['phone_number'] }}</p>
<p><strong>Message:</strong> {!! $contactData['message'] !!}</p>
<p><strong>Others:</strong> {!! json_encode($contactData) !!}</p>
