@extends('layouts.app')

@section('content')
@php
    $isEn = session('locale', 'ar') === 'en';

    $mediaUrl = function ($path, $fallback = 'images/lolita/white-ombre.jpg') {
        if (!$path) {
            return asset($fallback);
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    };
@endphp

<section class="page-hero booking-page-hero">
    <div class="container">
        <span class="eyebrow">BOOK APPOINTMENT</span>
        <h1>{{ $isEn ? 'Book your appointment' : 'احجزي موعدك' }}</h1>
        <p>
            {{ $isEn
                ? 'Choose your service, day and preferred time in a few simple steps. Your request will remain pending until confirmed.'
                : 'اختاري الخدمة واليوم والوقت المناسب بخطوات بسيطة، وسنحتفظ بطلبك بانتظار التأكيد.' }}
        </p>
    </div>
</section>

<section class="section booking-section">
    <div class="container booking-layout">
        <form class="booking-form card booking-wizard" method="post" action="{{ route('booking.store') }}">
            @csrf

            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="booking-progress">
                <span id="booking_progress_fill"></span>
            </div>

            <div class="booking-steps" aria-label="{{ $isEn ? 'Booking steps' : 'خطوات الحجز' }}">
                <div class="booking-step is-active" data-step-indicator="1">
                    <span>1</span><b>{{ $isEn ? 'Service' : 'الخدمة' }}</b>
                </div>
                <div class="booking-step" data-step-indicator="2">
                    <span>2</span><b>{{ $isEn ? 'Date' : 'التاريخ' }}</b>
                </div>
                <div class="booking-step" data-step-indicator="3">
                    <span>3</span><b>{{ $isEn ? 'Time' : 'الوقت' }}</b>
                </div>
                <div class="booking-step" data-step-indicator="4">
                    <span>4</span><b>{{ $isEn ? 'Confirm' : 'التأكيد' }}</b>
                </div>
            </div>

            <div class="booking-block" data-booking-step="1">
                <div class="booking-block-head">
                    <div>
                        <span class="booking-number">01</span>
                        <h2>{{ $isEn ? 'Choose your service' : 'اختاري الخدمة' }}</h2>
                    </div>
                    <p>{{ $isEn ? 'Tap the service card that suits you.' : 'اضغطي على البطاقة المناسبة لك.' }}</p>
                </div>

                <input type="hidden" name="service_id" id="service_id" value="{{ old('service_id') }}" required>

                <div class="service-choice-grid" id="service_choices">
                    @foreach($services as $service)
                        @php
                            $selected = (string) old('service_id') === (string) $service->id;
                            $serviceName = $isEn ? ($service->name_en ?? $service->name_ar) : $service->name_ar;
                        @endphp

                        <button
                            class="service-choice {{ $selected ? 'is-selected' : '' }}"
                            type="button"
                            data-service-id="{{ $service->id }}"
                            aria-pressed="{{ $selected ? 'true' : 'false' }}"
                        >
                            <span class="service-choice-image">
                                <img
                                    src="{{ $mediaUrl($service->image) }}"
                                    alt="{{ $serviceName }}"
                                    loading="lazy"
                                >
                            </span>

                            <span class="service-choice-copy">
                                <strong>{{ $serviceName }}</strong>
                                <small>{{ $service->duration_minutes }} {{ $isEn ? 'min' : 'دقيقة' }}</small>
                                <em>{{ number_format($service->price) }} {{ $isEn ? 'SYP' : 'ل.س' }}</em>
                            </span>

                            <span class="choice-check">✓</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="booking-block booking-block-muted" data-booking-step="2">
                <div class="booking-block-head">
                    <div>
                        <span class="booking-number">02</span>
                        <h2>{{ $isEn ? 'Choose date and time' : 'اختاري التاريخ والوقت' }}</h2>
                    </div>
                    <p>{{ $isEn ? 'Only available time slots are shown.' : 'الأوقات المعروضة متاحة للحجز.' }}</p>
                </div>

                <label class="date-field">
                    {{ $isEn ? 'Date' : 'التاريخ' }}
                    <input
                        type="date"
                        name="appointment_date"
                        id="appointment_date"
                        min="{{ now()->toDateString() }}"
                        value="{{ old('appointment_date') }}"
                        required
                    >
                </label>

                <input type="hidden" name="appointment_time" id="appointment_time" value="{{ old('appointment_time') }}" required>

                <div class="time-picker-wrap">
                    <span class="field-title">{{ $isEn ? 'Time' : 'الوقت' }}</span>
                    <div class="time-slots is-empty" id="time_slots" aria-live="polite">
                        <div class="time-placeholder">
                            <span>♡</span>
                            <p>{{ $isEn ? 'Choose a service and date first to see available times.' : 'اختاري الخدمة والتاريخ أولاً لنظهر لك الأوقات المتاحة.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-block" data-booking-step="3">
                <div class="booking-block-head">
                    <div>
                        <span class="booking-number">03</span>
                        <h2>{{ $isEn ? 'Your details' : 'بيانات الحجز' }}</h2>
                    </div>
                    <p>{{ $isEn ? 'We use these details only to confirm your appointment.' : 'نستخدم هذه البيانات فقط لتأكيد موعدك.' }}</p>
                </div>

                <div class="two-col">
                    <label>
                        {{ $isEn ? 'Name' : 'الاسم' }}
                        <input
                            type="text"
                            name="client_name"
                            value="{{ old('client_name') }}"
                            placeholder="{{ $isEn ? 'Full name' : 'الاسم الكامل' }}"
                            required
                        >
                    </label>

                    <label>
                        {{ $isEn ? 'Phone number' : 'رقم الهاتف' }}
                        <input
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="09xx xxx xxx"
                            required
                        >
                    </label>
                </div>

                <label>
                    {{ $isEn ? 'Notes' : 'ملاحظات' }}
                    <span class="optional">{{ $isEn ? 'Optional' : 'اختياري' }}</span>
                    <textarea
                        name="notes"
                        rows="4"
                        placeholder="{{ $isEn ? 'Example: French design, a specific color...' : 'مثلاً: تصميم فرنش، لون محدد...' }}"
                    >{{ old('notes') }}</textarea>
                </label>
            </div>

            <div class="booking-policy">
                <h3>{{ $isEn ? 'Booking policy' : 'سياسة الحجز' }}</h3>

                <div style="white-space:pre-line;color:var(--muted);font-size:13px;margin-bottom:14px">
                    {{ $site['booking_policy'] ?? ($isEn
                        ? "Please arrive 5–10 minutes before your appointment.\nFor cancellations or changes, please contact us as early as possible.\nAny deposit or payment conditions will be confirmed directly through WhatsApp if applicable."
                        : "يُفضّل الوصول قبل الموعد بـ 5–10 دقائق.\nالإلغاء أو التعديل يُفضّل أن يكون قبل الموعد بوقت كافٍ.\nأي عربون أو شروط دفع يتم تأكيدها مباشرة عبر WhatsApp إن كانت مطبقة.") }}
                </div>

                <label class="policy-check">
                    <input type="checkbox" name="booking_policy" value="1" @checked(old('booking_policy')) required>
                    <span>{{ $isEn ? 'I have read and agree to the booking policy.' : 'قرأت سياسة الحجز وأوافق عليها.' }}</span>
                </label>
            </div>

            <div class="booking-summary" id="booking_summary">
                <div>
                    <span>{{ $isEn ? 'Your selection' : 'اختيارك' }}</span>
                    <strong id="summary_service">{{ $isEn ? 'No service selected yet' : 'لم يتم اختيار خدمة بعد' }}</strong>
                </div>
                <div>
                    <span>{{ $isEn ? 'Appointment' : 'الموعد' }}</span>
                    <strong id="summary_datetime">{{ $isEn ? 'Choose a date and time' : 'اختاري التاريخ والوقت' }}</strong>
                </div>
            </div>

            <button class="btn btn-primary full booking-submit" type="submit">
                <span>{{ $isEn ? 'Confirm booking request' : 'تأكيد طلب الحجز' }}</span>
                <span class="btn-arrow">←</span>
            </button>
        </form>

        <aside class="booking-side">
            <div class="card booking-help-card">
                <span class="mini-icon">♡</span>
                <h3>{{ $isEn ? 'Simple and clear booking' : 'حجز بسيط وواضح' }}</h3>
                <ol>
                    <li>{{ $isEn ? 'Choose your service.' : 'اختاري خدمتك.' }}</li>
                    <li>{{ $isEn ? 'Pick the day and time.' : 'حددي اليوم والوقت.' }}</li>
                    <li>{{ $isEn ? 'Enter your contact details.' : 'أدخلي بيانات التواصل.' }}</li>
                    <li>{{ $isEn ? 'We confirm your appointment.' : 'نؤكد لك الموعد.' }}</li>
                </ol>
            </div>

            <div class="card whatsapp-card">
                <span class="mini-icon">↗</span>
                <h3>{{ $isEn ? 'Prefer WhatsApp?' : 'تفضلي واتساب؟' }}</h3>
                <p>
                    {{ $isEn
                        ? 'You can contact us directly at ' . ($site['phone'] ?? '0936 812 019') . '.'
                        : 'يمكنك التواصل مباشرة عبر الرقم ' . ($site['phone'] ?? '0936 812 019') . '.' }}
                </p>
                <a
                    class="btn btn-outline full"
                    target="_blank"
                    rel="noopener"
                    href="https://wa.me/{{ $site['whatsapp'] ?? '963936812019' }}"
                >
                    {{ $isEn ? 'Open WhatsApp' : 'فتح WhatsApp' }}
                </a>
            </div>
        </aside>
    </div>
</section>
@endsection

@push('scripts')
<script>
    window.bookingAvailabilityUrl = @json(route('booking.availability'));
    window.bookingOldTime = @json(old('appointment_time'));
</script>
@endpush
