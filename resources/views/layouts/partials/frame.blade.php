<div class="modal fade" id="pdfViewerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 90%; height: 95vh;">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalTitle">Preview Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="(function(){$('#pdfViewerModal').modal('hide');})()"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <div id="pdfLoading" class="text-center p-5">
                    <i class="fas fa-spinner fa-spin fa-3x"></i><br>Memverifikasi Akses...
                </div>
                <iframe id="pdfIframe" src="" frameborder="0" style="width:100%; height:100%; display:none;"></iframe>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script>

    function sendPost(url, data = {}) {
        // 1. Buat elemen form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        // 2. Tambahkan Token CSRF (Ambil dari meta tag Laravel)
        const csrfToken = '{{ csrf_token() }}';
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        // 3. Tambahkan data tambahan jika ada (misal ID atau Method Spoofing)
        for (const key in data) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = data[key];
            form.appendChild(input);
        }

        // 4. Masukkan ke dokumen dan submit
        document.body.appendChild(form);
        form.submit();
    }
    function openSecureFrame(type, category, id, footer={}) {
        // Footer = {acceptButton, rejectButton}
        //
        // Tampilkan Loading/Spinner di modal

        const defAcction = function (event) {
            event.preventDefault();
            target = event.target; // Tombol yang diklik
            $('#pdfViewerModal').modal('hide');
        };

        if (!footer.acceptButton){
            footer.acceptButton = {
                label: 'Terima',
                action: defAcction
            };
        }
        if (!footer.rejectButton){
            footer.rejectButton = {
                label: 'Tolak',
                action: defAcction
            };
        }

        $('#pdfViewerModal').modal('show');
        $('#pdfIframe').hide();
        $('#pdfLoading').show();

        // Ambil signed URL dari backend
        fetch(`/api/frame-access/${type}/${category}/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            if(data.access_url) {
                // Pasang URL bertanda tangan ke iframe
                const iframe = document.getElementById('pdfIframe');
                iframe.src = data.access_url;
                iframe.onload = () => {
                    var footerAccept = data.footer && data.footer.accept ? data.footer.accept : footer.acceptButton;
                    var footerReject = data.footer && data.footer.reject ? data.footer.reject : footer.rejectButton
                    $('#pdfLoading').hide();
                    $('.modal-footer').html(`
                        <button type="button" class="btn ${ footerReject.class ? footerReject.class : 'btn-danger' }" id="rejectBtn">${footerReject.label}</button>
                        <button type="button" class="btn ${ footerAccept.class ? footerAccept.class : 'btn-success'}" id="acceptBtn">${footerAccept.label}</button>
                    `);
                    if (footerAccept.route) {
                        $('#acceptBtn').on('click', function(e) {
                            sendPost(footerAccept.route);
                        });
                    } else {
                        $('#acceptBtn').on('click', footerAccept.action);
                    }
                    if (footerReject.route) {
                        $('#rejectBtn').on('click', function(e) {
                            sendPost(footerReject.route);
                        });
                    } else {
                        $('#rejectBtn').on('click', footerReject.action);
                    }
                    $(iframe).show();

                    
                };
            }
        })
        .catch(error => alert("Akses ditolak atau sesi berakhir."));
    }
</script>