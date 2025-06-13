<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Message</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

  <!-- Header -->
  <div style="background-color: #f7941e; padding: 20px 0; text-align: center;">
    <img src="https://cdn-icons-png.flaticon.com/512/61/61088.png" alt="Logo" width="40" height="40">
  </div>

  <!-- Card Container -->
  <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.08);">
    
    <h1 style="text-align: center; font-size: 24px; color: #111827; margin-bottom: 25px;">📨 New Contact Message</h1>

    <!-- Name -->
    <h2 style="font-size: 20px; color: #1f2937; margin-bottom: 10px; text-transform: capitalize;">
      {{ $data['name'] }}
    </h2>

    <!-- Email -->
    <p style="font-size: 15px; color: #4b5563; margin: 10px 0;">
      <strong>Email:</strong><br>{{ $data['email'] }}
    </p>

    <!-- Subject -->
    <p style="font-size: 15px; color: #4b5563; margin: 20px 0;">
      <strong>Subject:</strong><br>{{ $data['subject'] }}
    </p>

    <!-- Message -->
    <div style="margin-top: 30px;">
      <p style="font-size: 15px; color: #374151; margin-bottom: 6px;"><strong>Message:</strong></p>
      <p style="font-size: 15px; color: #1f2937; line-height: 1.6; background-color: #f9fafb; padding: 15px; border-radius: 12px; border-left: 4px solid #f7941e;">
        {{ $data['user_message'] }}
      </p>
    </div>

  </div>

  <!-- Footer -->
  <div style="max-width: 600px; margin: 30px auto; text-align: center; font-size: 12px; color: #9ca3af;">
    <p>This message was sent from your website’s contact form.</p>
  </div>

</body>
</html>
