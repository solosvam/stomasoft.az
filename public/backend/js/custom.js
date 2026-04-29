$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.multi-source-search-input').on('input',searchCustomer);
$('.cashier-net').on('click',toggleCashierAmount);

$(document).on('change', '.role-permission-switch', function() {
    let checked = $(this).is(':checked');
    let roleId = $(this).data('role-id');
    let permId = $(this).data('id');

    $.ajax({
        url: urls.ajaxurls.setRolePermission,
        method: 'POST',
        data: {
            checked: checked,
            role_id: roleId,
            perm_id: permId
        },
        success: function(response) {
            console.log('Permission updated successfully');
        },
        error: function(xhr, status, error) {
            console.error('Error updating permission:', error);
        }
    });
});
$(document).on('change', '.price-selector', function () {
    let selected = $(this).find(':selected');
    let price = selected.data('price') || 0;

    let row = $(this).closest('.row');
    row.find('.price').val(price).trigger('input');
});
function toggleCashierAmount() {
    $(this).toggleClass('hide-money');
}

function initDatePicker() {
    $('.dateLimit').daterangepicker({
        minYear: 2023,
        dateLimit: {
            days: 40
        },
        maxDate: new Date(),
        ranges: {
            'Dünən': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Son 7 gün': [moment().subtract(6, 'days'), moment()],
            'Son 30 gün': [moment().subtract(29, 'days'), moment()],
            'Bu ay': [moment().startOf('month'), moment().endOf('month')]
        }
    });
}
function initInputMask() {
    if (typeof Inputmask === 'undefined') return;
    Inputmask.extendAliases({
        "string": {
            mask: "*{1,50}",
            greedy: false,
            definitions: {
                '*': {
                    validator: function(ch){
                        return typeof ch === 'string' && ch.length === 1 && !/\d/.test(ch);
                    },
                    cardinality: 1
                }
            },
            onincomplete: function(){ $(this).addClass('is-invalid'); },
            oncomplete: function(){ $(this).removeClass('is-invalid'); }
        },
        "mobile": {
            mask: "XYZ9999999",
            placeholder: "",
            definitions: {
                'X': {
                    validator: "[0]",
                    cardinality: 1,
                },
                'Y': {
                    validator: "[1,5,6,7,9]",
                    cardinality: 1,
                },
                'Z': {
                    validator: function (currentDigit, buffer, pos, strict, opts) {
                        var secondDigit = buffer.buffer[1];
                        let canBe = [];

                        if(secondDigit === '1' || secondDigit === '6'){
                            canBe = [0];
                        }

                        if(secondDigit === '5'){
                            canBe = [0,1,5];
                        }

                        if(secondDigit === '7'){
                            canBe = [0,7];
                        }

                        if(secondDigit === '9'){
                            canBe = [9];
                        }

                        return canBe.includes(Number(currentDigit));

                    },
                    cardinality: 1,
                }
            },
            onincomplete: function (){
                $(this).addClass('is-invalid');
            },
            oncomplete: function (){
                $(this).removeClass('is-invalid');
            }
        }
    });

    Inputmask().mask($(".multi-source-search-input"));
}
function searchCustomer() {
    var limit = 6;
    var placeholder = 'AD SOYADLA AXTARIŞ';
    var search_data = $(this).inputmask('unmaskedvalue');
    var type = 'string';

    if($(this).val()[0] === '0' || $(this).val()[0] === '('){
        limit = 10;
        type = 'mobile';
        placeholder = 'MOBİL NÖMRƏ İLƏ AXTARIŞ';
        search_data = "0"+$(this).inputmask('unmaskedvalue').slice(1);
    }

    $(this).attr("data-inputmask","'alias': '"+type+"'");

    $(this).next().html(placeholder);
    initInputMask();
    let input = $(this);
    if ($(this).inputmask('unmaskedvalue').length >= limit) {
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: urls.ajaxurls.searchCustomer,
            data: {
                search: search_data,
                type:type
            },
            cache: false,
            success: function(response){
                const $res = $('.result');
                $res.show().empty();

                if (response.success && response.url) {
                    location.href = response.url;
                    return;
                }

                if (response.success && Array.isArray(response.items)) {
                    // list
                    const html = response.items.map(it => `
                        <a class="list-group-item list-group-item-action p-3" href="${it.url}">
                          <div><b>${it.full_name}</b></div>
                          <small>${it.mobile ?? ''}</small>
                        </a>
                      `).join('');

                    $res.html(`<div class="list-group">${html}</div>`);
                    return;
                }

                input.addClass('is-invalid');
                $res.html(response.message || 'Nəticə tapılmadı');

            }
        });
    }
}

