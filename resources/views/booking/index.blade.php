@extends('layouts.app')

@section('styles')
<style>
    .booking-steps {
        display: flex; justify-content: center; gap: 4px;
        margin-bottom: 48px; max-width: 700px; margin-left: auto; margin-right: auto;
    }
    .step-pill {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 20px; border-radius: 100px;
        font-size: 13px; font-weight: 500; color: #bbb;
        background: #fff; border: 1px solid var(--gray-200);
        transition: all 0.4s ease;
    }
    .step-pill.active {
        background: var(--gray-900); color: #fff;
        border-color: var(--gray-900);
        box-shadow: 0 4px 20px rgba(15,23,42,0.2);
    }
    .step-pill.done {
        background: var(--gray-100); color: var(--gray-600);
        border-color: var(--gray-100);
    }
    .step-pill .num {
        width: 24px; height: 24px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700;
        background: var(--gray-100); color: var(--gray-600);
    }
    .step-pill.active .num { background: var(--cafe); color: #fff; }
    .step-pill.done .num { background: var(--cafe-dark); color: #fff; }

    .step { animation: fadeInUp 0.5s ease; }

    .pkg-card {
        background: #fff; border-radius: var(--radius-md);
        border: 2px solid transparent; cursor: pointer;
        transition: all 0.3s ease; padding: 20px 24px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .pkg-card:hover { border-color: var(--cafe-light); box-shadow: var(--shadow-md); }
    .pkg-card.selected {
        border-color: var(--cafe); background: #FDF8F0;
        box-shadow: 0 0 0 4px rgba(139,111,71,0.1);
    }
    .pkg-card .pkg-name { font-family: 'Playfair Display', serif; font-weight: 600; font-size: 16px; }
    .pkg-card .pkg-meta { font-size: 13px; color: var(--gray-500); margin-top: 4px; }
    .pkg-card .pkg-price { font-size: 20px; font-weight: 700; color: var(--cafe-dark); font-family: 'Playfair Display', serif; }
    .pkg-card .pkg-select { font-size: 12px; color: var(--gray-400); margin-top: 4px; }

    .cal-card {
        background: #fff; border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden; margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
    }
    .cal-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 20px 24px;
        background: var(--gradient-4);
    }
    .cal-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 18px; color: #fff; font-weight: 600;
    }
    .cal-header button {
        background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);
        color: #fff; padding: 6px 14px; border-radius: 100px;
        cursor: pointer; font-size: 12px; font-family: 'DM Sans', sans-serif;
        transition: all 0.3s;
    }
    .cal-header button:hover { background: rgba(255,255,255,0.25); }

    .cal-grid { padding: 16px 20px; }
    .cal-weekdays {
        display: grid; grid-template-columns: repeat(7,1fr); gap: 2px;
        margin-bottom: 8px;
    }
    .cal-weekdays div {
        text-align: center; font-size: 11px; font-weight: 600;
        color: var(--gray-400); padding: 6px 0;
        text-transform: uppercase; letter-spacing: 1px;
    }
    .cal-days { display: grid; grid-template-columns: repeat(7,1fr); gap: 2px; }
    .cal-day {
        aspect-ratio: 1; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        border-radius: 12px; cursor: pointer;
        transition: all 0.2s; font-size: 14px; font-weight: 500;
        position: relative;
    }
    .cal-day:hover:not(.empty):not(.unavailable) { background: var(--gray-50); }
    .cal-day.selected { background: var(--cafe); color: #fff; }
    .cal-day.selected .cal-dot { background: #fff; }
    .cal-day.available { color: var(--gray-800); }
    .cal-day.unavailable { color: var(--gray-300); cursor: not-allowed; }
    .cal-day.empty { cursor: default; }
    .cal-dot { width: 5px; height: 5px; border-radius: 50%; margin-top: 4px; }
    .cal-day.available .cal-dot { background: var(--teal); }
    .cal-day.unavailable .cal-dot { background: var(--gray-200); }

    .cal-legend {
        display: flex; gap: 16px; padding: 12px 20px 16px;
        font-size: 11px; color: var(--gray-400);
    }
    .cal-legend span { display: flex; align-items: center; gap: 6px; }
    .cal-legend .dot { width: 6px; height: 6px; border-radius: 50%; }

    .date-info-bar {
        display: flex; align-items: center; gap: 14px;
        padding: 16px 20px;
        background: linear-gradient(135deg, #FDF8F0, #F0E4D4);
        border-radius: var(--radius-md); margin-bottom: 24px;
        border: 1px solid rgba(139,111,71,0.15);
    }
    .date-info-bar .icon { font-size: 24px; }
    .date-info-bar .text { font-weight: 600; font-size: 15px; }
    .date-info-bar .sub { font-size: 12px; color: var(--gray-500); }

    .slot-card {
        background: #fff; border: 2px solid var(--gray-200);
        border-radius: var(--radius-md); padding: 16px 20px;
        display: flex; justify-content: space-between; align-items: center;
        cursor: pointer; transition: all 0.3s ease;
        margin-bottom: 10px;
    }
    .slot-card:hover { border-color: var(--cafe-light); background: #FAF6F1; }
    .slot-card.selected {
        border-color: var(--cafe);
        background: linear-gradient(135deg, #FDF8F0, #F0E4D4);
        box-shadow: 0 0 0 4px rgba(139,111,71,0.1);
    }
    .slot-card.unavailable {
        border-color: var(--gray-100); background: var(--gray-50);
        cursor: not-allowed; opacity: 0.5;
    }
    .slot-card .slot-time {
        font-family: 'Playfair Display', serif;
        font-size: 18px; font-weight: 600;
    }
    .slot-card .slot-range { font-size: 12px; color: var(--gray-500); margin-top: 2px; }
    .slot-card .slot-badge { font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 100px; }
    .slot-card .slot-badge.available { background: #D1FAE5; color: #065F46; }
    .slot-card .slot-badge.booked { background: #FEE2E2; color: #991B1B; }

    .summary-card {
        background: #fff; border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden; box-shadow: var(--shadow-md);
    }
    .summary-header {
        padding: 24px 28px;
        background: var(--gradient-4); color: #fff;
    }
    .summary-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 20px; font-weight: 600;
    }
    .summary-body { padding: 28px; }
    .summary-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 0; border-bottom: 1px solid var(--gray-100);
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-row .label { font-size: 13px; color: var(--gray-500); }
    .summary-row .value { font-weight: 600; font-size: 14px; }
    .summary-total {
        margin-top: 20px; padding: 20px 24px;
        background: linear-gradient(135deg, #FDF8F0, #F0E4D4);
        border-radius: var(--radius-md); display: flex;
        justify-content: space-between; align-items: center;
        border: 1px solid rgba(139,111,71,0.15);
    }
    .summary-total .amount {
        font-family: 'Playfair Display', serif;
        font-size: 28px; font-weight: 700; color: var(--cafe-dark);
    }

    .back-btn {
        background: none; border: none; cursor: pointer;
        font-size: 13px; color: var(--cafe); font-weight: 500;
        margin-bottom: 20px; font-family: 'DM Sans', sans-serif;
        display: flex; align-items: center; gap: 6px;
        transition: color 0.3s;
    }
    .back-btn:hover { color: var(--cafe-dark); }

    @media (max-width: 768px) {
        .booking-steps { flex-wrap: wrap; }
        .step-pill span:not(.num) { display: none; }
        .step-pill { padding: 8px 12px; }
    }
    #time-slots::-webkit-scrollbar { width: 6px; }
    #time-slots::-webkit-scrollbar-track { background: var(--gray-100); border-radius: 3px; }
    #time-slots::-webkit-scrollbar-thumb { background: var(--cafe-light); border-radius: 3px; }
    #time-slots::-webkit-scrollbar-thumb:hover { background: var(--cafe); }
</style>
@endsection

@section('content')
<div class="page-header" style="padding:120px 40px 60px;">
    <h1>Book Your Session</h1>
    <p>Select a package, choose your date & time, and complete your booking</p>
    <div class="header-accent"></div>
</div>

<div class="container">
    <div id="app" style="max-width:800px;margin:0 auto;">

        <div class="booking-steps">
            <div class="step-pill active" data-step="1"><span class="num">1</span> <span>Package</span></div>
            <div class="step-pill" data-step="2"><span class="num">2</span> <span>Date & Time</span></div>
            <div class="step-pill" data-step="3"><span class="num">3</span> <span>Details</span></div>
            <div class="step-pill" data-step="4"><span class="num">4</span> <span>Confirm</span></div>
        </div>

        {{-- STEP 1 --}}
        <div class="step" id="step-1">
            <div style="text-align:center;margin-bottom:32px;">
                <h2 style="font-family:'Playfair Display',serif;font-size:28px;font-weight:700;">Choose Your Package</h2>
                <p style="color:var(--gray-500);font-size:14px;margin-top:8px;">Select the experience that suits you best</p>
            </div>
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach($services as $service)
                <div class="pkg-card" data-service-id="{{ $service->id }}" onclick="selectService({{ $service->id }}, '{{ addslashes($service->name) }}', {{ $service->price }}, {{ $service->duration_minutes }}, {{ $service->max_pax }})">
                    <div style="display:flex;align-items:center;gap:16px;">
                        @if($service->image)
                        <div style="width:60px;height:60px;border-radius:10px;overflow:hidden;flex-shrink:0;">
                            <img src="{{ $service->image }}" alt="{{ $service->name }}" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        @endif
                        <div>
                            <div class="pkg-name">{{ $service->name }}</div>
                            <div class="pkg-meta">🕐 {{ $service->duration_label }} · 👥 Up to {{ $service->max_pax }} pax</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div class="pkg-price">₱{{ number_format($service->price, 2) }}</div>
                        <div class="pkg-select">Select →</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- STEP 2 --}}
        <div class="step" id="step-2" style="display:none;">
            <button class="back-btn" onclick="goToStep(1)">← Back to packages</button>
            <div style="text-align:center;margin-bottom:32px;">
                <h2 style="font-family:'Playfair Display',serif;font-size:28px;font-weight:700;">Pick a Date & Time</h2>
                <p id="selected-service-display" style="color:var(--gray-500);font-size:14px;margin-top:8px;"></p>
            </div>

            <div class="cal-card">
                <div class="cal-header">
                    <button onclick="changeMonth(-1)">← Prev</button>
                    <h3 id="calendar-month-label"></h3>
                    <button onclick="changeMonth(1)">Next →</button>
                </div>
                <div class="cal-grid">
                    <div class="cal-weekdays">
                        <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                    </div>
                    <div class="cal-days" id="calendar-grid"></div>
                </div>
                <div class="cal-legend">
                    <span><span class="dot" style="background:var(--teal);"></span> Available</span>
                    <span><span class="dot" style="background:var(--red);"></span> Fully Booked</span>
                    <span><span class="dot" style="background:var(--gray-300);"></span> Closed</span>
                </div>
            </div>

            <div id="selected-date-display" style="display:none;margin-bottom:24px;">
                <div class="date-info-bar">
                    <span class="icon">📅</span>
                    <div>
                        <div class="text" id="selected-date-text"></div>
                        <div class="sub" id="studio-hours-text"></div>
                    </div>
                </div>
            </div>

            <div id="slots-container" style="display:none;">
                <label style="font-size:14px;font-weight:600;margin-bottom:12px;display:block;">Available Time Slots</label>
                <div id="time-slots" style="max-height:260px;overflow-y:auto;border:1px solid var(--gray-200);border-radius:var(--radius-md);padding:8px;"></div>
            </div>

            <div style="margin-top:28px;display:flex;justify-content:flex-end;">
                <button class="btn btn-primary" onclick="goToStep(3)" id="step2-next" disabled style="padding:14px 36px;">Continue →</button>
            </div>
        </div>

        {{-- STEP 3 --}}
        <div class="step" id="step-3" style="display:none;">
            <button class="back-btn" onclick="goToStep(2)">← Back to date & time</button>
            <div style="text-align:center;margin-bottom:32px;">
                <h2 style="font-family:'Playfair Display',serif;font-size:28px;font-weight:700;">Your Details</h2>
            </div>

            <form id="booking-form" onsubmit="submitBooking(event)">
                @csrf
                <input type="hidden" name="service_id" id="form_service_id">
                <input type="hidden" name="booking_date" id="form_booking_date">
                <input type="hidden" name="booking_time" id="form_booking_time">

                <div style="background:#fff;border-radius:var(--radius-lg);padding:32px;border:1px solid var(--gray-200);box-shadow:var(--shadow-sm);">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="customer_name" class="form-control" required placeholder="Juan Dela Cruz">
                        </div>
                        <div class="form-group">
                            <label>Phone Number *</label>
                            <input type="tel" name="customer_phone" class="form-control" required placeholder="09XX XXX XXXX">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="customer_email" class="form-control" required placeholder="your@email.com">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                        <div class="form-group">
                            <label>Number of Pax *</label>
                            <input type="number" name="num_pax" class="form-control" min="1" max="20" value="1" required>
                        </div>
                        <div class="form-group">
                            <label>Payment Method *</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="">Select payment...</option>
                                <option value="full">Full Payment</option>
                                <option value="downpayment">50% Down Payment</option>
                                <option value="gcash">GCash (Full)</option>
                                <option value="paymaya">PayMaya (Full)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Special Requests (optional)</label>
                        <textarea name="special_requests" class="form-control" placeholder="Any special requests or notes..."></textarea>
                    </div>

                    <div class="form-group">
                        <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;">
                                <input type="checkbox" name="agreed_to_policy" required style="margin-top:4px;accent-color:var(--cafe);">
                            <span style="font-size:13px;color:var(--gray-500);">I have read and agree to 56'30 Studio's Payment and Booking Policy *</span>
                        </label>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:28px;">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(2)">Back</button>
                    <button type="submit" class="btn btn-primary" style="padding:14px 36px;">Review Booking →</button>
                </div>
            </form>
        </div>

        {{-- STEP 4 --}}
        <div class="step" id="step-4" style="display:none;">
            <button class="back-btn" onclick="goToStep(3)">← Back to details</button>
            <div style="text-align:center;margin-bottom:32px;">
                <h2 style="font-family:'Playfair Display',serif;font-size:28px;font-weight:700;">Booking Summary</h2>
            </div>

            <div class="summary-card" style="margin-bottom:24px;">
                <div class="summary-header">
                    <h3>Review Your Booking</h3>
                </div>
                <div class="summary-body">
                    <div id="summary-content"></div>
                </div>
            </div>

            <div id="booking-error" class="alert alert-error" style="display:none;"></div>

            <div style="display:flex;justify-content:flex-end;gap:12px;">
                <button class="btn btn-secondary" onclick="goToStep(3)">Edit Details</button>
                <button class="btn btn-primary" onclick="confirmBooking()" id="confirm-btn" style="padding:14px 36px;">Confirm Booking</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let selectedService = null, selectedSlot = null, selectedDate = null;
    let currentMonth = new Date(), calendarDates = [];

    function selectService(id, name, price, duration, maxPax) {
        document.querySelectorAll('.pkg-card').forEach(c => c.classList.remove('selected'));
        document.querySelector(`[data-service-id="${id}"]`).classList.add('selected');
        selectedService = { id, name, price, duration, maxPax };
        document.getElementById('form_service_id').value = id;
        document.getElementById('selected-service-display').textContent = `${name} — ₱${price.toFixed(2)} — ${duration} minutes`;
        selectedDate = null; selectedSlot = null;
        document.getElementById('step2-next').disabled = true;
        document.getElementById('slots-container').style.display = 'none';
        document.getElementById('selected-date-display').style.display = 'none';
        goToStep(2); loadCalendar();
    }

    function goToStep(step) {
        document.querySelectorAll('.step').forEach(s => s.style.display = 'none');
        document.getElementById(`step-${step}`).style.display = 'block';
        document.querySelectorAll('.step-pill').forEach(p => {
            const s = parseInt(p.dataset.step);
            p.classList.remove('active', 'done');
            if (s === step) p.classList.add('active');
            else if (s < step) p.classList.add('done');
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    async function loadCalendar() {
        if (!selectedService) return;
        const m = `${currentMonth.getFullYear()}-${String(currentMonth.getMonth()+1).padStart(2,'0')}-01`;
        document.getElementById('calendar-month-label').textContent = currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        try {
            const r = await fetch(`/booking/calendar?service_id=${selectedService.id}&month=${m}`);
            const d = await r.json();
            calendarDates = d.dates;
            renderCalendar();
        } catch(e) { console.error(e); }
    }

    function renderCalendar() {
        const grid = document.getElementById('calendar-grid');
        const first = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1).getDay();
        let html = '';
        for (let i = 0; i < first; i++) html += '<div class="cal-day empty"></div>';
        calendarDates.forEach(d => {
            const isPast = new Date(d.date+'T00:00:00') < new Date(new Date().toDateString());
            const isSel = selectedDate === d.date;
            let cls = 'cal-day';
            if (isSel) cls += ' selected';
            else if (isPast || d.reason === 'closed' || !d.available) cls += ' unavailable';
            else cls += ' available';
            const click = (!isPast && d.reason !== 'closed' && d.available) ? `onclick="pickDate('${d.date}')"` : '';
            html += `<div class="${cls}" ${click}><div>${d.day||''}</div>${d.day ? '<div class="cal-dot"></div>' : ''}</div>`;
        });
        grid.innerHTML = html;
    }

    function changeMonth(dir) { currentMonth.setMonth(currentMonth.getMonth()+dir); loadCalendar(); }

    async function pickDate(dateStr) {
        selectedDate = dateStr; selectedSlot = null;
        document.getElementById('step2-next').disabled = true;
        renderCalendar();
        const dt = new Date(dateStr+'T00:00:00');
        document.getElementById('selected-date-text').textContent = dt.toLocaleDateString('en-US', { weekday:'long', month:'long', day:'numeric', year:'numeric' });
        document.getElementById('selected-date-display').style.display = 'block';
        const slotsDiv = document.getElementById('time-slots');
        slotsDiv.innerHTML = '<div style="padding:24px;text-align:center;color:var(--gray-400);">Loading time slots...</div>';
        document.getElementById('slots-container').style.display = 'block';
        try {
            const r = await fetch(`/booking/slots?service_id=${selectedService.id}&date=${dateStr}`);
            const data = await r.json();
            if (data.message) { slotsDiv.innerHTML = `<div style="padding:24px;text-align:center;color:var(--red);">${data.message}</div>`; document.getElementById('studio-hours-text').textContent = ''; return; }
            document.getElementById('studio-hours-text').textContent = `Studio hours: ${data.studio_hours} (${data.day_label})`;
            document.getElementById('form_booking_date').value = dateStr;
            if (!data.slots.length) { slotsDiv.innerHTML = '<div style="padding:24px;text-align:center;color:var(--gray-400);">No available slots.</div>'; return; }
            slotsDiv.innerHTML = data.slots.map(s => `
                <div class="slot-card ${s.available?'':'unavailable'}" ${s.available?`onclick="selectSlot('${s.time}','${s.display}')"`:''}>
                    <div>
                        <div class="slot-time">${s.display}</div>
                        <div class="slot-range">until ${s.end_display} · ${selectedService.duration} min</div>
                    </div>
                    <span class="slot-badge ${s.available?'available':'booked'}">${s.available?'Available':'Booked'}</span>
                </div>
            `).join('');
        } catch(e) { slotsDiv.innerHTML = '<div style="padding:24px;text-align:center;color:var(--red);">Error loading slots.</div>'; }
    }

    function selectSlot(time, display) {
        document.querySelectorAll('.slot-card').forEach(s => s.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
        selectedSlot = { time, display };
        document.getElementById('form_booking_time').value = time;
        document.getElementById('step2-next').disabled = false;
    }

    function submitBooking(e) {
        e.preventDefault();
        const data = new FormData(e.target);
        const pm = data.get('payment_method');
        const due = pm === 'downpayment' ? (selectedService.price * 0.5) : selectedService.price;
        document.getElementById('summary-content').innerHTML = `
            <div class="summary-row"><span class="label">Package</span><span class="value">${selectedService.name}</span></div>
            <div class="summary-row"><span class="label">Date</span><span class="value">${new Date(data.get('booking_date')+'T00:00:00').toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'})}</span></div>
            <div class="summary-row"><span class="label">Time</span><span class="value">${selectedSlot.display}</span></div>
            <div class="summary-row"><span class="label">Name</span><span class="value">${data.get('customer_name')}</span></div>
            <div class="summary-row"><span class="label">Phone</span><span class="value">${data.get('customer_phone')}</span></div>
            <div class="summary-row"><span class="label">Email</span><span class="value">${data.get('customer_email')}</span></div>
            <div class="summary-row"><span class="label">Pax</span><span class="value">${data.get('num_pax')}</span></div>
            <div class="summary-row"><span class="label">Payment</span><span class="value">${pm==='downpayment'?'50% Down Payment':pm.toUpperCase()}</span></div>
            <div class="summary-total">
                <div><div style="font-size:13px;color:var(--gray-500);margin-bottom:4px;">Total Amount</div><div class="amount">₱${selectedService.price.toFixed(2)}</div></div>
                <div style="text-align:right;"><div style="font-size:13px;color:var(--gray-500);margin-bottom:4px;">Due Now</div><div style="font-size:20px;font-weight:700;">₱${due.toFixed(2)}</div></div>
            </div>`;
        goToStep(4);
    }

    async function confirmBooking() {
        const btn = document.getElementById('confirm-btn');
        const err = document.getElementById('booking-error');
        btn.disabled = true; btn.textContent = 'Processing...'; err.style.display = 'none';
        const data = new FormData(document.getElementById('booking-form'));
        try {
            const r = await fetch('{{ route("booking.store") }}', { method:'POST', body:data, headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} });
            const res = await r.json();
            if (res.success) window.location.href = res.redirect;
            else { err.textContent = res.message || 'Something went wrong.'; err.style.display = 'block'; btn.disabled = false; btn.textContent = 'Confirm Booking'; }
        } catch(e) { err.textContent = 'Network error.'; err.style.display = 'block'; btn.disabled = false; btn.textContent = 'Confirm Booking'; }
    }
</script>
@endsection
