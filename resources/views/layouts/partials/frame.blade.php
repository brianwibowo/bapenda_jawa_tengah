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
    function openSecurePdf(category, id, footer={}) {
        // Footer = {acceptButton, rejectButton}
        //
        // Tampilkan Loading/Spinner di modal
        $('#pdfViewerModal').modal('show');
        $('#pdfIframe').hide();
        $('#pdfLoading').show();

        // Ambil signed URL dari backend
        fetch(`/api/pdf-access/${category}/${id}`, {
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
                    $('#pdfLoading').hide();
                    $(iframe).show();
                    $('.modal-footer').html(`
                        ${footer.acceptButton ? `<button class="btn btn-success" onclick="${footer.acceptButton.action}">${footer.acceptButton.label}</button>` : ''}
                        ${footer.rejectButton ? `<button class="btn btn-danger" onclick="${footer.rejectButton.action}">${footer.rejectButton.label}</button>` : ''}
                    `);
                };
            }
        })
        .catch(error => alert("Akses ditolak atau sesi berakhir."));
    }
</script>