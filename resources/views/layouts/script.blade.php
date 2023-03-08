<script>
    @if (Session::has('messages'))
        @foreach (Session::get('messages') as $key => $message)
            @if ($key == 'danger')
                toastr.error("{{ $message }}");
            @elseif ($key == 'success')
                toastr.success("{{ $message }}");
                setTimeout(() => {
                    console.log("hello");
                    {{ Session::forget('success') }}
                }, 2000);
            @elseif ($key == 'info')
                toastr.info("{{ $message }}");
            @elseif ($key == 'warning')
                toastr.warning("{{ $message }}");
            @endif
        @endforeach
    @endif
    @if (isset($errors))
        @foreach ($errors->all() as $message)
            toastr.error("{{ $message }}");
        @endforeach
    @endif

    $(document).ready(() => {
        $('.select2').select2({});
        $('.select2').select2({
            tags: true
        });
    });
</script>
