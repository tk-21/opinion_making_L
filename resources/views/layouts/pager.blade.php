@if($topics->hasPages())
    <div class="paging">
        <p class="paging-txt">全件数：{{ $topics->total() }}件</p>
        <p class="paging-txt">{{ $topics->firstItem() }}~{{ $topics->lastItem() }}を表示中</p>
        <ul class="paging-list">

            {{--                                前へ戻るボタン--}}
            <li class="paging-item">
                @if ($topics->onFirstPage())
                    <span class="paging-pre">&laquo;</span>
                @else
                    <a href="{{ $topics->previousPageUrl() }}">&laquo;</a>
                @endif
            </li>

            {{--                                ページ番号--}}
            @foreach($topics->getUrlRange($topics->currentPage()-3, $topics->currentPage()+3) as $page=>$link)
                @if($page >= 1 && $page <= $topics->lastPage())
                    <li class="paging-item">
                        @if($page === $topics->currentPage())
                            <span class="paging-now">{{ $page }}</span>
                        @else
                            <a href="{{ $link }}" class="paging-num">{{ $page }}</a>
                        @endif
                    </li>
                @endif
            @endforeach

            {{--                                次へ進むボタン--}}
            <li class="paging-item">
                @if($topics->hasMorePages())
                    <a href="{{ $topics->nextPageUrl() }}">&raquo;</a>
                @else
                    <span class="paging-next">&raquo;</span>
                @endif
            </li>

        </ul>
    </div>
@endif
