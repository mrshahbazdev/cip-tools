<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Registration - CIP-Tools.de</title>
    <style>
        /* Simple styling for clarity */
        body { font-family: Arial, sans-serif; padding: 20px; }
        .error-box { color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px; }
        .submit-btn { padding: 10px 15px; background-color: #007bff; color: white; border: none; cursor: pointer; border-radius: 5px; }
        label { font-weight: bold; }
        input[type="text"], input[type="email"] { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px; box-sizing: border-box; }
    </style>
</head>
<body>

<h1>🚀 CIP-Tools.de Project Registration</h1>
<p>Register for a 30-day trial. Your new subdomain will be created automatically.</p>

{{-- Errors Display --}}
@if ($errors->any())
    <div class="error-box">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register.store') }}">
    @csrf

    <div>
        <label for="name">👤 Your Name:</label><br>
        <input type="text" name="name" required value="{{ old('name') }}" placeholder="Full Name"><br><br>
    </div>

    <div>
        <label for="email">📧 Email Address:</label><br>
        <input type="email" name="email" required value="{{ old('email') }}" placeholder="Super Admin Email"><br><br>
    </div>

    <div>
        <label for="project_name">🏢 Project/Company Name (For Subdomain):</label><br>
        <input type="text" name="project_name" required value="{{ old('project_name') }}" placeholder="Example: InnovationHub"><br><br>
    </div>

    <div>
        <label for="pays_bonus">💰 Offer Bonus for Implemented Innovation?</label>
        <input type="checkbox" name="pays_bonus" value="1"><br><br>
    </div>
    
    <p>⚠️ **Important Note:** As the Super Admin, you are responsible for the **bonus** payment and **privacy** compliance for this Project.</p>
    
    <button type="submit" class="submit-btn">Start 30-Day Trial</button>
</form>

</body>
</html>