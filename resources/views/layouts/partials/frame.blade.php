<div class="modal fade" id="ViewerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 90%; height: 95vh;">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalTitle">Preview Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="(function(){$('#ViewerModal').modal('hide');})()"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <div id="Loading" class="text-center p-5">
                    <i class="fas fa-spinner fa-spin fa-3x"></i><br>Memverifikasi Akses...
                </div>
                <iframe id="iframe" src="" frameborder="0" style="width:100%; height:100%; display:none;"></iframe>
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

            $('#ViewerModal').modal('hide');
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
        if (!footer.backButton){
            footer.backButton = {
                label: 'Kembali',
                action: defAcction
            };
        }

        $('#ViewerModal').modal('show');
        $('#iframe').hide();
        $('#Loading').show();

        // Ambil signed URL dari backend
        fetch(`/api/frame-access/${type}/${category}/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            if(data.access_url) {
                // Pasang URL bertanda tangan ke iframe
                const iframe = document.getElementById('iframe');
                iframe.src = data.access_url;
                iframe.onload = () => {
                    var footerAccept = false;
                    var footerReject = false;
                    var footerBack = false;
                    if (data.footer && data.footer.accept) {
                        footerAccept = { ...footer.acceptButton, ...data.footer.accept };
                    }
                    if (data.footer && data.footer.reject) {
                        console.log('Footer Reject Config:', data.footer.reject);
                        footerReject = { ...footer.rejectButton, ...data.footer.reject };
                    }
                    if (data.footer && data.footer.back) {
                        footerBack = { ...footer.backButton, ...data.footer.back };
                    }
                    var modelHtml = '';
                    $('#Loading').hide();
                    if (footerBack) {
                        modelHtml += `<button type="button" class="btn ${ footerBack.class ? footerBack.class : 'btn-secondary' }" id="backBtn">${footerBack.label}</button>`;
                    }
                    if (footerReject) {
                        modelHtml += `<button type="button" class="btn ${ footerReject.class ? footerReject.class : 'btn-danger' }" id="rejectBtn">${footerReject.label}</button>`;
                    }
                    if (footerAccept) {
                        modelHtml += `<button type="button" class="btn ${ footerAccept.class ? footerAccept.class : 'btn-success' }" id="acceptBtn">${footerAccept.label}</button>`;
                    }
                    
                    $('.modal-footer').html(modelHtml);

                    const normalizeAction = function(action) {
                        if (typeof action === 'function') {
                            return action;
                        }

                        // Some server payloads send action as a string (e.g. 'exit').
                        // jQuery .on() requires a callable handler.
                        if (typeof action === 'string') {
                            if (action.toLowerCase() === 'exit' || action.toLowerCase() === 'close') {
                                return defAcction;
                            }
                        }

                        return defAcction;
                    };

                    if (footerAccept.route) {
                        $('#acceptBtn').on('click', function(e) {
                            sendPost(footerAccept.route);
                        });
                    } else {
                        $('#acceptBtn').on('click', normalizeAction(footerAccept.action));
                    }
                    if (footerReject.route) {
                        $('#rejectBtn').on('click', function(e) {
                            sendPost(footerReject.route);
                        });
                    } else {
                        $('#rejectBtn').on('click', normalizeAction(footerReject.action));
                    }
                    if (footerBack.route) {
                        $('#backBtn').on('click', function(e) {
                            sendPost(footerBack.route);
                        });
                    } else {
                        $('#backBtn').on('click', normalizeAction(footerBack.action));
                    }
                    $(iframe).show();

                    
                };
            }
        })
        .catch(error => alert("Akses ditolak atau sesi berakhir."));
    }
</script>