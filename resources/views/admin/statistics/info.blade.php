@php
    $html_tag_data = [];
    $title = __('menu_statistics');
    $breadcrumbs = ["/admin"=>"White Dent", ""=>__('menu_statistics')];
@endphp
@extends('admin.layout',['html_tag_data'=>$html_tag_data, 'title'=>$title])
@section('css')
    <link rel="stylesheet" href="{{asset('backend/css/vendor/daterangepicker.css')}}"/>
@endsection
@section('js_page')
    <script src="{{asset('backend/js/vendor/datepicker/moment.min.js')}}"></script>
    <script src="{{asset('backend/js/vendor/datepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('backend/js/vendor/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('backend/js/cs/charts.extend.js')}}"></script>
    <script>
        $(document).ready(function() {
            initDatePicker();
        })
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.getElementById('lineChart');
            if (!el) return;

            const labels = @json($lineLabels);
            const counts = @json($lineCounts);
            const income = @json($lineIncome);

            new Chart(el.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '',
                        data: counts,
                        incomes: income,
                        borderColor: '#1ea8e7',
                        pointBackgroundColor: '#1ea8e7',
                        pointBorderColor: '#1ea8e7',
                        pointHoverBackgroundColor: '#1ea8e7',
                        pointHoverBorderColor: '#1ea8e7',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBorderWidth: 3,
                        pointHoverRadius: 4,
                        fill: false,
                    }]
                },
                options: {
                    plugins: {
                        crosshair: ChartsExtend.Crosshair(),
                        datalabels: { display: false },
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                lineWidth: 1,
                                color: Globals.separatorLight,
                                drawBorder: false
                            },
                            ticks: {
                                beginAtZero: true,
                                padding: 20,
                                fontColor: Globals.alternate
                            },
                        }],
                        xAxes: [{
                            gridLines: { display: false },
                            ticks: { fontColor: Globals.alternate }
                        }],
                    },
                    legend: { display: false },
                    tooltips: {
                        ...ChartsExtend.ChartTooltipForCrosshair(),
                        backgroundColor: '#ffffff',
                        titleFontColor: '#1ea8e7',
                        bodyFontColor: '#4e4e4e',
                        borderColor: '#e6e6e6',
                        borderWidth: 1,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            title: function(tooltipItems, data) {
                                return data.labels[tooltipItems[0].index];
                            },
                            label: function(tooltipItem, data) {
                                const count = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                const income = data.datasets[tooltipItem.datasetIndex].incomes[tooltipItem.index];
                                return ['{{ __("count") }}: ' + count,
                                    '{{ __("revenue") }}: ' + income + ' ₼'];
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.getElementById('cashFlowChart');
            if (!el) return;

            const labels = @json($dailyLabels);
            const incomeData = @json($dailyIncome);
            const expenseData = @json($dailyExpense);

            new Chart(el.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '{{ __("income") }}',
                            data: incomeData,
                            backgroundColor: 'rgba(67, 155, 56, 0.15)',
                            borderColor: '#439b38',
                            borderWidth: 2,
                            borderRadius: 12,
                            borderSkipped: false,
                            maxBarThickness: 26,
                        },
                        {
                            label: '{{ __("expense") }}',
                            data: expenseData,
                            backgroundColor: 'rgba(207, 38, 55, 0.15)',
                            borderColor: '#cf2637',
                            borderWidth: 2,
                            borderRadius: 12,
                            borderSkipped: false,
                            maxBarThickness: 26,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        labels: {
                            fontColor: '#4e4e4e'
                        }
                    },
                    tooltips: {
                        backgroundColor: '#ffffff',
                        titleFontColor: '#1ea8e7',
                        bodyFontColor: '#4e4e4e',
                        borderColor: '#e6e6e6',
                        borderWidth: 1,
                        cornerRadius: 10,
                        displayColors: true,
                        callbacks: {
                            label: function(tooltipItem, data) {
                                const label = data.datasets[tooltipItem.datasetIndex].label || '';
                                return label + ': ' + Number(tooltipItem.yLabel).toFixed(2) + ' ₼';
                            }
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontColor: '#7c7c7c',
                                callback: function(value) {
                                    return value + ' ₼';
                                }
                            },
                            gridLines: {
                                color: '#f1f1f1',
                                drawBorder: false
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontColor: '#7c7c7c'
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{$title}}</h1>
                    @include('admin._layout.breadcrumb',['breadcrumbs'=>$breadcrumbs])
                </div>
                <!-- Title End -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">

                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-end w-100 w-sm-auto ms-4" data-bs-toggle="offcanvas" data-bs-target="#filterCanvas" aria-controls="filterCanvas">
                        <i data-acorn-icon="filter"></i>
                        <span>{{ __('filter') }}</span>
                    </button>

                </div>
            </div>
        </div>
        <div class="row g-4 mb-5">
            <div class="col-12 col-xl-8">
                <section class="scroll-section" id="lineChartTitle">
                    <h2 class="small-title">{{ __('services_chart') }}</h2>
                    <div class="card">
                        <div class="card-body">
                            <div class="sh-35">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <section class="scroll-section" id="financeStats">
                    <h2 class="small-title">{{ __('finance') }}</h2>

                    <div class="row g-2">
                        <div class="col-12 mb-2">
                            <div class="card hover-border-primary">
                                <div class="h-100 row g-0 card-body align-items-center p-3">
                                    <div class="col-auto">
                                        <div class="bg-gradient-light sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center">
                                            <i data-acorn-icon="wallet" class="text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="heading mb-0 sh-8 d-flex align-items-center lh-1-25 ps-3">{{ __('monthly_total_income') }}</div>
                                    </div>
                                    <div class="col-auto ps-3">
                                        <div class="display-6 text-success">{{ number_format($incomeSummary->total_all, 2) }} ₼</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-2">
                            <div class="card hover-border-primary">
                                <div class="h-100 row g-0 card-body align-items-center p-3">
                                    <div class="col-auto">
                                        <div class="bg-gradient-light sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center">
                                            <i data-acorn-icon="minus" class="text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="heading mb-0 sh-8 d-flex align-items-center lh-1-25 ps-3">{{ __('monthly_total_expense') }}</div>
                                    </div>
                                    <div class="col-auto ps-3">
                                        <div class="display-6 text-danger">{{ number_format($expenseSummary->total_all, 2) }} ₼</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-2">
                            <div class="card bg-gradient-light">
                                <div class="h-100 row g-0 card-body align-items-center p-3">
                                    <div class="col-auto">
                                        <div class="border border-foreground sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center">
                                            <i data-acorn-icon="trend-up" class="text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="heading mb-0 sh-8 d-flex align-items-center lh-1-25 ps-3 text-white">{{ __('net_profit_label') }}</div>
                                    </div>
                                    <div class="col-auto ps-3">
                                        <div class="display-6 text-white">{{ number_format($profitSummary, 2) }} ₼</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-12 col-xl-4">
                <section class="scroll-section" id="activePatientsBox">
                    <h2 class="small-title">{{ __('receivables_payables') }}</h2>
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-0 align-items-center mb-3 sh-6">
                                <div class="col-auto">
                                    <div class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                                        <i data-acorn-icon="arrow-bottom" class="text-white"></i>
                                    </div>
                                </div>
                                <div class="col ps-3">
                                    <div class="row g-0">
                                        <div class="col">
                                            <div class="sh-3 d-flex align-items-center lh-1-25">{{ __('my_receivable') }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="cta-3 text-success sh-3 d-flex align-items-center">{{ number_format($allPatientsReceivableTotal, 2) }} ₼</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-0 align-items-center mb-3 sh-6">
                                <div class="col-auto">
                                    <div class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                                        <i data-acorn-icon="arrow-top" class="text-white"></i>
                                    </div>
                                </div>
                                <div class="col ps-3">
                                    <div class="row g-0">
                                        <div class="col">
                                            <div class="sh-3 d-flex align-items-center lh-1-25">{{ __('partner_payable') }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="cta-3 text-danger sh-3 d-flex align-items-center">{{ number_format($allPartnersDebtTotal, 2) }} ₼</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row g-0">
                                <div class="col pe-4 d-flex flex-column justify-content-between align-items-end">
                                    <div class="text-small text-muted sh-3 d-flex align-items-center">{{ __('receivable') }}</div>
                                    <div class="text-small text-muted sh-3 d-flex align-items-center">{{ __('payable_short') }}</div>
                                    <div class="text-small text-muted sh-5 d-flex align-items-end">{{ __('difference') }}</div>
                                </div>
                                <div class="col-auto d-flex flex-column justify-content-between align-items-end">
                                    <div class="text-muted sh-3 d-flex align-items-center">{{ number_format($allPatientsReceivableTotal, 2) }} ₼</div>
                                    <div class="text-muted sh-3 d-flex align-items-center">- {{ number_format($allPartnersDebtTotal, 2) }} ₼</div>
                                    <div class="cta-3 text-primary sh-5 d-flex align-items-end">
                                        {{ number_format($allPatientsReceivableTotal - $allPartnersDebtTotal, 2) }} ₼
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-8">
                <section class="scroll-section" id="cashChartTitle">
                    <h2 class="small-title"{{ __('daily_income_expense') }}</h2>
                    <div class="card">
                        <div class="card-body">
                            <div class="sh-35">
                                <canvas id="cashFlowChart"></canvas>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>






        <div class="offcanvas offcanvas-bottom" style="height: 60vh;" tabindex="-1" id="filterCanvas" aria-labelledby="offcanvasBottomLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasBottomLabel">{{ __('filter') }}</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body ">
                <form method="GET" action="{{route('admin.statistics.info')}}">
                    <label for="finance-date-filter">{{ __('start_end_dates') }}</label>
                    <input type="text" name="dates" class="form-control dateLimit" id="finance-date-filter" placeholder="{{ __('select') }}">
                    <button type="submit" class="btn btn-primary mt-2">{{ __('filter') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
