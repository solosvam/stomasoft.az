<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('frontend.partials.head', [
        'title' => __('site.seo_patients_title'),
        'desc'  => __('site.seo_patients_desc'),
    ])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include('frontend.partials.nav', ['active' => 'patients'])

<section class="page-head" style="padding-bottom:40px">
    <div class="wrap">
        <div class="pill"><i></i>{{ __('patients.badge') }}</div>
        <h1>{{ __('patients.headline') }}</h1>
        <p>{{ __('patients.subheadline') }}</p>

        <div class="search">
            @include('frontend.partials.icon', ['n' => 'search'])
            <input type="text" id="searchInput" placeholder="{{ __('patients.search_placeholder') }}">
        </div>
    </div>
</section>

<main class="wrap" style="min-height:40vh">
    @php
        $colors = [
            ['bg' => '#eaf4fd', 'color' => '#0a62d8'],
            ['bg' => '#e3f6fd', 'color' => '#0688eb'],
            ['bg' => '#e8edfb', 'color' => '#072a7d'],
            ['bg' => '#e6f6ef', 'color' => '#16a36a'],
            ['bg' => '#fff4dd', 'color' => '#b7791f'],
        ];
    @endphp

    <div class="clinics" id="clinicsGrid">
        @forelse($doctors as $index => $doctor)
            @php
                $color    = $colors[$index % count($colors)];
                $initials = collect(explode(' ', $doctor->doctorProfile?->clinic_name))
                                ->take(2)
                                ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                ->implode('');
                $services = $doctor->services ?? collect();
            @endphp

            <div class="clinic"
                 data-search="{{ strtolower($doctor->doctorProfile?->clinic_name . ' ' . $doctor->fullname . ' ' . $doctor->doctorProfile?->clinic_address) }}"
                 onclick="openModal({{ $doctor->id }}, '{{ addslashes($doctor->doctorProfile?->clinic_name) }}', '{{ addslashes($doctor->fullname) }}', '{{ addslashes($doctor->doctorProfile?->clinic_address) }}', '{{ $initials }}', '{{ $color['bg'] }}', '{{ $color['color'] }}', {{ $services->toJson() }})">

                <div class="clinic-top">
                    <div class="avatar" style="background: {{ $color['bg'] }}; color: {{ $color['color'] }}">{{ $initials }}</div>
                    <div>
                        <div class="clinic-name">{{ $doctor->doctorProfile?->clinic_name }}</div>
                        <div class="doctor-name">Dr. {{ $doctor->fullname }}</div>
                    </div>
                </div>

                <div class="clinic-addr">
                    @include('frontend.partials.icon', ['n' => 'pin'])
                    {{ $doctor->doctorProfile?->clinic_address }}
                </div>

                <div class="clinic-foot">
                    <span class="avail">{{ __('patients.available') }}</span>
                    <span class="book">{{ __('patients.book') }} @include('frontend.partials.icon', ['n' => 'arrow'])</span>
                </div>
            </div>
        @empty
            <div class="empty" style="display:block; grid-column: 1/-1;">{{ __('patients.no_clinics') }}</div>
        @endforelse
    </div>

    <div class="empty" id="noResults">{{ __('patients.no_results') }}</div>
</main>

@include('frontend.partials.footer')

{{-- Reservation modal --}}
<div class="overlay" id="modalOverlay" onclick="closeModalOnBg(event)">
    <div class="modal" id="modalBox">

        <div class="modal-head">
            <div class="modal-title">{{ __('patients.modal_title') }}</div>
            <button class="modal-x" type="button" onclick="closeModal()" aria-label="Close">×</button>
        </div>

        <div class="modal-clinic">
            <div class="modal-avatar" id="modalAvatar"></div>
            <div>
                <div class="modal-clinic-name" id="modalClinicName"></div>
                <div class="modal-doctor-name" id="modalDoctorName"></div>
            </div>
        </div>

        <div id="modalAlert" style="display:none"></div>

        <form id="reservationForm" onsubmit="submitReservation(event)">
            @csrf
            <input type="hidden" id="doctorIdInput" name="user_id">

            <div class="field">
                <label>{{ __('patients.fullname') }}</label>
                <input type="text" name="fullname" id="fullnameInput"
                       placeholder="{{ __('patients.fullname_placeholder') }}" autocomplete="name">
            </div>

            <div class="field">
                <label>{{ __('patients.mobile') }}</label>
                <input type="tel" name="mobile" id="mobileInput"
                       placeholder="+994 XX XXX XX XX" autocomplete="tel">
            </div>

            <div class="field">
                <label>{{ __('patients.service') }}</label>
                <select name="service_id" id="serviceSelect">
                    <option value="">{{ __('patients.service_placeholder') }}</option>
                </select>
            </div>

            <div class="field">
                <label>{{ __('patients.date') }}</label>
                <input type="date" name="date" id="dateInput" min="{{ date('Y-m-d') }}" onchange="loadHours()">
            </div>

            <input type="hidden" name="hour" id="hourInput">

            <div class="time-section">
                <span class="time-label">{{ __('patients.choose_time') }}</span>
                <div id="hours-container">
                    <div class="hours-placeholder">{{ __('patients.date_first') }}</div>
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">{{ __('patients.submit') }}</button>
        </form>

    </div>
