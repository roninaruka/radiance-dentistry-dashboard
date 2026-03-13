<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; padding: 40px; max-width: 600px; margin: 0 auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .logo-container { text-align: center; margin-bottom: 30px; }
        .logo { max-width: 200px; height: auto; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { color: #dc3545; margin: 0; font-size: 24px; font-weight: 600; }
        .content { margin-bottom: 30px; }
        .content p { font-size: 16px; margin: 0 0 15px; }
        .details { background: #fff5f5; padding: 20px; border-radius: 6px; border-left: 4px solid #dc3545; margin-bottom: 25px; }
        .details-row { margin-bottom: 10px; font-size: 15px; }
        .details-label { font-weight: bold; color: #555; display: inline-block; width: 100px; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; color: #777; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Radiance Dentistry Logo" class="logo">
        </div>
        
        <div class="header">
            <h2>Appointment Cancelled</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $appointment->name }},</p>
            
            <p>We regret to inform you that your requested appointment at <strong>Radiance Dentistry</strong> has been cancelled.</p>
            
            <div class="details">
                <div class="details-row"><span class="details-label">Date:</span> {{ $appointment->appointment_date->format('l, F j, Y') }}</div>
                <div class="details-row"><span class="details-label">Time:</span> {{ $appointment->appointment_time }}</div>
                @if($appointment->doctor)<div class="details-row"><span class="details-label">Doctor:</span> {{ $appointment->doctor->name }}</div>@endif
            </div>

            @include('emails.partials.clinic_info')

            <p>If you have any questions or would like to reschedule your visit, please contact our clinic directly and we will be happy to assist you.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Radiance Dentistry. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
