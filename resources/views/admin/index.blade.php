@extends('admin.layouts.admin')

@section('title', __('rcon::admin.title'))

@section('content')
    <div class="page-rcon">
        <div>
            <p>{{ __('rcon::admin.description') }}</p>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row col">
                    <h4>{{ __('admin.nav.settings.servers') }}</h4>
                </div>
                <div class="row">
                    <div class="col">
                        <select name="server" id="server-id" class="form-control custom-select">
                            @foreach($servers as $server)
                                <option value="{{ $server['id']  }}">{{ $server['name']  }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <pre id="response" class="rounded px-1"></pre>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-8 col-md-10">
                        <input type="text" id="cmd-value" class="form-control" placeholder="status...">
                    </div>
                    <div class="col-4 col-md-2 text-right">
                        <button class="btn btn-primary w-100" id="run-command">
                            <i class="bi bi-send"></i> {{ __('rcon::admin.run') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        const RconPluginEndpoint = '{{ route('rcon.admin.execute') }}' + '/';
    </script>
    <script type="text/javascript" src="{{ plugin_asset('rcon', 'js/script.js')  }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ plugin_asset('rcon', 'css/style.css')  }}">
@endpush