</div>

<script>
    let currentDoctorId = null;

    /* ── Open modal ── */
    function openModal(doctorId, clinicName, doctorName, address, initials, avatarBg, avatarColor, services) {
        currentDoctorId = doctorId;

        document.getElementById('doctorIdInput').value = doctorId;
        document.getElementById('modalClinicName').textContent = clinicName;
        document.getElementById('modalDoctorName').textContent = 'Dr. ' + doctorName;

        const avatar = document.getElementById('modalAvatar');
        avatar.textContent = initials;
        avatar.style.background = avatarBg;
        avatar.style.color = avatarColor;

        const serviceSelect = document.getElementById('serviceSelect');
        serviceSelect.innerHTML = '<option value="">{{ __('patients.service_placeholder') }}</option>';
        if (services && services.length) {
            services.forEach(function(s) {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.name;
                serviceSelect.appendChild(opt);
            });
        }

        document.getElementById('dateInput').value = '';
        document.getElementById('hourInput').value = '';
        document.getElementById('hours-container').innerHTML = '<div class="hours-placeholder">{{ __('patients.date_first') }}</div>';
        document.getElementById('modalAlert').style.display = 'none';
        document.getElementById('reservationForm').reset();
        document.getElementById('doctorIdInput').value = doctorId;

        document.getElementById('modalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    /* ── Close modal ── */
    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('active');
        document.body.style.overflow = '';
        currentDoctorId = null;
    }

    function closeModalOnBg(e) {
        if (e.target === document.getElementById('modalOverlay')) closeModal();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
    /* ── Load hours via AJAX ── */
    function loadHours() {
        const date = document.getElementById('dateInput').value;
        const doctorId = currentDoctorId;

        if (!date || !doctorId) return;

        document.getElementById('hourInput').value = '';
        document.getElementById('hours-container').innerHTML =
            '<div class="hours-loading">{{ __("patients.loading") }}</div>';

        const params = new URLSearchParams({
            date: date,
            doctor: doctorId
        });

        fetch('{{ route('page.reservation.hours') }}?' + params.toString(), {
            method: 'GET'
        })
            .then(r => r.text())
            .then(html => {
                document.getElementById('hours-container').innerHTML =
                    html || '<div class="hours-placeholder">{{ __("patients.no_slots") }}</div>';
            })
            .catch(() => {
                document.getElementById('hours-container').innerHTML =
                    '<div class="hours-placeholder">{{ __("patients.error") }}</div>';
            });
    }

    /* ── Time slot select (called from reservationHours HTML) ── */
    function setFrontTime(id, time) {
        document.querySelectorAll('#hours-container .slot-btn.btn-success').forEach(function(btn) {
            btn.classList.remove('selected');
        });
        const btn = document.getElementById(id);
        if (btn) btn.classList.add('selected');
        document.getElementById('hourInput').value = time;
    }

    /* ── Submit reservation ── */
    function submitReservation(e) {
        e.preventDefault();

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = '{{ __('patients.sending') }}';

        const formData = new FormData(document.getElementById('reservationForm'));

        fetch('{{ route('page.reservation.store') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            const alertBox = document.getElementById('modalAlert');
            alertBox.style.display = 'block';

            if (data.success) {
                alertBox.className = 'alert-custom alert-success';
                alertBox.textContent = data.message || '{{ __('patients.success') }}';
                document.getElementById('reservationForm').reset();
                document.getElementById('hours-container').innerHTML = '<div class="hours-placeholder">{{ __('patients.date_first') }}</div>';
                setTimeout(closeModal, 2500);
            } else {
                alertBox.className = 'alert-custom alert-error';
                alertBox.textContent = data.message || '{{ __('patients.error') }}';
            }
        })
        .catch(function() {
            const alertBox = document.getElementById('modalAlert');
            alertBox.style.display = 'block';
            alertBox.className = 'alert-custom alert-error';
            alertBox.textContent = '{{ __('patients.error') }}';
        })
        .finally(function() {
            btn.disabled = false;
            btn.textContent = '{{ __('patients.submit') }}';
        });
    }

    /* ── Search / filter ── */
    document.getElementById('searchInput').addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.clinic');
        let visible = 0;

        cards.forEach(function(card) {
            const match = !q || card.dataset.search.includes(q);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
    });
</script>

@include('frontend.partials.analytics')
</body>
</html>
