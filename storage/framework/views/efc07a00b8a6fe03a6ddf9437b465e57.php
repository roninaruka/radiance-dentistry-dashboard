<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; padding: 40px; max-width: 600px; margin: 0 auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .logo-container { text-align: center; margin-bottom: 30px; }
        .logo { max-width: 200px; height: auto; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { color: #f39c12; margin: 0; font-size: 24px; font-weight: 600; }
        .content { margin-bottom: 30px; }
        .content p { font-size: 16px; margin: 0 0 15px; }
        .details { background: #fffcf5; padding: 20px; border-radius: 6px; border-left: 4px solid #f39c12; margin-bottom: 25px; }
        .details-row { margin-bottom: 10px; font-size: 15px; }
        .details-label { font-weight: bold; color: #555; display: inline-block; width: 100px; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; color: #777; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Radiance Dentistry Logo" class="logo">
        </div>
        
        <div class="header">
            <h2>Appointment Received</h2>
        </div>
        
        <div class="content">
            <p>Dear <?php echo e($appointment->name); ?>,</p>
            
            <p>Thank you for choosing <strong>Radiance Dentistry</strong>. We have received your appointment request and it is currently **pending confirmation**.</p>
            
            <p>Our team will review your request and send you a follow-up email once your appointment is officially confirmed.</p>
            
            <div class="details">
                <div class="details-row"><span class="details-label">Date:</span> <?php echo e($appointment->appointment_date->format('l, F j, Y')); ?></div>
                <div class="details-row"><span class="details-label">Time:</span> <?php echo e($appointment->appointment_time); ?></div>
                <div class="details-row"><span class="details-label">Reason:</span> <?php echo e($appointment->reason ?? 'Checkup'); ?></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($appointment->doctor): ?><div class="details-row"><span class="details-label">Desired Doctor:</span> <?php echo e($appointment->doctor->name); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php echo $__env->make('emails.partials.clinic_info', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <p>If you have any urgent questions, please feel free to reply to this email or call our clinic directly.</p>
        </div>

        <div class="footer">
            <p>&copy; <?php echo e(date('Y')); ?> Radiance Dentistry. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/emails/appointment/pending_patient.blade.php ENDPATH**/ ?>