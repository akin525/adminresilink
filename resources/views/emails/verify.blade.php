<h2>Hello {{ $user->name }},</h2>
<p>Click the link below to verify your email:</p>

<a href="{{ url('/api/verify-email/'.$token) }}">Verify Email</a>
