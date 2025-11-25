@component('mail::message')

# Invoice Request Received: {{ $projectName }}

Aapki **12-month membership** ke liye invoice request hamen mil chuki hai.

**Zaroori Malumat:**
Aapne **Invoice** ka tareeqa chuna hai. Iska matlab hai:

1.  **Manual Activation:** Aapka account abhi activate nahi hua hai.
2.  **Activation Time:** Aapka tool sirf **payment receive hone ke baad** manually activate kiya jayega.
3.  **Invoice Issuance:** Invoice jald hi is email address par bhej di jayegi.

---

### Billing Details (Aapne Faraham Kiye):

{{ $billingDetails }}

---

Hamen umeed hai ke aap jald hi payment mukammal kar denge taake aapka project activate ho sake.

Agar aap fori activation chahte hain, to kripya [Credit Card/PayPal] ka tareeqa dobara istemal karein.

Koi bhi sawal ho to is email par rabta karein: {{ $projectAdminEmail }}

Shukriya,
{{ config('app.name') }} Team
@endcomponent
