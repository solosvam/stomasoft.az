@php
    $html_tag_data = [];
    $title = __('edit');
    $breadcrumbs = ["/admin"=> "White Dent", route('admin.crm.index') => "CRM", "#"=> __('edit')];
    $locationMap = auth()->user()->locationMap();

    $locationMapPartial = 'admin.crm.partial.maps._' . $locationMap;
@endphp

@extends('admin.layout', ['title' => $title])

@section('js_page')

    <script src="{{ asset('backend/js/jquery.inputmask.js') }}"></script>

    <script>
        const locationMap = @json(
            $locations->mapWithKeys(fn($location) => [
                (string) $location->code => (int) $location->id
            ])
        );

        let selectedLocationIds = @json($sessionLocationIds ?? []);

        selectedLocationIds = selectedLocationIds.map(Number);

        function syncLocationInputs() {
            const form = document.getElementById('editSessionForm');

            if (!form) {
                return;
            }

            form.querySelectorAll(
                'input[name="location_id[]"]'
            ).forEach(el => el.remove());

            selectedLocationIds.forEach(locationId => {
                const input = document.createElement('input');

                input.type = 'hidden';
                input.name = 'location_id[]';
                input.value = locationId;

                form.appendChild(input);
            });
        }

        function renderSelectedLocations() {

            /*
             * DİŞ XƏRİTƏSİ
             */
            @if($locationMap === 'tooth_map')

            document.querySelectorAll('.teeth')
                .forEach(el => el.classList.remove('selected'));

            document.querySelectorAll('.teeth').forEach(el => {

                const cls = [...el.classList].find(c =>
                    /^tooth-\d+$/.test(c)
                );

                if (!cls) {
                    return;
                }

                const toothCode = cls.replace('tooth-', '');

                const locationId = Number(
                    locationMap[toothCode]
                );

                if (
                    locationId &&
                    selectedLocationIds.includes(locationId)
                ) {
                    el.classList.add('selected');
                }
            });

            @endif


            /*
             * FULL BODY MAP
             */
            @if($locationMap === 'full_body_map')

            document.querySelectorAll('.map-zone')
                .forEach(el => el.classList.remove('selected'));

            selectedLocationIds.forEach(locationId => {

                document.querySelectorAll(
                    `.map-zone[data-location-id="${locationId}"]`
                ).forEach(el => {
                    el.classList.add('selected');
                });

            });

            @endif

            syncLocationInputs();
        }


        function toggleLocation(locationId, multiSelect = false) {

            locationId = Number(locationId);

            if (!locationId) {
                return;
            }

            if (multiSelect) {

                if (selectedLocationIds.includes(locationId)) {

                    selectedLocationIds =
                        selectedLocationIds.filter(
                            id => id !== locationId
                        );

                } else {

                    selectedLocationIds.push(locationId);

                }

            } else {

                /*
                 * eyni seçilmiş hissəyə yenidən klik
                 * olunanda seçimi götür
                 */
                if (
                    selectedLocationIds.length === 1 &&
                    selectedLocationIds.includes(locationId)
                ) {
                    selectedLocationIds = [];
                } else {
                    selectedLocationIds = [locationId];
                }
            }

            renderSelectedLocations();
        }


        function addEditServiceRow() {

            let index =
                $('.service-area-edit .service-edit-row').length;

            let component = `
                <div class="row mt-2 align-items-end g-2 service-edit-row">

                    <div class="col-md-3">
                        <select
                            class="form-select"
                            name="items[${index}][service_id]"
                            required
                        >
                            <option value="">
                                {{ __('select_service') }}
            </option>

${$('#serviceTemplateEdit').html()}
                        </select>
                    </div>

                    <div class="col-2">
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="items[${index}][price]"
                            class="form-control price"
                            placeholder="{{ __('price') }}"
                            required
                        >
                    </div>

                    <div class="col-2">
                        <input
                            type="number"
                            min="0"
                            max="100"
                            step="0.01"
                            name="items[${index}][percent]"
                            class="form-control percent"
                            value="0"
                            placeholder="{{ __('discount') }}"
                            required
                        >
                    </div>

                    <div class="col-2">
                        <input
                            type="number"
                            step="0.01"
                            name="items[${index}][price_net]"
                            class="form-control price_net"
                            placeholder="{{ __('total_price') }}"
                            required
                        >
                    </div>

                    <div class="col-2">
                        <input
                            type="text"
                            name="items[${index}][note]"
                            class="form-control"
                            placeholder="{{ __('note') }}"
                        >
                    </div>

                    <div class="col-1">
                        <button
                            class="btn btn-danger btn-sm removeEditService"
                            type="button"
                        >
                            -
                        </button>
                    </div>

                </div>
            `;

            $('.service-area-edit').append(component);
        }


        $(document).ready(function () {

            /*
             * İlk açılışda sessiyada olan
             * location-ları seçilmiş göstər
             */
            renderSelectedLocations();


            /*
             * DİŞ CLICK
             */
            @if($locationMap === 'tooth_map')

            document.querySelectorAll('.teeth').forEach(el => {

                el.addEventListener('click', (e) => {

                    const cls =
                        [...el.classList].find(c =>
                            /^tooth-\d+$/.test(c)
                        );

                    if (!cls) {
                        return;
                    }

                    const toothCode =
                        cls.replace('tooth-', '');

                    const locationId =
                        Number(locationMap[toothCode]);

                    if (!locationId) {
                        console.error(
                            'service_locations tapılmadı:',
                            toothCode
                        );

                        return;
                    }

                    toggleLocation(
                        locationId,
                        e.ctrlKey || e.metaKey
                    );
                });

            });

            @endif


            /*
             * BODY CLICK
             */
            @if($locationMap === 'full_body_map')

            document.querySelectorAll('.map-zone')
                .forEach(el => {

                    el.addEventListener('click', (e) => {

                        const locationId =
                            Number(el.dataset.locationId);

                        if (!locationId) {
                            return;
                        }

                        toggleLocation(
                            locationId,
                            e.ctrlKey || e.metaKey
                        );
                    });

                });

            @endif


            $(document).on(
                'click',
                '.addServiceEditRow',
                function (e) {

                    e.preventDefault();
                    e.stopPropagation();

                    addEditServiceRow();
                }
            );


            $(document).on(
                'click',
                '.removeEditService',
                function (e) {

                    e.preventDefault();
                    e.stopPropagation();

                    if (
                        $('.service-area-edit .service-edit-row')
                            .length <= 1
                    ) {
                        return;
                    }

                    $(this)
                        .closest('.service-edit-row')
                        .remove();
                }
            );
        });


        $(document).on(
            'input',
            '.price, .percent, .price_net',
            function () {

                let row = $(this).closest('.row');

                let price =
                    parseFloat(row.find('.price').val()) || 0;

                let percent =
                    parseFloat(row.find('.percent').val()) || 0;

                let priceNet =
                    parseFloat(row.find('.price_net').val()) || 0;


                if (
                    $(this).hasClass('price') ||
                    $(this).hasClass('percent')
                ) {

                    let net =
                        price - ((price * percent) / 100);

                    row.find('.price_net').val(
                        net > 0
                            ? net.toFixed(2)
                            : 0
                    );
                }


                if (
                    $(this).hasClass('price_net') &&
                    price > 0
                ) {

                    let calcPercent =
                        ((price - priceNet) / price) * 100;

                    row.find('.percent').val(
                        calcPercent >= 0
                            ? calcPercent.toFixed(2)
                            : 0
                    );
                }
            }
        );

    </script>

