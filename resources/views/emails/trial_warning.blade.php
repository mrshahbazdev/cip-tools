@component('mail::message')

# Trial Expiry Alert: {{ \$projectName }}

@if (\$daysLeft > 0)

## ⚠️ Attention Required: Only {{ \$daysLeft }} Days Remaining!

Your **{{ \$projectName }}** project's 30-day trial period is ending soon. You only have **{{ \$daysLeft }} days** remaining.

Please make an immediate payment to keep your service active and secure your project data.
@else

## 🚨 URGENT: Trial Period Has Expired!

The 30-day trial period for your **{{ \$projectName }}** project has expired today. Your access may be suspended at any time.

Please complete the payment process immediately to activate your project and resume service.
@endif

@component('mail::button', ['url' => $paymentLink])
Pay Now and Activate
@endcomponent

If you have already made a payment or have any questions, please contact our support team.

Thank you,
{{ config('app.name') }} Team
@endcomponent
