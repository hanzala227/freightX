@forelse($mappings as $m)
<tr id="map-row-{{ $m->id }}" data-id="{{ $m->id }}"
    data-target="{{ $m->target }}"
    data-status="{{ $m->status }}"
    data-sender-id="{{ $m->sender_id }}"
    data-key="{{ $m->key }}"
    data-ic="{{ $m->init_target_code }}"
    data-tc="{{ $m->target_code }}"
    data-tp-id="{{ $m->trade_partner_id }}">
    <td class="sticky-col" style="width:28px;text-align:center;cursor:pointer;" onclick="event.stopPropagation()">
        <input type="checkbox" name="ids[]" value="{{ $m->id }}" class="row-check" onchange="updateToolbar()">
    </td>
    <td class="sticky-col" style="left:28px;min-width:160px;">
        <span style="color:#1e293b;font-weight:600;cursor:pointer;" onclick="event.stopPropagation();editMapping('{{ $m->id }}')" title="Click to edit">{{ $m->target ?? '--' }}</span>
    </td>
    <td>
        @if($m->status)
            <span class="badge-status bg-blue">{{ $m->status }}</span>
        @else -- @endif
    </td>
    <td>{{ $m->sender_id ?? '--' }}</td>
    <td>{{ $m->key ?? '--' }}</td>
    <td>{{ $m->init_target_code ?? '--' }}</td>
    <td>
        <a href="{{ $m->tradePartner ? route('trade-partner.edit', $m->tradePartner->id) : 'javascript:;' }}" class="col-link" style="color:#3598dc;font-weight:600;">
            {{ $m->tradePartner->name ?? '--' }}
        </a>
    </td>
    <td>{{ $m->target_code ?? '--' }}</td>
    <td style="color:#94a3b8;font-size:10px;">{{ $m->created_at ? $m->created_at->format('m-d-Y H:i') : '--' }}</td>
    <td style="text-align:center;white-space:nowrap;">
        <button class="btn-tool" style="height:18px;min-width:18px;padding:0 3px;font-size:9px;" onclick="event.stopPropagation();editMapping('{{ $m->id }}')" title="Edit">
            <i class="fa fa-pencil"></i>
        </button>
    </td>
</tr>
@empty
<tr id="empty-row">
    <td colspan="10" style="text-align:center;padding:30px 10px;color:#94a3b8;">
        <i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
        No mappings found.
    </td>
</tr>
@endforelse