$('.patient-search-reservation').on('input', function () {
    let search_data = $(this).val().trim();
    let $box = $('.search-results-reservation');

    if (search_data.length < 2) {
        $box.addClass('d-none').html('');
        return;
    }

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: urls.ajaxurls.searchCustomerForReservation,
        data: {
            search: search_data,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            let html = '';

            if (response.length) {
                response.forEach(function (item) {
                    html += `
                        <div class="search-item" data-id="${item.id}" data-name="${item.fullname}">
                            <span class="search-name">${item.fullname}</span>
                            <small class="search-mobile">${item.mobile ?? ''}</small>
                        </div>
                    `;
                });

                $box.html(html).removeClass('d-none');
            } else {
                $box.html(`<div class="search-item text-muted" style="cursor:default;">Pasient tapılmadı</div>`).removeClass('d-none');
            }
        }
    });
});

$(document).on('click', '.search-results-reservation .search-item[data-id]', function () {
    let id = $(this).data('id');
    let name = $(this).data('name');

    $('.patient-search-reservation').val(name);
    $('.patient_id').val(id);
    $('.search-results-reservation').addClass('d-none').html('');
});

$(document).on('click', function(e){
    if (!$(e.target).closest('.patient-search-reservation, .search-results-reservation').length) {
        $('.search-results-reservation').addClass('d-none').html('');
    }
});

const select2ServiceOptions = {
    placeholder: 'Xidmət seçin',
    allowClear: true,
    width: '100%',
    dropdownParent: $('#addServiceModal')
};

function addServiceRow() {
    let component = `
    <div class="row align-items-end g-2 mt-2 service-row">
      <div class="col-md-3">
        <select class="form-select select2-service price-selector" name="service_id[]" required>
          <option></option>
          ${$('#serviceTemplate').html()}
        </select>
      </div>
      <div class="col-2">
        <input type="number" name="price[]" class="form-control price" placeholder="Qiymət" required>
      </div>
      <div class="col-2">
        <input type="number" name="percent[]" min="0" max="100" step="0.01" class="form-control percent" placeholder="Endirim" value="0" required>
      </div>
      <div class="col-2">
        <input type="number" name="price_net[]" class="form-control price_net" step="0.01" placeholder="Toplam" required>
      </div>
      <div class="col-2">
        <input type="text" name="note[]" class="form-control" placeholder="Qeyd">
      </div>
      <div class="col-auto">
        <button class="btn btn-danger btn-sm removeService" type="button">-</button>
      </div>
    </div>`;
    $('.service-area').append(component);
    $('.service-area .service-row:last .select2-service').select2(select2ServiceOptions);
}

function addPrescriptionRow() {
    let component = `
        <div class="row align-items-end g-2 mt-2 prescription-row">
            <div class="col-md-4">
                <input type="text" name="medicine_name[]" class="form-control" placeholder="Dərman" required>
            </div>
            <div class="col-3">
                <input type="text" name="dose[]" class="form-control" placeholder="Doza">
            </div>
            <div class="col-4">
                <input type="text" name="usage_text[]" class="form-control" placeholder="İstifadə qaydası" required>
            </div>
            <div class="col-auto">
                <button class="btn btn-danger btn-sm removePrescription" type="button">-</button>
            </div>
        </div>
    `;
    $('.prescription-area').append(component);
}

$(document).on('click', '.addService', function (e) {
    e.preventDefault();
    e.stopPropagation();
    addServiceRow();
});

$(document).on('click', '.addPrescription', function (e) {
    e.preventDefault();
    e.stopPropagation();
    addPrescriptionRow();
});

$(document).on('click', '.removeService', function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).closest('.service-row').remove();
});

