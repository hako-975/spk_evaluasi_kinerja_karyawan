<script src="js/jquery-3.6.0.min.js"></script> 
<script src="js/overlayscrollbars.browser.es6.min.js"></script> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
<script src="js/adminlte.js"></script>

<!-- datatables -->
<script src="assets/vendor/datatables/js/dataTables.js"></script>
<script src="assets/vendor/datatables/js/dataTables.bootstrap5.js"></script>

<!-- select2 -->
<script src="assets/vendor/select2/js/select2.min.js"></script>

<!-- chartjs -->
<script src="assets/vendor/chartjs/chart.js"></script>

<script>
    $(document).ready(function() {
        $('#table_id').DataTable({
            "order": []
        });
    });

    $(function () {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih',
            closeOnSelect: true
        });
        
        // Handle focus event for Select2 search field
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
    });
</script>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview-img');
            var outputCircle = document.getElementById('preview-img-circle');
            output.src = reader.result;
            outputCircle.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<script>
    const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
    const Default = {
        scrollbarTheme: "os-theme-light",
        scrollbarAutoHide: "leave",
        scrollbarClickScroll: true,
    };
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (
            sidebarWrapper &&
            typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
        ) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script>

<script>
    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();

        const href = $(this).attr('href');
        const nama = $(this).data('nama');

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Ingin menghapus data " + nama + '!',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#3085d6',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Hapus Data!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                document.location.href = href;
            }
        });
    });

</script>