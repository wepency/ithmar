<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <title>Invoice</title>
    <link rel=stylesheet type=text/css href= style.css />
</head>

<body>
<div id="page-wrap">
    <textarea id="header">INVOICE</textarea>
    <div id="identity">
        <label id="address">
            SAUDI ARABIA
        </label>

        <!--div id="logo">
            <img src="https://urway.sa/wp-content/uploads/2019/06/Logo-300x150.png" alt="logo" />
        </div-->

    </div>

    <div style="clear:both"></div>

    <div id="customer">

        <label id="customer-title">Sample Receipt
        </label>

        <table id="meta">
            <tr>
                <td class="meta-head">Payment id #</td>
                <td><textarea>'.$_GET['PaymentId'].'</textarea></td>
            </tr>
            <tr>
                <td class="meta-head">Result</td>
                <td>';
                    if($_GET['Result'] == 'Successful'||$_GET['Result'] == 'Success')
                    {
                    echo '<div class="due" style="color:white ; background-color: green;">'.$_GET['Result'].'</div>';
                    }
                    else
                    echo'  <div class="due" style="color:white ; background-color: red;">'.$_GET['Result'].'</div>';
                    echo'
                </td>
            </tr>
            <tr>
                <td class="meta-head">Response Code</td>
                <td>
                    <div class="due">'.$_GET['ResponseCode'].'</div>
                </td>
            </tr>
            <tr>
                <td class="meta-head">Auth Code</td>
                <td>
                    <div class="due">'.$_GET['AuthCode'].'</div>
                </td>
            </tr>
            <tr>
                <td class="meta-head">Date</td>
                <td><textarea id="date">'.date('d-m-Y H:i:s a').'</textarea></td>
            </tr>
            <tr>
                <td class="meta-head">CardBrand</td>
                <td><textarea id="date">'.$_GET['cardBrand'].' </textarea></td>
            </tr>


        </table>

    </div>

    <table id="items">

        <tr>
            <th>Item</th>
            <th>Description</th>
            <!--th>Unit Cost</th>
            <th>Quantity</th-->
            <th>Price</th>
        </tr>

        <tr class="item-row">
            <td class="item-name">
                <div><label><?php echo Sample Item ?></label></div>
            </td>
            <td class="description"><label></label></td>

            <td colspan="3"><span class="price">'.$_GET['amount'].'</span></td>
        </tr>
        <tr>
            <td colspan="2" class="blank"> </td>
            <td colspan="1" class="total-line">Amount Paid</td>

            <td class="total-value"><textarea id="paid">'.$_GET['amount'].'</textarea></td>
        </tr>
    </table>

    <div id="terms">
        <!--a href="/urshop/" class="">Back to Store</a-->
    </div>

</div>

</body>

</html>