$(document).on('click', '.removePrescription', function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).closest('.prescription-row').remove();
});

$(document).on('shown.bs.modal', '.modal', function () {
    $(this).find('.select2').select2({
        dropdownParent: $(this)
    });
});

document.addEventListener('click', function(e){
    const btn = e.target.closest('.open-pay-modal');
    if(!btn) return;

    const mode = btn.dataset.mode;
    const doctorId = btn.dataset.doctorId || '';
    const doctorName = btn.dataset.doctorName || '';
    const max = parseFloat(btn.dataset.max || '0');

    document.getElementById('pay_mode').value = mode;
    document.getElementById('pay_doctor_id').value = doctorId;

    document.getElementById('pay_title').textContent =
        mode === 'total'
            ? 'toplam ödəmə'
            : doctorName + ' üçün ödəmə';

    const amountInput = document.getElementById('pay_amount');
    amountInput.value = '';
    amountInput.max = max > 0 ? max : '';

    document.getElementById('pay_hint').textContent =
        max > 0 ? 'maksimum: ' + max.toFixed(2) + ' ₼' : '';
});

$(document).on('click','.remove-file-btn',function(){

    let id = $(this).data('id');

    if(!confirm('fayl silinsin?')) return;

    $.ajax({
        url: '/patient/files/delete/' + id,
        type: 'POST',
        success: function(res){

            if(res.status){
                $('#file-row-'+id).remove();
            }

        },
        error: function(err){
            console.log(err);
        }
    });

});

