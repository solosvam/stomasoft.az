@php
    $html_tag_data = [];
    $title = __('stock');
    $breadcrumbs = ["/admin"=>"White Dent", ""=>__('stock')];
@endphp
@extends('admin.layout',['html_tag_data'=>$html_tag_data, 'title'=>$title])
@section('css')
    <link rel="stylesheet" href="{{asset('backend/css/vendor/select2.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('backend/css/vendor/select2-bootstrap4.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('backend/css/vendor/datatables.min.css')}}"/>
    <style>
        .qty-box{
            display:flex;
            align-items:center;
            gap:6px;
            width:130px;
        }

        .qty-btn{
            width:34px;
            height:34px;
            border:1px solid #20a8e8;
            background:#fff;
            color:#20a8e8;
            border-radius:10px;
            font-size:20px;
            line-height:1;
        }

        .qty-input{
            width:55px;
            height:34px;
            border:1px solid #ddd;
            border-radius:10px;
            text-align:center;
        }
    </style>
@endsection
@section('js_page')
    <script src="{{asset('backend/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('backend/js/cs/datatable.extend.js')}}"></script>
    <script src="{{asset('backend/js/plugins/datatable.boxedvariations.js')}}"></script>
    <script>
        $(document).on('click', '.qty-plus, .qty-minus', function () {
            let box = $(this).closest('.qty-box');
            let input = box.find('.qty-input');
            let id = box.data('id');
            let qty = parseInt(input.val()) || 0;

            if ($(this).hasClass('qty-plus')) {
                qty++;
            } else {
                qty = Math.max(0, qty - 1);
            }

            input.val(qty);
            updateStockQty(id, qty, input);
        });

        $(document).on('change', '.qty-input', function () {
            let box = $(this).closest('.qty-box');
            let id = box.data('id');
            let qty = parseInt($(this).val()) || 0;

            if (qty < 0) qty = 0;

            $(this).val(qty);
            updateStockQty(id, qty, $(this));
        });

        function updateStockQty(id, qty, input) {
            let price = parseFloat(input.data('price'));

            let total = (price * qty).toFixed(2);
            $('#total-' + id).text(total + ' AZN');

            $.ajax({
                url: "{{ route('admin.stock.updateQty') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    qty: qty
                },
                success: function (res) {
                    if (res.success) {
                        $('#total-' + id).text(res.total + ' AZN');
                    }
                },
                error: function () {
                    alert('Xəta oldu brat');
                }
            });
        }
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

                <!-- Top Buttons Start -->
                <div class="col-12 col-sm-6 d-flex align-items-start justify-content-end">
                    <!-- Tour Button Start -->
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-end w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#newAdmin">
                        <span>{{ __('new_stock') }}</span>
                        <i data-acorn-icon="plus"></i>
                    </button>
                    <!-- Tour Button End -->
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <div class="row">
            <div class="col">

                <section class="scroll-section" id="hover">
                    <div class="card mb-5">
                        <div class="card-body">
                            <!-- Hover Controls Start -->
                            <div class="row">
                                <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-1">
                                    <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                                        <input class="form-control form-control-sm datatable-search" placeholder="{{ __('search') }}" data-datatable="#datatableHover" />
                                        <span class="search-magnifier-icon">
                                          <i data-acorn-icon="search"></i>
                                        </span>
                                        <span class="search-delete-icon d-none">
                                          <i data-acorn-icon="close"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-7 col-lg-9 col-xxl-10 text-end mb-1">
                                    <div class="d-inline-block">
                                        <div class="d-inline-block datatable-export" data-datatable="#datatableHover">
                                            <button
                                                class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                                <i data-acorn-icon="download"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <button class="dropdown-item export-excel" type="button">Excel</button>
                                                <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                            </div>
                                        </div>
                                        <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#datatableHover">
                                            <button
                                                class="btn btn-outline-muted btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                                10 {{ __('results') }}
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <a class="dropdown-item" href="#">5 {{ __('results') }}</a>
                                                <a class="dropdown-item active" href="#">10 {{ __('results') }}</a>
                                                <a class="dropdown-item" href="#">20 {{ __('results') }}</a>
                                                <a class="dropdown-item" href="#">50 {{ __('results') }}</a>
                                                <a class="dropdown-item" href="#">100 {{ __('results') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Hover Controls End -->

                            <!-- Hover Table Start -->
                            <table class="data-table data-table-pagination data-table-standard responsive nowrap hover" id="datatableHover" data-order='[[ 0, "desc" ]]'>
                                <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase">#</th>
                                    <th>{{ __('partner') }}</th>
                                    <th>{{ __('product') }}</th>
                                    <th>{{ __('quantity') }}</th>
                                    <th>{{ __('price') }}</th>
                                    <th>{{ __('total') }}</th>
                                    <th>{{ __('operation') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stocks as $stock)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$stock->partner->name}}</td>
                                        <td class="text-alternate">{{$stock->product}}</td>
                                        <td class="text-alternate">
                                            <div class="qty-box" data-id="{{ $stock->id }}">
                                                <button type="button" class="qty-btn qty-minus">−</button>

                                                <input type="number"
                                                       class="qty-input"
                                                       value="{{ $stock->qty }}"
                                                       min="0"
                                                       data-price="{{ $stock->price }}">

                                                <button type="button" class="qty-btn qty-plus">+</button>
                                            </div>
                                        </td>
                                        <td class="text-alternate">{{$stock->price}} AZN</td>
                                        <td class="text-alternate total-price" id="total-{{ $stock->id }}">
                                            {{ $stock->price * $stock->qty }} AZN
                                        </td>
                                        <td class="text-alternate">
                                            <a href="{{route('admin.stock.edit',$stock->id)}}" class="btn btn-primary btn-sm">{{ __('edit') }}</a>
                                            <a href="{{route('admin.stock.delete',$stock->id)}}" class="btn btn-danger btn-sm">{{ __('delete') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="modal modal-right fade" id="newAdmin" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('new_stock') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('admin.stock.add')}}">
                            @csrf
                            <div class="mb-2">
                                <label>{{ __('select_partner') }}</label>
                                <select class="form-select select2" name="partner_id" required>
                                    <option value="">{{ __('select_partner') }}</option>
                                    @foreach($partners as $partner)
                                        <option value="{{$partner->id}}">{{$partner->name}}
                                            | {{ $partner->type == 'supplier' ? 'Təchizatçı' : 'Texnik' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>{{ __('product') }}</label>
                                <input type="text" name="product" class="form-control" placeholder="{{ __('product') }}" value="{{old('product')}}" required>
                            </div>
                            <div class="mb-2">
                                <label>{{ __('quantity') }}</label>
                                <input type="text" name="qty" class="form-control" placeholder="{{ __('quantity') }}" value="{{old('qty')}}" required>
                            </div>

                            <div class="mb-2">
                                <label>{{ __('price') }}</label>
                                <input type="text" name="price" class="form-control" placeholder="{{ __('price') }}" value="{{old('price')}}" required>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary">{{ __('add') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
