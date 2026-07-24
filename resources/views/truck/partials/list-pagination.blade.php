@if ($shipments->hasPages())
    {{ $shipments->onEachSide(1)->links('pagination::bootstrap-4') }}
@else
    <ul class="pagination" style="display:flex;list-style:none;padding:0;margin:0;gap:2px;align-items:center;font-size:10px;">
        <li class="disabled"><span>«</span></li>
        <li class="disabled"><span>‹</span></li>
        <li class="active"><span>1</span></li>
        <li class="disabled"><span>›</span></li>
        <li class="disabled"><span>»</span></li>
    </ul>
@endif