@endsection

@section('content')
    <div class="container">
        <div class="page-title-container">
            <div class="row">
                <div class="col-12 col-md-7">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    @include('admin._layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                </div>

                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row gx-4 gy-4">

                <div class="col-12">
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                                    <div id="serviceTemplateEdit" class="d-none">
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </div>

                                    <div class="mb-2">
                                        @if($locationMap === 'tooth_map')
                                            <cite>{{ __('ctrl_select_teeth') }}</cite>
                                        @elseif($locationMap === 'full_body_map')
                                            <cite>{{ __('ctrl_select_zones') }}</cite>
                                        @endif
                                        @if(view()->exists($locationMapPartial))
                                            @include($locationMapPartial)
                                        @else
                                            <div class="alert alert-warning mb-0">Bu ixtisas üçün bədən xəritəsi hələ hazır deyil.</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-8 col-md-12">
                                    <form method="POST" action="{{ route('admin.crm.session.update', $session->id) }}" id="editSessionForm">
                                        @csrf
                                        @if(in_array($locationMap, ['tooth_map', 'full_body_map']))
                                            @foreach($sessionLocationIds as $locationId)
                                                <input
                                                    type="hidden"
                                                    name="location_id[]"
                                                    value="{{ $locationId }}"
                                                >
                                            @endforeach
                                        @endif
                                        <div class="service-area-edit mb-3">
                                            @foreach($groupedItems as $item)
                                                <div class="align-items-end g-2 row mt-2 service-edit-row">
                                                    <div class="col-md-3">
                                                        <select class="form-select" name="items[{{ $loop->index }}][service_id]" required>
                                                            <option value="">Xidmət seçin</option>
                                                            @foreach($services as $service)
                                                                <option value="{{ $service->id }}"
                                                                    {{ (int)$item->service_id === (int)$service->id ? 'selected' : '' }}>
                                                                    {{ $service->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-2">
                                                        <input type="number" step="0.01" min="0" name="items[{{ $loop->index }}][price]" class="form-control price" value="{{ $item->price }}" required>
                                                    </div>

                                                    <div class="col-2">
                                                        <input type="number" step="0.01" min="0" max="100" name="items[{{ $loop->index }}][percent]" class="form-control percent" value="{{ $item->percent }}" required>
                                                    </div>

                                                    <div class="col-2">
                                                        <input type="number" step="0.01" name="items[{{ $loop->index }}][price_net]" class="form-control price_net" value="{{ $item->price_net }}" required>
                                                    </div>

                                                    <div class="col-2">
                                                        <input type="text" name="items[{{ $loop->index }}][note]" class="form-control" value="{{ $item->note }}">
                                                    </div>

                                                    <div class="col-auto">
                                                        <button class="btn btn-primary btn-sm addServiceEditRow" type="button">+</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <label class="top-label w-100">
                                            <textarea class="form-control" name="comment" rows="4">{{ $session->note }}</textarea>
                                            <span>{{ __('general_note') }}</span>
                                        </label>

                                        <button class="btn btn-success mt-3" type="submit">{{ __('update') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