if (typeof Dropzone !== 'undefined') {
    Dropzone.autoDiscover = false;

    const patientDropzoneEl = document.getElementById('dropzoneServerFiles');

    if (patientDropzoneEl) {
        new Dropzone('#dropzoneServerFiles', {
            url: patientDropzoneEl.dataset.uploadUrl,
            method: "post",
            paramName: "file",
            maxFilesize: 10,
            acceptedFiles: "image/*,.pdf",
            thumbnailWidth: 160,
            previewTemplate: typeof DropzoneTemplates !== 'undefined' ? DropzoneTemplates.previewTemplate : undefined,
            clickable: true,
            addRemoveLinks: true,
            dictRemoveFile: "sil",

            init: function () {
                this.on('success', function (file, response) {
                    file.serverId = response.file_id;

                    $('#patientFilesTbody .no-file-row').remove();

                    $('#patientFilesTbody').append(`
                        <tr id="file-row-${response.file_id}">
                            <td>${response.count}</td>
                            <td><a href="${response.url}" target="_blank">${response.file}</a></td>
                            <td>${response.date}</td>
                            <td class="text-end">
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger remove-file-btn"
                                        data-id="${response.file_id}">
                                    sil
                                </button>
                            </td>
                        </tr>
                    `);

                    $('#patientFilesCount').text(response.count);
                });

                this.on('error', function (file, response) {
                    console.log(response);
                });
            },

            removedfile: function (file) {
                if (file.serverId) {
                    $.ajax({
                        url: '/patient/files/delete/' + file.serverId,
                        type: 'POST',
                        success: function () {
                            if (file.previewElement) {
                                file.previewElement.remove();
                            }
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                } else {
                    if (file.previewElement) {
                        file.previewElement.remove();
                    }
                }
            }
        });
    }
}

let selectedTeeth = [];

document.querySelectorAll('#addServiceModal .teeth').forEach(el => {
    el.addEventListener('click', (e) => {
        const cls = [...el.classList].find(c => /^tooth-\d+$/.test(c));
        if (!cls) return;

        const toothNo = parseInt(cls.split('-')[1]);

        if (e.shiftKey) {
            if (el.classList.contains('worked')) {
                openToothServicesModal(toothNo);
            }
            return;
        }

        if (e.ctrlKey || e.metaKey) {
            if (selectedTeeth.includes(toothNo)) {
                selectedTeeth = selectedTeeth.filter(id => id !== toothNo);
                document.querySelectorAll(`#addServiceModal .tooth-${toothNo}`)
                    .forEach(x => x.classList.remove('selected'));
            } else {
                selectedTeeth.push(toothNo);
                document.querySelectorAll(`#addServiceModal .tooth-${toothNo}`)
                    .forEach(x => x.classList.add('selected'));
            }
        } else {
            selectedTeeth = [toothNo];

            document.querySelectorAll('#addServiceModal .teeth.selected')
                .forEach(x => x.classList.remove('selected'));

            document.querySelectorAll(`#addServiceModal .tooth-${toothNo}`)
                .forEach(x => x.classList.add('selected'));
        }

        document.querySelectorAll('#addServiceForm input[name="tooth_id[]"]').forEach(x => x.remove());

        const form = document.getElementById('addServiceForm');
        selectedTeeth.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'tooth_id[]';
            input.value = id;
            form.appendChild(input);
        });
    });
});

function openToothServicesModal(toothNo) {

    $('#toothServicesModal').modal('show');
    $('#toothServicesModalBody').html('Yüklənir...');

    let patientId = $('#patient_id').val();

    $.get('/crm/tooth-services/' + patientId, {
        tooth_id: toothNo
    }, function(html){
        $('#toothServicesModalBody').html(html);
    });
}

const modalEl = document.getElementById('addServiceModal')

if (modalEl) {
    modalEl.addEventListener('shown.bs.modal', async () => {
        document.querySelectorAll('#addServiceModal .teeth.worked')
            .forEach(el => el.classList.remove('worked'))

        const url = modalEl.dataset.workedUrl
        const res = await fetch(url)
        const data = await res.json()

        ;(data.worked || []).forEach(n => {
            document.querySelectorAll(`#addServiceModal .tooth-${n}`)
                .forEach(el => el.classList.add('worked'))
        })
    })
}

$(document).on('input', '.price, .percent, .price_net', function () {
    let row = $(this).closest('.row');

    let price = parseFloat(row.find('.price').val()) || 0;
    let percent = parseFloat(row.find('.percent').val()) || 0;
    let priceNet = parseFloat(row.find('.price_net').val()) || 0;

    if ($(this).hasClass('price') || $(this).hasClass('percent')) {
        let net = price - ((price * percent) / 100);
        row.find('.price_net').val(net > 0 ? net.toFixed(2) : 0);
    }

    if ($(this).hasClass('price_net') && price > 0) {
        let calcPercent = ((price - priceNet) / price) * 100;
        row.find('.percent').val(calcPercent >= 0 ? calcPercent.toFixed(2) : 0);
    }
});
$(document).on('change', '#reservation_date', function () {
    var date = $("#reservation_date").val();
    var doctor_id = $("#reservation_date").data("doctor");
    $.ajax({
        type: "POST",
        url: '/reservations/hours',
        data: {
            date: date,
            doctor:doctor_id
        },
        cache: false,
        success: function(html) {
            $(".dates").html(html);
            $("#reservation_hour").val('');
        }
    });
});

function setTime(hour, viewHour){
    $("#reservation_hour").val(viewHour);
    $(".dates button").removeClass('btn-primary').addClass('btn-success');
    $("#" + hour).removeClass('btn-success').addClass('btn-primary');
}

$(document).on('click', 'input[type="date"]', function(){
    this.showPicker && this.showPicker();
});

document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('newReservationSlot');
    if (!modalEl) return;
    const modal = new bootstrap.Modal(modalEl);

    document.querySelectorAll('.open-reservation-modal').forEach(button => {
        button.addEventListener('click', function () {
            const date = this.dataset.date;
            const time = this.dataset.time;

            document.getElementById('reservation_date_slot').value = date;
            document.getElementById('reservation_hour_slot').value = time;

            document.getElementById('selected_reservation_date').innerText = date;
            document.getElementById('selected_reservation_hour').innerText = time;

            modal.show();
        });
    });
});

document.querySelectorAll('.language-switch-container .dropdown-item').forEach(function(item) {
    item.addEventListener('click', function(e) {
        e.preventDefault();

        let lang = this.dataset.lang;
        let url = new URL(window.location.href);

        url.searchParams.set('lang', lang);

        window.location.href = url.toString();
    });
});

$('.subscriptionBtn').on('click', function () {
    let url = $(this).data('url');

    $('#subscriptionContent').html('Yüklənir...');

    $.get(url, function (res) {
        $('#subscriptionContent').html(res.html);
    });
});
