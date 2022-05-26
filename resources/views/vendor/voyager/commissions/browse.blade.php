@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->display_name_plural)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }}
        </h1>
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
                       
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('voyager::generic.Organiser') }}</th>
                                        <th>{{ __('voyager::generic.total') }} {{ __('voyager::generic.Bookings') }}</th>
                                        <th>{{ __('voyager::generic.Admin Commission') }}</th>
                                        <th>{{ __('voyager::generic.Admin Tax') }}</th>
                                        <th>{{ __('voyager::generic.Organiser Earning') }}</th>
                                        <th class="actions text-right">{{ __('voyager::generic.actions') }}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($commissions))
                                        @foreach($commissions as $data)
                                        <tr>
                                            <td>{{$data->organiser_name}}</td>
                                            <td>{{$data->customer_paid_total}} {{ setting('regional.currency_default') }}</td>
                                            <td>{{$data->admin_commission_total}} {{ setting('regional.currency_default') }}</td>
                                            <td>{{$data->admin_tax_total}} {{ setting('regional.currency_default') }}</td>
                                            <td>{{$data->organiser_earning_total}} {{ setting('regional.currency_default') }}</td>
                                            
                                            <td class="no-sort no-click" id="bread-actions">
                                                
                                                <a href="{{ route('voyager.'.$dataType->slug.'.show', [$data->org_id]) }}" class="btn btn-sm btn-warning pull-right view">
                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.view') }}</span>
                                                </a>
                                            </td>
                                        </tr>
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



