
<?php $clinic = App\Models\Location::active()->first(); ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($clinic): ?>
<div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 6px; font-size: 13px; color: #555;">
    <strong style="display: block; margin-bottom: 6px; font-size: 14px; color: #333;"><?php echo e($clinic->name); ?></strong>
    <span>📍 <?php echo e($clinic->full_address); ?></span><br>
    <span>📞 <?php echo e($clinic->phone); ?></span><br>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($clinic->email): ?><span>✉️ <?php echo e($clinic->email); ?></span><br><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($clinic->working_hours): ?><span>🕐 <?php echo nl2br(e($clinic->working_hours)); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /var/www/html/resources/views/emails/partials/clinic_info.blade.php ENDPATH**/ ?>