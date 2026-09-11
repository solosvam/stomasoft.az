document.addEventListener('DOMContentLoaded', function () {

    const config = document.getElementById('tcrmDoctorConfig');

    if (!config) {
        return;
    }

    const locationMap = JSON.parse(config.dataset.locationMap || '{}');

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function getToothCode(element) {
        for (const className of element.classList) {
            const match = className.match(/^tooth-(\d+)$/);

            if (match) {
                return match[1];
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Yeni iş
    |--------------------------------------------------------------------------
    */

    let pendingTeeth = new Set();
    let assignedTeeth = new Set();
    let groupIndex = 0;

    function paintNewJobTooth(code, className, add = true) {
        document.querySelectorAll('#newJobModal .tooth-' + code).forEach(function (element) {
            if (add) {
                element.classList.add(className);
            } else {
                element.classList.remove(className);
            }
        });
    }

    function clearPendingTeeth() {
        pendingTeeth.forEach(function (code) {
            paintNewJobTooth(code, 'job-pending-tooth', false);
        });

        pendingTeeth.clear();
    }

    function calculateJobTotal() {
        let total = 0;

        document.querySelectorAll('#jobGroups .job-group-total').forEach(function (input) {
            total += parseFloat(input.value || 0);
        });

        const totalElement = document.getElementById('jobTotal');

        if (totalElement) {
            totalElement.textContent = total.toFixed(2);
        }
    }

    function updateGroupTotal(group) {
        const teeth = group.dataset.teeth.split(',');
        const price = parseFloat(group.querySelector('.job-group-price').value || 0);
        const total = price * teeth.length;

        group.querySelector('.job-group-total').value = total.toFixed(2);

        calculateJobTotal();
    }

    document.addEventListener('click', function (e) {
        const tooth = e.target.closest('#newJobModal .teeth');

        if (!tooth) {
            return;
        }

        const code = getToothCode(tooth);

        if (!code || assignedTeeth.has(code)) {
            return;
        }

        const multiple = e.ctrlKey || e.metaKey;

        if (!multiple) {
            clearPendingTeeth();
        }

        if (pendingTeeth.has(code)) {
            pendingTeeth.delete(code);
            paintNewJobTooth(code, 'job-pending-tooth', false);
        } else {
            pendingTeeth.add(code);
            paintNewJobTooth(code, 'job-pending-tooth', true);
        }
    });

    const addSelectedTeethGroup = document.getElementById('addSelectedTeethGroup');

    if (addSelectedTeethGroup) {
        addSelectedTeethGroup.addEventListener('click', function () {
            if (!pendingTeeth.size) {
                return;
            }

            const teeth = Array.from(pendingTeeth);
            const index = groupIndex++;

            let locationInputs = '';

            teeth.forEach(function (code) {
                if (!locationMap[code]) {
                    return;
                }

                locationInputs += `<input type="hidden" name="items[${index}][location_ids][]" value="${locationMap[code]}">`;
            });

            const html = `
                <div class="job-group border rounded p-2 mb-2" data-group="${index}" data-teeth="${teeth.join(',')}">
                    ${locationInputs}

                    <div class="row align-items-end g-2">
                        <div class="col-md-2">
                            <label>Dişlər</label>
                            <input type="text" class="form-control" value="${teeth.join(', ')}" readonly>
                        </div>

                        <div class="col-md-4">
                            <label>Xidmət</label>
                            <select class="form-select job-group-service" name="items[${index}][service_id]" required>
                                ${document.getElementById('jobServiceTemplate').innerHTML}
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Qiymət</label>
                            <input type="number" class="form-control job-group-price" name="items[${index}][price]" step="0.01" min="0" required>
                        </div>

                        <div class="col-md-1">
                            <label>Say</label>
                            <input type="number" class="form-control" value="${teeth.length}" readonly>
                        </div>

                        <div class="col-md-2">
                            <label>Toplam</label>
                            <input type="number" class="form-control job-group-total" value="0.00" readonly>
                        </div>

                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger removeJobGroup">×</button>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('jobGroups').insertAdjacentHTML('beforeend', html);

            teeth.forEach(function (code) {
                assignedTeeth.add(code);

                paintNewJobTooth(code, 'job-pending-tooth', false);
                paintNewJobTooth(code, 'job-assigned-tooth', true);
            });

            pendingTeeth.clear();
        });
    }

    document.addEventListener('change', function (e) {
        if (!e.target.matches('#newJobModal .job-group-service')) {
            return;
        }

        const group = e.target.closest('.job-group');
        const option = e.target.options[e.target.selectedIndex];
        const price = parseFloat(option.dataset.price || 0);

        group.querySelector('.job-group-price').value = price.toFixed(2);

        updateGroupTotal(group);
    });

    document.addEventListener('input', function (e) {
        if (!e.target.matches('#newJobModal .job-group-price')) {
            return;
        }

        updateGroupTotal(e.target.closest('.job-group'));
    });

    document.addEventListener('click', function (e) {
        const button = e.target.closest('#newJobModal .removeJobGroup');

        if (!button) {
            return;
        }

        const group = button.closest('.job-group');
        const teeth = group.dataset.teeth.split(',');

        teeth.forEach(function (code) {
            assignedTeeth.delete(code);
            paintNewJobTooth(code, 'job-assigned-tooth', false);
        });

        group.remove();

        calculateJobTotal();
    });

    const newJobModal = document.getElementById('newJobModal');

    if (newJobModal) {
        newJobModal.addEventListener('hidden.bs.modal', function () {
            pendingTeeth.forEach(function (code) {
                paintNewJobTooth(code, 'job-pending-tooth', false);
            });

            assignedTeeth.forEach(function (code) {
                paintNewJobTooth(code, 'job-assigned-tooth', false);
            });

            pendingTeeth.clear();
            assignedTeeth.clear();

            document.getElementById('jobGroups').innerHTML = '';
            document.getElementById('jobTotal').textContent = '0.00';

            groupIndex = 0;

            const form = document.getElementById('newJobForm');

            if (form) {
                form.reset();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Mövcud job xidmətləri
    |--------------------------------------------------------------------------
    */

    let usedJobTeeth = new Set();
    let pendingJobTeeth = new Set();
    let serviceGroupIndex = 0;

    function paintJobServiceTooth(code, className, add = true) {
        document.querySelectorAll('#jobServicesModal .tooth-' + code).forEach(function (element) {
            if (add) {
                element.classList.add(className);
            } else {
                element.classList.remove(className);
            }
        });
    }

    function clearJobServiceMap() {
        document.querySelectorAll('#jobServicesModal .job-used-tooth, #jobServicesModal .job-pending-tooth').forEach(function (element) {
            element.classList.remove('job-used-tooth', 'job-pending-tooth');
        });
    }

    function calculateNewJobServiceTotal() {
        let total = 0;

        document.querySelectorAll('#newJobGroups .job-service-total').forEach(function (input) {
            total += parseFloat(input.value || 0);
        });

        const totalElement = document.getElementById('newJobServiceTotal');

        if (totalElement) {
            totalElement.textContent = total.toFixed(2);
        }
    }

    function updateJobServiceGroupTotal(group) {
        const teeth = group.dataset.teeth.split(',');
        const price = parseFloat(group.querySelector('.job-service-price').value || 0);
        const total = price * teeth.length;

        group.querySelector('.job-service-total').value = total.toFixed(2);

        calculateNewJobServiceTotal();
    }

    document.addEventListener('click', function (e) {
        const button = e.target.closest('.openJobServices');

        if (!button) {
            return;
        }

        const servicesUrl = button.dataset.servicesUrl;
        const addUrl = button.dataset.addUrl;

        usedJobTeeth.clear();
        pendingJobTeeth.clear();
        serviceGroupIndex = 0;

        clearJobServiceMap();

        document.getElementById('existingJobGroups').innerHTML = '';
        document.getElementById('newJobGroups').innerHTML = '';
        document.getElementById('newJobServiceTotal').textContent = '0.00';

        document.getElementById('jobServicesForm').action = addUrl;

        fetch(servicesUrl)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Xidmətlər yüklənmədi');
                }

                return response.json();
            })
            .then(function (data) {
                data.items.forEach(function (item) {
                    const codes = item.locations
                        .map(function (location) {
                            return location.code;
                        })
                        .filter(Boolean);

                    codes.forEach(function (code) {
                        code = String(code);

                        usedJobTeeth.add(code);
                        paintJobServiceTooth(code, 'job-used-tooth', true);
                    });

                    const html = `
                        <div class="tcrm-existing-group border rounded p-2 mb-2">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div>
                                    <strong>${codes.join(', ')}</strong>
                                    <span class="mx-1">|</span>
                                    ${item.service ?? '---'}
                                </div>

                                <div>
                                    ${parseFloat(item.price || 0).toFixed(2)} ₼
                                    × ${codes.length}
                                    =
                                    <strong>${parseFloat(item.total_price || 0).toFixed(2)} ₼</strong>
                                </div>
                            </div>
                        </div>
                    `;

                    document.getElementById('existingJobGroups').insertAdjacentHTML('beforeend', html);
                });
            })
            .catch(function (error) {
                document.getElementById('existingJobGroups').innerHTML = `
                    <div class="alert alert-danger">
                        Xidmətlər yüklənərkən xəta baş verdi
                    </div>
                `;

                console.error(error);
            });
    });

    document.addEventListener('click', function (e) {
        const tooth = e.target.closest('#jobServicesModal .teeth');

        if (!tooth) {
            return;
        }

        const code = getToothCode(tooth);

        if (!code || usedJobTeeth.has(code)) {
            return;
        }

        const multiple = e.ctrlKey || e.metaKey;

        if (!multiple) {
            pendingJobTeeth.forEach(function (item) {
                paintJobServiceTooth(item, 'job-pending-tooth', false);
            });

            pendingJobTeeth.clear();
        }

        if (pendingJobTeeth.has(code)) {
            pendingJobTeeth.delete(code);
            paintJobServiceTooth(code, 'job-pending-tooth', false);
        } else {
            pendingJobTeeth.add(code);
            paintJobServiceTooth(code, 'job-pending-tooth', true);
        }
    });

    const addJobServiceGroup = document.getElementById('addJobServiceGroup');

    if (addJobServiceGroup) {
        addJobServiceGroup.addEventListener('click', function () {
            if (!pendingJobTeeth.size) {
                return;
            }

            const teeth = Array.from(pendingJobTeeth);
            const index = serviceGroupIndex++;

            let locationInputs = '';

            teeth.forEach(function (code) {
                if (!locationMap[code]) {
                    return;
                }

                locationInputs += `<input type="hidden" name="items[${index}][location_ids][]" value="${locationMap[code]}">`;
            });

            const html = `
                <div class="job-service-group border rounded p-2 mb-2" data-group="${index}" data-teeth="${teeth.join(',')}">
                    ${locationInputs}

                    <div class="row align-items-end g-2">
                        <div class="col-md-2">
                            <label>Dişlər</label>
                            <input type="text" class="form-control" value="${teeth.join(', ')}" readonly>
                        </div>

                        <div class="col-md-4">
                            <label>Xidmət</label>
                            <select class="form-select job-service-select" name="items[${index}][service_id]" required>
                                ${document.getElementById('jobServiceTemplate').innerHTML}
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Qiymət</label>
                            <input type="number" class="form-control job-service-price" name="items[${index}][price]" step="0.01" min="0" required>
                        </div>

                        <div class="col-md-1">
                            <label>Say</label>
                            <input type="text" class="form-control" value="${teeth.length}" readonly>
                        </div>

                        <div class="col-md-2">
                            <label>Toplam</label>
                            <input type="text" class="form-control job-service-total" value="0.00" readonly>
                        </div>

                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger removeJobServiceGroup">×</button>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('newJobGroups').insertAdjacentHTML('beforeend', html);

            teeth.forEach(function (code) {
                usedJobTeeth.add(code);
                pendingJobTeeth.delete(code);

                paintJobServiceTooth(code, 'job-pending-tooth', false);
                paintJobServiceTooth(code, 'job-used-tooth', true);
            });
        });
    }

    document.addEventListener('change', function (e) {
        if (!e.target.matches('#jobServicesModal .job-service-select')) {
            return;
        }

        const group = e.target.closest('.job-service-group');
        const option = e.target.options[e.target.selectedIndex];
        const price = parseFloat(option.dataset.price || 0);

        group.querySelector('.job-service-price').value = price.toFixed(2);

        updateJobServiceGroupTotal(group);
    });

    document.addEventListener('input', function (e) {
        if (!e.target.matches('#jobServicesModal .job-service-price')) {
            return;
        }

        updateJobServiceGroupTotal(e.target.closest('.job-service-group'));
    });

    document.addEventListener('click', function (e) {
        const button = e.target.closest('#jobServicesModal .removeJobServiceGroup');

        if (!button) {
            return;
        }

        const group = button.closest('.job-service-group');
        const teeth = group.dataset.teeth.split(',');

        teeth.forEach(function (code) {
            usedJobTeeth.delete(code);
            paintJobServiceTooth(code, 'job-used-tooth', false);
        });

        group.remove();

        calculateNewJobServiceTotal();
    });

    const jobServicesModal = document.getElementById('jobServicesModal');

    if (jobServicesModal) {
        jobServicesModal.addEventListener('hidden.bs.modal', function () {
            clearJobServiceMap();

            usedJobTeeth.clear();
            pendingJobTeeth.clear();

            serviceGroupIndex = 0;

            document.getElementById('existingJobGroups').innerHTML = '';
            document.getElementById('newJobGroups').innerHTML = '';
            document.getElementById('newJobServiceTotal').textContent = '0.00';
        });
    }

    /*
|--------------------------------------------------------------------------
| Job Edit
|--------------------------------------------------------------------------
*/

    let editPendingTeeth = new Set();
    let editAssignedTeeth = new Set();
    let editGroupIndex = 0;

    function paintEditJobTooth(code, className, add = true) {
        document.querySelectorAll('#editJobModal .tooth-' + code).forEach(function (element) {
            if (add) {
                element.classList.add(className);
            } else {
                element.classList.remove(className);
            }
        });
    }

    function clearEditPendingTeeth() {
        editPendingTeeth.forEach(function (code) {
            paintEditJobTooth(code, 'job-pending-tooth', false);
        });

        editPendingTeeth.clear();
    }

    function clearEditJobMap() {
        document.querySelectorAll('#editJobModal .job-pending-tooth, #editJobModal .job-assigned-tooth').forEach(function (element) {
            element.classList.remove('job-pending-tooth', 'job-assigned-tooth');
        });
    }

    function calculateEditJobTotal() {
        let total = 0;

        document.querySelectorAll('#editJobGroups .edit-job-group-total').forEach(function (input) {
            total += parseFloat(input.value || 0);
        });

        document.getElementById('editJobTotal').textContent = total.toFixed(2);
    }

    function updateEditGroupTotal(group) {
        const teeth = group.dataset.teeth.split(',');
        const price = parseFloat(group.querySelector('.edit-job-group-price').value || 0);
        const total = price * teeth.length;

        group.querySelector('.edit-job-group-total').value = total.toFixed(2);

        calculateEditJobTotal();
    }

    function addEditGroup(teeth, serviceId = '', price = 0) {
        const index = editGroupIndex++;

        let locationInputs = '';

        teeth.forEach(function (code) {
            if (!locationMap[code]) {
                return;
            }

            locationInputs += `<input type="hidden" name="items[${index}][location_ids][]" value="${locationMap[code]}">`;
        });

        const html = `
        <div class="edit-job-group border rounded p-2 mb-2" data-group="${index}" data-teeth="${teeth.join(',')}">
            ${locationInputs}

            <div class="row align-items-end g-2">

                <div class="col-md-2">
                    <label>Dişlər</label>
                    <input type="text" class="form-control" value="${teeth.join(', ')}" readonly>
                </div>

                <div class="col-md-4">
                    <label>Xidmət</label>
                    <select class="form-select edit-job-group-service" name="items[${index}][service_id]" required>
                        ${document.getElementById('jobServiceTemplate').innerHTML}
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Qiymət</label>
                    <input type="number" class="form-control edit-job-group-price" name="items[${index}][price]" value="${parseFloat(price || 0).toFixed(2)}" step="0.01" min="0" required>
                </div>

                <div class="col-md-1">
                    <label>Say</label>
                    <input type="text" class="form-control" value="${teeth.length}" readonly>
                </div>

                <div class="col-md-2">
                    <label>Toplam</label>
                    <input type="text" class="form-control edit-job-group-total" value="${(parseFloat(price || 0) * teeth.length).toFixed(2)}" readonly>
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger removeEditJobGroup">×</button>
                </div>

            </div>
        </div>
    `;

        document.getElementById('editJobGroups').insertAdjacentHTML('beforeend', html);

        const group = document.querySelector(`#editJobGroups .edit-job-group[data-group="${index}"]`);

        if (serviceId) {
            group.querySelector('.edit-job-group-service').value = String(serviceId);
        }

        teeth.forEach(function (code) {
            code = String(code);

            editAssignedTeeth.add(code);
            paintEditJobTooth(code, 'job-assigned-tooth', true);
        });

        calculateEditJobTotal();
    }

    document.addEventListener('click', function (e) {
        const button = e.target.closest('.openJobEdit');

        if (!button) {
            return;
        }

        const editUrl = button.dataset.editUrl;
        const updateUrl = button.dataset.updateUrl;

        editPendingTeeth.clear();
        editAssignedTeeth.clear();
        editGroupIndex = 0;

        clearEditJobMap();

        document.getElementById('editJobGroups').innerHTML = '';
        document.getElementById('editJobTotal').textContent = '0.00';

        const form = document.getElementById('editJobForm');

        form.action = updateUrl;

        fetch(editUrl)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('İş məlumatları yüklənmədi');
                }

                return response.json();
            })
            .then(function (data) {
                document.getElementById('editPatientName').value = data.patient_name ?? '';
                document.getElementById('editReceivedType').value = data.received_type ?? '';
                document.getElementById('editReceivedAt').value = data.received_at ?? '';
                document.getElementById('editDueAt').value = data.due_at ?? '';
                document.getElementById('editJobNote').value = data.note ?? '';

                data.items.forEach(function (item) {
                    const teeth = item.locations
                        .map(function (location) {
                            return String(location.code);
                        })
                        .filter(Boolean);

                    addEditGroup(
                        teeth,
                        item.service_id,
                        item.price
                    );
                });

                calculateEditJobTotal();
            })
            .catch(function (error) {
                console.error(error);
            });
    });

    document.addEventListener('click', function (e) {
        const tooth = e.target.closest('#editJobModal .teeth');

        if (!tooth) {
            return;
        }

        const code = getToothCode(tooth);

        if (!code || editAssignedTeeth.has(code)) {
            return;
        }

        const multiple = e.ctrlKey || e.metaKey;

        if (!multiple) {
            clearEditPendingTeeth();
        }

        if (editPendingTeeth.has(code)) {
            editPendingTeeth.delete(code);
            paintEditJobTooth(code, 'job-pending-tooth', false);
        } else {
            editPendingTeeth.add(code);
            paintEditJobTooth(code, 'job-pending-tooth', true);
        }
    });

    const addEditJobGroupButton = document.getElementById('addEditJobGroup');

    if (addEditJobGroupButton) {
        addEditJobGroupButton.addEventListener('click', function () {
            if (!editPendingTeeth.size) {
                return;
            }

            const teeth = Array.from(editPendingTeeth);

            addEditGroup(teeth);

            teeth.forEach(function (code) {
                editPendingTeeth.delete(code);

                paintEditJobTooth(code, 'job-pending-tooth', false);
                paintEditJobTooth(code, 'job-assigned-tooth', true);
            });
        });
    }

    document.addEventListener('change', function (e) {
        if (!e.target.matches('#editJobModal .edit-job-group-service')) {
            return;
        }

        const group = e.target.closest('.edit-job-group');
        const option = e.target.options[e.target.selectedIndex];
        const price = parseFloat(option.dataset.price || 0);

        group.querySelector('.edit-job-group-price').value = price.toFixed(2);

        updateEditGroupTotal(group);
    });

    document.addEventListener('input', function (e) {
        if (!e.target.matches('#editJobModal .edit-job-group-price')) {
            return;
        }

        updateEditGroupTotal(
            e.target.closest('.edit-job-group')
        );
    });

    document.addEventListener('click', function (e) {
        const button = e.target.closest('#editJobModal .removeEditJobGroup');

        if (!button) {
            return;
        }

        const group = button.closest('.edit-job-group');
        const teeth = group.dataset.teeth.split(',');

        teeth.forEach(function (code) {
            editAssignedTeeth.delete(code);

            paintEditJobTooth(
                code,
                'job-assigned-tooth',
                false
            );
        });

        group.remove();

        calculateEditJobTotal();
    });

    const editJobModal = document.getElementById('editJobModal');

    if (editJobModal) {
        editJobModal.addEventListener('hidden.bs.modal', function () {
            clearEditJobMap();

            editPendingTeeth.clear();
            editAssignedTeeth.clear();

            editGroupIndex = 0;

            document.getElementById('editJobGroups').innerHTML = '';
            document.getElementById('editJobTotal').textContent = '0.00';

            document.getElementById('editJobForm').reset();
        });
    }

});
