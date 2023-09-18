<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        @php
            $lastStrUrl = explode('/', url()->current());
            $lastStrUrl = end($lastStrUrl);
        @endphp
        <a class="nav-link{{
            (
                (strpos(url()->current(), '/admin/report') !== false)
                && $lastStrUrl == 'report'
             ) ? ' active' : '' }}"
           href="{{ route('admin.report.index') }}"
           role="tab"
           aria-selected="true">
            Tổng quan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ (strpos(url()->current(), '/admin/report/tmp-co') !== false)  ? ' active' : '' }}"
           href="{{ route('admin.report.tmp-co', ['type' => 'date']) }}"
           role="tab"
           aria-selected="true">
            Chào giá
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ (strpos(url()->current(), '/admin/report/co') !== false)  ? ' active' : '' }}"
           href="{{ route('admin.report.co', ['type' => 'date']) }}"
           role="tab"
           aria-selected="true">
            CO
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ (strpos(url()->current(), '/admin/report/request') !== false)  ? ' active' : '' }}"
           href="{{ route('admin.report.request', ['type' => 'date']) }}"
           role="tab"
           aria-selected="true">
            Phiếu yêu cầu
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ (strpos(url()->current(), '/admin/report/customer-tmp-co') !== false)  ? ' active' : '' }}"
           href="{{ route('admin.report.customer-tmp-co', ['type' => 'date']) }}"
           role="tab"
           aria-selected="true">
            KH - Chào giá
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ (strpos(url()->current(), '/admin/report/customer-co') !== false)  ? ' active' : '' }}"
           href="{{ route('admin.report.customer-co', ['type' => 'date']) }}"
           role="tab"
           aria-selected="true">
            KH - CO
        </a>
    </li>
</ul>
