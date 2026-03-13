{{-- Clinic Info Footer Partial --}}
@php $clinic = App\Models\Location::active()->first(); @endphp
@if($clinic)
<div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 6px; font-size: 13px; color: #555;">
    <strong style="display: block; margin-bottom: 6px; font-size: 14px; color: #333;">{{ $clinic->name }}</strong>
    <span>📍 {{ $clinic->full_address }}</span><br>
    <span>📞 {{ $clinic->phone }}</span><br>
    @if($clinic->email)<span>✉️ {{ $clinic->email }}</span><br>@endif
    @if($clinic->working_hours)<span>🕐 {!! nl2br(e($clinic->working_hours)) !!}</span>@endif
</div>
@endif
