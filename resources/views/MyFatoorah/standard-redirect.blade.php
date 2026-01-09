
<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
    You will be redirected to the MaFatwra website in a few seconds.

    <form action="{{ route('myfatoorah.process') }}" id="myfatoorah_checkout" method="POST">
        <input value="Click here if you are not redirected within 10 seconds..." type="submit">
        @csrf

        <input type="hidden" name="contract_id" value="{{ $contract_id }}">

    </form>

    <script type="text/javascript">
        document.getElementById("myfatoorah_checkout").submit();
    </script>
</body>
