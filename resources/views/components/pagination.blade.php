@if ($paginator->hasPages())
    <div class="mt-6 mb-2 flex justify-between items-center">
        {{-- Info text --}}
        <p class="text-indigo-600 text-sm font-semibold mb-2">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </p>

        <nav class="flex justify-center" role="navigation" aria-label="Pagination">
            <ul class="inline-flex items-center -space-x-px">

                {{-- First --}}
                <li>
                    @if ($paginator->onFirstPage())
                        <span
                            class="px-3 py-1 text-gray-400 bg-white border border-gray-300 rounded-l cursor-not-allowed">First</span>
                    @else
                        <a href="{{ $paginator->url(1) }}"
                            class="px-3 py-1 text-gray-500 bg-white border border-gray-300 rounded-l hover:bg-gray-100 hover:text-gray-700">First</a>
                    @endif
                </li>

                {{-- Prev --}}
                <li>
                    @if ($paginator->onFirstPage())
                        <span
                            class="px-3 py-1 text-gray-400 bg-white border border-gray-300 cursor-not-allowed">Prev</span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                            class="px-3 py-1 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">Prev</a>
                    @endif
                </li>

                {{-- Page numbers --}}
                @php
                    $start = max($paginator->currentPage() - 2, 1);
                    $end = min($paginator->currentPage() + 2, $paginator->lastPage());
                @endphp

                {{-- Leading ellipsis --}}
                @if ($start > 1)
                    <li>
                        <a href="{{ $paginator->url(1) }}"
                            class="px-3 py-1 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">1</a>
                    </li>
                    @if ($start > 2)
                        <li><span
                                class="px-3 py-1 text-gray-500 bg-white border border-gray-300 cursor-default">…</span>
                        </li>
                    @endif
                @endif

                {{-- Middle pages --}}
                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $paginator->currentPage())
                        <li><span
                                class="px-3 py-1 text-white bg-indigo-600 border border-gray-300">{{ $i }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->url($i) }}"
                                class="px-3 py-1 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                {{-- Trailing ellipsis --}}
                @if ($end < $paginator->lastPage())
                    @if ($end < $paginator->lastPage() - 1)
                        <li><span
                                class="px-3 py-1 text-gray-500 bg-white border border-gray-300 cursor-default">…</span>
                        </li>
                    @endif
                    <li>
                        <a href="{{ $paginator->url($paginator->lastPage()) }}"
                            class="px-3 py-1 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">{{ $paginator->lastPage() }}</a>
                    </li>
                @endif

                {{-- Next --}}
                <li>
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                            class="px-3 py-1 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">Next</a>
                    @else
                        <span
                            class="px-3 py-1 text-gray-400 bg-white border border-gray-300 cursor-not-allowed">Next</span>
                    @endif
                </li>

                {{-- Last --}}
                <li>
                    @if ($paginator->currentPage() == $paginator->lastPage())
                        <span
                            class="px-3 py-1 text-gray-400 bg-white border border-gray-300 rounded-r cursor-not-allowed">Last</span>
                    @else
                        <a href="{{ $paginator->url($paginator->lastPage()) }}"
                            class="px-3 py-1 text-gray-500 bg-white border border-gray-300 rounded-r hover:bg-gray-100 hover:text-gray-700">Last</a>
                    @endif
                </li>

            </ul>
        </nav>
    </div>
@endif
