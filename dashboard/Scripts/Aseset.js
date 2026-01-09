
    $(AssLst).each(function (m, n) {
        var StartUp_date = n.StartUp_date.split("T");
        $('#SetAsset').append('<tr class="row_' + n.AssetId + '"><td>' + n.Name + '</td><td>' + n.code + '</td><td >' + n.AssetPlace + '</td><td >' + StartUp_date[0] + '<td ><a onclick="Edit(' + n.AssetId + ')"  class="btn btn-primary glyphicon glyphicon-edit" style= "padding:6px 12px"></a> <a  onclick="deleteBranch(' + n.AssetId + ')" class="btn btn-danger glyphicon glyphicon-trash"></a></td > </tr>')
    })

var AssetObj = {};
function SaveAsset() {
    AssetObj.AssetId =            $("#AssetId").val();
    AssetObj.AccountId =          $("#AssetAcount").data('kendoComboBox').value();
    AssetObj.Name =               $("#AssetName").val();
    AssetObj.AssetPurchaseValue = $("#AssetValue").val();
    AssetObj.StartUp_date =       $("#AssetDate").val();
    AssetObj.ScrapValue =         $("#ScrapValue").val();
    AssetObj.code =               $("#Assetcode").val();
    AssetObj.AssetPlace =         $("#Assetplace").val();
    AssetObj.SerialNum =          $("#Assetnum").val();
    AssetObj.DepreciationYear =   $("#depreciation").val();
    
    if (AssetObj.AccountId !== '' && AssetObj.Name !== '' && AssetObj.AssetPurchaseValue && AssetObj.ScrapValue !== '' && AssetObj.code !== '' && AssetObj.AssetPlace !== '' && AssetObj.SerialNum !== '' && AssetObj.DepreciationYear !== '') {
        $.ajax({
            type: "Post",
            url: $("#SaveAsset").val(),
            data: JSON.stringify({ Data: AssetObj }),
            contentType: "application/json;charset=utf-8",
            dataType: 'JSON',
            success: function (resultt) {

                if (resultt.AssetId > 0)
                {
                    alert("تم الحفظ بنجاح");
                    $("#Asset").modal("hide");


                } 
                else {
                    var StartUp_date = ToJavaScriptDate(resultt.StartUp_date);
                    var Data = "<tr class='row_" + resultt.AssetId + "'>" +
                          "<td >" + resultt.Name+ "</td>" +
                          "<td >" + resultt.code + "</td>" +
                          "<td >" + resultt.AssetPlace + "</td>" +
                          "<td >" + StartUp_date + "</td>" +
                          "<td ><a onclick='Edit(" + resultt.AssetId + ")'  class='btn btn-primary glyphicon glyphicon-edit'style='padding:6px 12px'></a> <a  onclick='deleteBranch(" + resultt.AssetId + ")' class='btn btn-danger glyphicon glyphicon-trash'></a>" + "</td > " +
                    "</tr>";
                    $("#SetAsset").append(Data);
                    alert("تم الحفظ بنجاح");
                    $("#Asset").modal("hide");
                }

            }

        })
    }
    else {
        alert("يجب ملئ الحقول");
    }

}
function Edit(AssetId) {
    $("#Asset").modal("show");
    $("#AssetId").val(AssetId);
    alert(AssetId)
    $(AssLst).each(function (m, n) {
        if (n.AssetId == AssetId) {
            
            $("#AssetAcount").data('kendoComboBox').value(n.AccountId);
            $("#AssetName").val(n.Name);
            $("#AssetValue").val(n.AssetPurchaseValue);
            $("#AssetDate").val(n.StartUp_date);
            $("#ScrapValue").val(n.ScrapValue);
            $("#Assetcode").val(n.code);
            $("#Assetplace").val(n.AssetPlace);
            $("#Assetnum").val(n.SerialNum);
            $("#depreciation").val(n.DepreciationYear);
        }
    })
    //$.ajax({
    //    method: "GET",
    //    url: $("#EditAsset").val(),
    //    data: { AssetId: AssetId },
      
       
    //    success: function (data) {
    //        var obj = JSON.parse(data);
           
    //        $("#AssetAcount").data('kendoComboBox').value(obj.AccountId);
    //        $("#AssetName").val(obj.Name);
    //        $("#AssetValue").val(obj.AssetPurchaseValue);
    //        $("#AssetDate").val(obj.StartUp_date);
    //        $("#ScrapValue").val(obj.ScrapValue);
    //        $("#Assetcode").val(obj.code);
    //        $("#Assetplace").val(obj.AssetPlace);
    //        $("#Assetnum").val(obj.SerialNum);
    //        $("#depreciation").val(obj.DepreciationYear);
    //    }
    //})
}