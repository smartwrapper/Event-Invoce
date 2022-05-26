@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->display_name_plural)

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mb-0">
                <h1 class="page-title">
                    <i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }}
                </h1>
            </div>
            <div class="col-md-6 mb-0">
                <a href="javascript:;" onclick='openBankModal()' title="{{ __('voyager::generic.organizer_bank') }}" class="btn btn-primary pull-right mt-2" data-id="bank-modal" id="bank-modal">
                    <i class="voyager-lighthouse"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.organizer_bank') }}</span>
                </a>
                {{-- Single Bank modal --}}
                <div class="modal modal-primary fade" tabindex="-1" id="bank_modal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><i class="voyager-lighthouse"></i> {{ __('voyager::generic.organizer_bank') }}</h4>
                            </div>
                            <div class="modal-body">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td>{{ __('voyager::generic.Organiser') }}</td>
                                            <th>{{ $organiser->name }} ({{ $organiser->email }})</th>
                                        </tr>
                                        <tr>
                                            <td>{{ __('voyager::generic.Organisation') }}</td>
                                            <th>{{ $organiser->organisation }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ __('voyager::generic.Bank Name') }}</td>
                                            <th>{{ $organiser->bank_name }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ __('voyager::generic.Bank Code') }}</td>
                                            <th>{{ $organiser->bank_code }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ __('voyager::generic.Bank Branch Name') }}</td>
                                            <th>{{ $organiser->bank_branch_name }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ __('voyager::generic.Bank Branch Code') }}</td>
                                            <th>{{ $organiser->bank_branch_code }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ __('voyager::generic.Bank Account Number') }}</td>
                                            <th>{{ $organiser->bank_account_number }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ __('voyager::generic.Bank Account Name') }}</td>
                                            <th>{{ $organiser->bank_account_name }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ __('voyager::generic.Bank Account Phone') }}</td>
                                            <th>{{ $organiser->bank_account_phone }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.close') }}</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div>
        </div>
        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <h3>{{ __('voyager::generic.payouts') }}</h3>
                        <h5>{{ __('voyager::generic.payouts_info') }}</h5>
                        <br>
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('voyager::generic.Event') }}</th>
                                        <th>{{ __('voyager::generic.Organiser') }}</th>
                                        <th>{{ __('voyager::generic.total') }} {{ __('voyager::generic.Bookings') }}</th>
                                        <th>{{ __('voyager::generic.Admin Commission') }}</th>
                                        <th>{{ __('voyager::generic.Admin Tax') }}</th>
                                        <th>{{ __('voyager::generic.Organiser Earning') }}</th>
                                        <th>{{ __('voyager::generic.Month Year') }}</th>
                                        <th>{{ __('voyager::generic.Transferred') }}</th>
                                        <th class="actions text-right">{{ __('voyager::generic.actions') }}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($commissions))
                                        @foreach($commissions as $key => $data)
                                            <form method="POST"  action="{{route('eventmie.commission_update')}}" class="form-search">
                                                {{ csrf_field() }}
                                                <tr>
                                                <input type="hidden" class="form-control" name="month_year"  value="{{$data->month_year}}">
                                                <input type="hidden" class="form-control" name="organiser_id" value="{{$data->org_id}}">
                                                <input type="hidden" class="form-control" name="event_id" value="{{$data->event_id}}">
                                                        
                                                    <td>{{ $data->event_name }}</td>
                                                    <td>{{ $data->organiser_name }}</td>
                                                    <td>{{ $data->customer_paid_total}} {{ setting('regional.currency_default') }}</td>
                                                    <td>{{ $data->admin_commission_total }} {{ setting('regional.currency_default') }}</td>
                                                    <td>{{ $data->admin_tax_total }} {{ setting('regional.currency_default') }}</td>
                                                    <td>{{ $data->organiser_earning_total }} {{ setting('regional.currency_default') }}</td>
                                                    <td>{{ $data->month_year}}</td>
                                                    <td>  
                                                        <div class="form-check">
                                                            <input type="checkbox" name="transferred"  class="form-check-input"  
                                                            {{ $data->transferred > 0 ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td class="no-sort no-click" id="bread-actions">

                                                        <button type="submit" class="btn btn-sm btn-primary     pull-right view">
                                                            <i class="voyager-edit"></i>
                                                             <span class="hidden-xs hidden-sm">
                                                                {{ __('voyager::generic.update') }}
                                                            </span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </form>

                                        @endforeach
                                    @endif    
                                </tbody>
                            </table>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

        {{-- refunds --}}
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <h3>{{ __('voyager::generic.refunds') }}</h3>
                        <h5>{{ __('voyager::generic.refunds_info') }}</h5>
                        <br>
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('voyager::generic.Event') }}</th>
                                        <th>{{ __('voyager::generic.Organiser') }}</th>
                                        <th>{{ __('voyager::generic.total') }} {{ __('voyager::generic.Bookings') }}</th>
                                        <th>{{ __('voyager::generic.Admin Commission') }}</th>
                                        <th>{{ __('voyager::generic.Admin Tax') }}</th>
                                        <th>{{ __('voyager::generic.Organiser Earning') }}</th>
                                        <th>{{ __('voyager::generic.Month Year') }}</th>
                                        <th>{{ __('voyager::generic.settlement') }}</th>
                                        <th class="actions text-right">{{ __('voyager::generic.actions') }}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($refunds))
                                        @foreach($refunds as $key => $data)
                                            <form method="POST"  action="{{route('eventmie.settlement_update')}}" class="form-search">
                                                {{ csrf_field() }}
                                                <tr>
                                                <input type="hidden" class="form-control" name="month_year"  value="{{$data->month_year}}">
                                                <input type="hidden" class="form-control" name="organiser_id" value="{{$data->org_id}}">
                                                <input type="hidden" class="form-control" name="event_id" value="{{$data->event_id}}">
                                                        
                                                    <td>{{ $data->event_name }}</td>
                                                    <td>{{ $data->organiser_name }}</td>
                                                    <td>{{ $data->customer_paid_total}} {{ setting('regional.currency_default') }}</td>
                                                    <td>{{ $data->admin_commission_total }} {{ setting('regional.currency_default') }}</td>
                                                    <td>{{ $data->admin_tax_total }} {{ setting('regional.currency_default') }}</td>
                                                    <td>-{{ $data->organiser_earning_total }} {{ setting('regional.currency_default') }}</td>
                                                    <td>{{ $data->month_year}}</td>
                                                    <td>  
                                                        <div class="form-check">
                                                            <input type="checkbox" name="settled"  class="form-check-input"  
                                                            {{ $data->settled > 0 ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td class="no-sort no-click" id="bread-actions">

                                                        <button type="submit" class="btn btn-sm btn-primary     pull-right view">
                                                            <i class="voyager-edit"></i>
                                                             <span class="hidden-xs hidden-sm">
                                                                {{ __('voyager::generic.update') }}
                                                            </span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </form>
                                        @endforeach
                                    @endif    
                                </tbody>
                            </table>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop

@section('css')
@if(config('dashboard.data_tables.responsive'))
    <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
@endif
@stop

@section('javascript')
<script>
function openBankModal() {
    $('#bank_modal').modal('show');
}
</script>
@stop