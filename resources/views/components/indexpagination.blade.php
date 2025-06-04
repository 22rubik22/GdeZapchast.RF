<link rel="stylesheet" href="{{ asset('css/pagination.css') }}">

<div class="pagination">
    @if ($adverts->onFirstPage())
        <!-- Не отображаем кнопку "предыдущая страница" на первой странице -->
    @else
        <a href="{{ $adverts->previousPageUrl() . '&status=' . request('status') . '&search=' . request('search') . '&brand=' . request('brand') }}">&laquo;</a>
    @endif

    @php
        $currentPage = $adverts->currentPage();
        $lastPage = $adverts->lastPage();
        $start = max(1, $currentPage - 3);
        $end = min($lastPage, $currentPage + 3);
    @endphp

    @if ($start > 1)
        <a href="{{ $adverts->url(1) . '&status=' . request('status') . '&search=' . request('search') . '&brand=' . request('brand') }}">1</a>
        @if ($start > 2)
            <span>...</span>
        @endif
    @endif

    @for ($i = $start; $i <= $end; $i++)
        @if ($i == $currentPage)
            <span class="active">{{ $i }}</span>
        @else
            <a href="{{ $adverts->url($i) . '&status=' . request('status') . '&search=' . request('search') . '&brand=' . request('brand') }}">{{ $i }}</a>
        @endif
    @endfor

    @if ($end < $lastPage)
        @if ($end < $lastPage - 1)
            <span>...</span>
        @endif
        <a href="{{  $adverts->url($lastPage) . '&status=' . request('status') . '&search=' . request('search') . '&brand=' . request('brand') }}">{{ $lastPage }}</a>
    @endif

    @if ($adverts->hasMorePages())
        <a href="{{ $adverts->nextPageUrl() . '&status=' . request('status') . '&search=' . request('search') . '&brand=' . request('brand') }}">&raquo;</a>
    @else
        <!-- Не отображаем кнопку "следующая страница" на последней странице -->
    @endif
</div>

