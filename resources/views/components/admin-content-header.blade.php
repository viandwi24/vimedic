@php
    $title = isset($title) ? $title : "Other Page";
@endphp
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    @isset($breadcrumb)
                        @foreach ($breadcrumb as $item)
                            @isset($item['link'])
                                <li class="breadcrumb-item">
                                    <a href="{{ $item['link'] }}">{{ $item['text'] }}</a>
                                </li>
                            @else
                            <li class="breadcrumb-item">{{ $item['text'] }}</li>
                            @endisset
                        @endforeach
                    @endisset
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>