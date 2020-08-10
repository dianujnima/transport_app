@if ($paginator->hasPages())
    <nav>
        <ul class="page_navigation">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <!-- <span class="page-link" aria-hidden="true" class="flaticon-left-arrow"></span> -->
                    <a class="page-link" href="javascript:void(0)"  rel="prev" aria-label="@lang('pagination.previous')">
                    <span  class="flaticon-left-arrow"  aria-hidden="true"></span>
                    </a>

                
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" class="flaticon-left-arrow" rel="prev" aria-label="@lang('pagination.previous')"><span class="flaticon-left-arrow"></span></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="flaticon-right-arrow"><span class="flaticon-right-arrow"></span></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <!-- <span class="page-link" aria-hidden="true" class="flaticon-right-arrow"></span> -->
                   

                    <a class="page-link" href="javascript:void(0)"  rel="prev" aria-label="@lang('pagination.previous')">
                    <span class="flaticon-right-arrow"></span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif
