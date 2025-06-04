<link rel="stylesheet" href="{{ asset('css/pagination.css') }}"> <!-- Подключение стилей пагинации -->

<div class="pagination">
    @if ($paginator->onFirstPage())
        <!-- Не отображаем кнопку "предыдущая страница" на первой странице -->
    @else
        <a href="{{ $paginator->previousPageUrl() }}">&laquo;</a>
    @endif

    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $start = max(1, $currentPage - 3);
        $end = min($lastPage, $currentPage + 3);
    @endphp

    @if ($start > 1)
        <a href="{{ $paginator->appends(request()->input())->url(1) }}">1</a>
        @if ($start > 2)
            <span>...</span>
        @endif
    @endif

    @for ($i = $start; $i <= $end; $i++)
        @if ($i == $currentPage)
            <span class="active">{{ $i }}</span>
        @else
            <a href="{{ $paginator->appends(request()->input())->url($i) }}">{{ $i }}</a>
        @endif
    @endfor

    @if ($end < $lastPage)
        @if ($end < $lastPage - 1)
            <span>...</span>
        @endif
        <a href="{{ $paginator->appends(request()->input())->url($lastPage) }}">{{ $lastPage }}</a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}">&raquo;</a>
    @else
        <!-- Не отображаем кнопку "следующая страница" на последней странице -->
    @endif
</div>