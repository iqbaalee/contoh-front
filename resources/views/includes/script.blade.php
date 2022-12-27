<!-- Bootstrap 4 -->
<script src="{{asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('adminlte/dist/js/adminlte.js')}}"></script>
<script>
    function debounce(callback, wait, context = this) {

        let timeout = null;
        let callbackArgs = null;

        const later = () => callback.apply(context, callbackArgs);

        return function ({
            id
        }) {
            callbackArgs = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    const onInputDebounced = debounce(({
        id,
        value,
        arg1
    }) => {
        tabel.draw()
    }, 500);

</script>
