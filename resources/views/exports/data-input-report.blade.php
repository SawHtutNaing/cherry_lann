<table>
    {{-- Summary table --}}
    <tr>
        <th>No</th>
        <th>Charge</th>
        <th>Refund</th>
        <th>Net Total</th>
        <th>Pending</th>
        <th>Overall Total</th>
    </tr>
    <tr>
        <td>{{ $rows->count() }}</td>
        <td>{{ $charges }}</td>
        <td>{{ $refund }}</td>
        <td>{{ $charges - $refund }}</td>
        <td>{{ $pending_total }}</td>
        <td>{{ number_format($overall_total, 2) }}</td>
    </tr>

    <tr><td></td></tr>

    {{-- Detail table: one row per line item --}}
    <tr>
        <th>No</th>
        <th>Page Name</th>
        <th>Cus Name</th>
        <th>Serviced By</th>
        <th>Service Type</th>
        <th>Start Date</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Discount</th>
        <th>Line Total</th>
        {{-- <th>Record Total</th> --}}
        <th>Status</th>
        <th>Remark</th>
    </tr>
    @foreach ($rows as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row['page_name'] }}</td>
            <td>{{ $row['customer_name'] }}</td>
            <td>{{ $row['serviced_by'] }}</td>
            <td>{{ $row['service_type'] }}</td>
            <td>{{ $row['start_date'] }}</td>
            <td>{{ $row['quantity'] }}</td>
            <td>{{ $row['price'] }}</td>
            <td>{{ $row['discount'] }}</td>
            <td>{{ $row['line_total'] }}</td>
            {{-- <td>{{ $row['record_total'] }}</td> --}}
            <td>{{ $row['status'] }}</td>
            <td>{{ $row['remark'] }}</td>
        </tr>
    @endforeach

    {{-- Grand total row --}}
    <tr>
        <td colspan="9" style="text-align:right;"><strong>GRAND TOTAL</strong></td>
        <td><strong>{{ number_format($overall_total, 2) }}</strong></td>
        <td colspan="2"></td>
    </tr>
</table>
