@extends('HCCore::admin.layout.master')

@section('content')
    <div class="row">
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-users" aria-hidden="true"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ trans('HCCore::core.users') }}</span>
                    <span class="info-box-number">{{ \HoneyComb\Core\Models\HCUser::count() }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
