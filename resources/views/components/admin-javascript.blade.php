    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        var Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
        $(window).ready(function(){
        checklaststatepushmenu();
        });
        
        function togglepushmenu() {
        $state = !$('body').hasClass('sidebar-collapse');
        if ($state == true) {
            localStorage.setItem("pushmenu", false);
        } else {
            localStorage.setItem("pushmenu", true);
        }
        }
        function checklaststatepushmenu() {
        $state = localStorage.getItem("pushmenu");
        if ($state == null) {
            $state = true;
            localStorage.setItem("pushmenu", true);
        }
        if ($state == "false") {
            $('body').addClass('sidebar-collapse');
        }
        }
    </script>

@if (Session::has('alert'))
    <?php $alert = Session::get('alert'); ?>
    @if ($alert["type"] == 'success')
        <script>toastr.success(`{{ $alert['text'] }}`)</script>
    @elseif($alert["type"] == 'error')
        <script>toastr.error(`{{ $alert['text'] }}`)</script>
    @elseif($alert["type"] == 'info')
        <script>toastr.info(`{{ $alert['text'] }}`)</script>
    @endif
@endif

@if ($errors->any())
    <script>
    @foreach ($errors->all() as $error)
        console.log('{{ $error }}');
        toastr.error('{{ $error }}')
    @endforeach
    </script>
@endif