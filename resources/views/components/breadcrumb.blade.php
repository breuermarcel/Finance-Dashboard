<nav class="breadcrumb-nav" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('stocks.index') }}">{{ trans('Home') }}</a></li>
        <?php
            $segments = Request::segments();
            $segments_total = count($segments);
        ?>
        @for($i = 1; $i <= $segments_total; $i++)
            @if($i === $segments_total)
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ URL::to( implode( '/', array_slice($segments, 0 ,$i, true)))}}">{{ strtoupper(Request::segment($i)) }}</a>
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ URL::to( implode( '/', array_slice($segments, 0 ,$i, true)))}}">{{ strtoupper(Request::segment($i)) }}</a>
                </li>
            @endif
        @endfor
    </ol>
</nav>
