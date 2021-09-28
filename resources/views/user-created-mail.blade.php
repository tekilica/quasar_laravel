<div>
    <p>Hi {{ $user->userDetails->first_name.' '.$user->userDetails->last_name }},</p>
    <br>
    <p>Welcome to {{ config('app.name') }}. We’re thrilled to see you here!</p>
    <br>
    <p>Your password is: {{ $password }}.</p>
</div>
