<table class="table table-bordered table-striped mt-0">
    <thead>
    <tr>
        <th>اسم المستثمر</th>
        <th>رقم الوحدة</th>
        <th>الشاطئ</th>
        <th>عدد العقود</th>
        <th>إجمالي قيمة العقود</th>
        <th>نسبة إثمار من العقود</th>
        <th>نسبة القطاع من العقود</th>
    </tr>
    </thead>

    <tbody>
    @foreach($contracts as $contract)
        <tr>
            <td>{{$contract['investor']}}</td>
            <td>{{$contract['unit']}}</td>
            <td>{{$contract['beach']}}</td>
            <td>{{$contract['count']}}</td>
            <td>{{currency_format($contract['total_contracts'])}}</td>
            <td>{{currency_format($contract['ithmar_total'])}}</td>
            <td>{{currency_format($contract['sector_total'])}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
