@component('mail::message')
    # Invitation to Join {{ $organizationName }}

    You have been invited to join our organization. Please use the following token to complete your registration:

    **{{ $token }}**

    Thank you for joining us!

    Regards,
    The {{ $organizationName }} Team
@endcomponent
