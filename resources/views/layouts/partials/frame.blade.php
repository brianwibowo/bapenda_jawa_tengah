<div class="modal fade" id="ViewerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 90%; height: 95vh; margin: auto;">
        <div class="modal-content" style="height: 100%; display: flex; flex-direction: column;">
            <div class="modal-header" style="flex-shrink: 0;">
                <h5 class="modal-title" id="ModalTitle">Preview Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="(function(){$('#ViewerModal').modal('hide');})()"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0" style="flex: 1 1 auto; overflow-y: auto; min-height: 0;">
                <div id="Loading" class="text-center p-5">
                    <i class="fas fa-spinner fa-spin fa-3x"></i><br>Memverifikasi Akses...
                </div>
                <iframe id="iframe" src="" frameborder="0" style="width:100%; height:100%; display:none;"></iframe>
            </div>
            <div class="modal-footer" style="flex-shrink: 0;">
            </div>
        </div>
    </div>
</div>

<script>

    // ─────────────────────────────────────────────────────────────────────────
    // State cache: menyimpan hasil fetch terakhir agar modal tidak re-fetch
    // saat dibuka kembali dengan parameter yang sama.
    // State di-reset hanya ketika: (a) parameter berbeda, (b) ada submit baru.
    // ─────────────────────────────────────────────────────────────────────────
    var _frameState = {
        key:        null,   // "<type>:<category>:<id>"
        mode:       null,   // 'modal' | 'iframe'
        data:       null,   // response dari /api/frame-access
        pdfUrl:     null,   // diisi setelah submit berhasil → switch ke iframe
        rendered:   false,  // apakah sudah pernah dirender
    };

    function _frameKey(type, category, id) {
        return `${type}:${category}:${id}`;
    }

    // Reset state paksa (dipanggil dari luar jika perlu)
    function resetFrameState() {
        _frameState = { key: null, mode: null, data: null, pdfUrl: null, rendered: false };
    }

    function sendPost(url, data = {}) {
        const form      = document.createElement('form');
        form.method     = 'POST';
        form.action     = url;
        const csrfInput = document.createElement('input');
        csrfInput.type  = 'hidden';
        csrfInput.name  = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        for (const key in data) {
            const input   = document.createElement('input');
            input.type    = 'hidden';
            input.name    = key;
            input.value   = data[key];
            form.appendChild(input);
        }
        document.body.appendChild(form);
        form.submit();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Render iframe dari URL yang sudah diketahui (mode pdf/iframe)
    // ─────────────────────────────────────────────────────────────────────────
    function _renderIframe(iframeSrc, footerConfig, footerCallbacks) {
        const defAction = function(e) { e.preventDefault(); $('#ViewerModal').modal('hide'); };

        let iframe = document.getElementById('iframe');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'iframe';
            iframe.frameBorder = '0';
            iframe.style.width = '100%';
            iframe.style.height = '100%';
        }
        const modalBody = document.querySelector('#ViewerModal .modal-body');

        // Sembunyikan form jika ada (tidak perlu di-clear/dihapus dari DOM agar bisa Kembali Edit)
        const injectedForm = document.getElementById('frameForm');
        if (injectedForm) {
            injectedForm.style.display = 'none';
        } else {
            modalBody.innerHTML = '';
        }

        modalBody.style.overflowY = 'hidden';
        modalBody.style.maxHeight = '';
        modalBody.style.padding   = '0';
        
        // Pastikan loading element ada
        let loading = document.getElementById('Loading');
        if (!loading) {
            loading = document.createElement('div');
            loading.id = 'Loading';
            loading.className = 'text-center p-5';
            loading.innerHTML = '<i class="fas fa-spinner fa-spin fa-3x"></i><br>Memuat PDF...';
            modalBody.appendChild(loading);
        } else {
            loading.innerHTML = '<i class="fas fa-spinner fa-spin fa-3x"></i><br>Memuat PDF...';
        }

        // Pastikan iframe ada di dalam modalBody
        if (!modalBody.contains(iframe)) {
            modalBody.appendChild(iframe);
        }

        $('#iframe').hide();
        $('#Loading').show();

        iframe.onload = function() {
            if (!iframe.src || iframe.src === 'about:blank' || iframe.src === window.location.href) {
                return;
            }
            var footerAccept = false, footerReject = false, footerBack = false;
            const fc = footerConfig || {};
            const fb = footerCallbacks || {};

            if (fc.accept) footerAccept = { ...(fb.acceptButton || {}), ...fc.accept };
            if (fc.reject) footerReject = { ...(fb.rejectButton || {}), ...fc.reject };
            if (fc.back)   footerBack   = { ...(fb.backButton   || {}), ...fc.back   };

            var html = '';
            if (footerBack)   html += `<button type="button" class="btn ${footerBack.class   || 'btn-secondary'}" id="backBtn">${footerBack.label}</button>`;
            if (footerReject) html += `<button type="button" class="btn ${footerReject.class || 'btn-danger'}"    id="rejectBtn">${footerReject.label}</button>`;
            if (footerAccept) html += `<button type="button" class="btn ${footerAccept.class || 'btn-success'}"  id="acceptBtn">${footerAccept.label}</button>`;

            // Jika tidak ada footer config dari server, tampilkan tombol Tutup default
            if (!html) html = `<button type="button" class="btn btn-secondary" onclick="$('#ViewerModal').modal('hide')">Tutup</button>`;

            $('.modal-footer').html(html);

            const normalizeAction = function(action) {
                if (typeof action === 'function') return action;
                if (typeof action === 'string' && ['exit','close'].includes(action.toLowerCase())) return defAction;
                return defAction;
            };

            if (footerAccept && footerAccept.route) { $('#acceptBtn').on('click', function() { sendPost(footerAccept.route); }); }
            else if (footerAccept) { $('#acceptBtn').on('click', normalizeAction(footerAccept.action)); }
            if (footerReject && footerReject.route) { $('#rejectBtn').on('click', function() { sendPost(footerReject.route); }); }
            else if (footerReject) { $('#rejectBtn').on('click', normalizeAction(footerReject.action)); }
            if (footerBack && footerBack.route) { $('#backBtn').on('click', function() { sendPost(footerBack.route); }); }
            else if (footerBack) { $('#backBtn').on('click', normalizeAction(footerBack.action)); }

            $('#Loading').hide();
            $(iframe).show();
        };

        // Add cache-buster to prevent browser caching of PDF previews
        const cacheBuster = 't=' + new Date().getTime();
        const finalUrl = iframeSrc.includes('?') ? (iframeSrc + '&' + cacheBuster) : (iframeSrc + '?' + cacheBuster);
        iframe.src = finalUrl;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Render modal (inject HTML form) — hanya dipanggil sekali per state
    // ─────────────────────────────────────────────────────────────────────────
    function _renderModal(accessUrl, submitUrl, footerConfig, footerCallbacks) {
        fetch(accessUrl)
        .then(r => r.text())
        .then(html => {
            const parser        = new DOMParser();
            const doc           = parser.parseFromString(html, 'text/html');
            const bodyContent   = doc.querySelector('[data-frame-body]');
            const scriptContent = doc.querySelectorAll('[data-form-script]');
            const iframe        = document.getElementById('iframe');

            if (!bodyContent) {
                $('#Loading').hide();
                document.querySelector('#ViewerModal .modal-body').innerHTML =
                    '<div class="p-4 text-danger">Konten form tidak ditemukan.</div>';
                return;
            }

            const modalBody = document.querySelector('#ViewerModal .modal-body');
            modalBody.innerHTML   = '';
            modalBody.style.overflowY = 'auto';
            modalBody.style.maxHeight = '';
            modalBody.style.padding   = '1.5rem';

            const form = doc.getElementById('frameForm');
            if (form) {
                form.action = submitUrl; // Set form action to signed submit URL
                modalBody.appendChild(document.adoptNode(form));
                const footerInForm = modalBody.querySelector('[data-frame-footer]');
                if (footerInForm) footerInForm.remove();
            } else {
                modalBody.appendChild(document.adoptNode(bodyContent));
            }

            // Re-run injected scripts
            modalBody.querySelectorAll('script').forEach(old => {
                const s = document.createElement('script');
                if (old.src) s.src = old.src; else s.textContent = old.textContent;
                document.body.appendChild(s);
                old.remove();
            });

            // Re-run any additional scripts marked with data-frame-script
            scriptContent.forEach(old => {
                const s = document.createElement('script');
                if (old.src) s.src = old.src; else s.textContent = old.textContent;
                document.body.appendChild(s);
                old.remove();
            }); 

            // Footer: Batal + Lihat Preview
            $('.modal-footer').html(`
                <button type="button" class="btn btn-secondary" id="frameFormCancelBtn">Batal</button>
                <button type="button" class="btn btn-primary" id="frameFormPreviewBtn">Lihat Preview</button>
            `);

            // Submit handler
            const injectedForm = document.getElementById('frameForm');
            
            function bindFormFooterListeners() {
                const cancelBtn = document.getElementById('frameFormCancelBtn');
                if (cancelBtn) {
                    cancelBtn.addEventListener('click', function () {
                        $('#ViewerModal').modal('hide');
                    });
                }

                const previewBtn = document.getElementById('frameFormPreviewBtn');
                if (previewBtn && injectedForm) {
                    previewBtn.addEventListener('click', function() {
                        if (!injectedForm.checkValidity()) {
                            injectedForm.reportValidity();
                            return;
                        }

                        previewBtn.disabled = true;
                        previewBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';

                        const formData = new FormData(injectedForm);
                        formData.append('preview', '1');

                        fetch(submitUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => {
                            if (!res.ok) return res.json().then(err => { throw err; });
                            return res.json();
                        })
                        .then(result => {
                            console.log('[Frame] Preview result:', result);
                            let pdfUrl = null;
                            if (result.data) {
                                if (result.data.pdf_url) {
                                    pdfUrl = result.data.pdf_url;
                                } else {
                                    const firstKey = Object.keys(result.data)[0];
                                    if (firstKey && result.data[firstKey]) {
                                        pdfUrl = result.data[firstKey].pdf_url || result.data[firstKey];
                                    }
                                }
                            }

                            if (pdfUrl) {
                                // Render iframe dengan pdf_url menggunakan _renderIframe agar konsisten dan dinamis
                                _renderIframe(pdfUrl, {
                                    'accept' : {
                                        'label' : 'Kirim & Simpan',
                                        'class' : 'btn-success',
                                        'action' : function() {
                                            // Trigger form submission
                                            injectedForm.dispatchEvent(new Event('submit', { cancelable: true }));
                                        }
                                    },
                                    'reject' : false,
                                    'back' : {
                                        'label' : 'Kembali Edit',
                                        'class' : 'btn-warning',
                                        'action' : function() {
                                            let iframe = document.getElementById('iframe');
                                            if (iframe) {
                                                iframe.onload = null;
                                                $(iframe).hide();
                                                iframe.src = '';
                                            }
                                            injectedForm.style.display = 'block';
                                            modalBody.style.padding = '1.5rem';
                                            modalBody.style.overflowY = 'auto';

                                            // Kembalikan footer form semula
                                            $('.modal-footer').html(`
                                                <button type="button" class="btn btn-secondary" id="frameFormCancelBtn">Batal</button>
                                                <button type="button" class="btn btn-primary" id="frameFormPreviewBtn">Lihat Preview</button>
                                            `);
                                            // Bind ulang listener
                                            bindFormFooterListeners();
                                        }
                                    }
                                }, footerCallbacks);
                            } else {
                                alert('Gagal memuat preview PDF.');
                            }
                            previewBtn.disabled = false;
                            previewBtn.innerHTML = 'Lihat Preview';
                        })
                        .catch(err => {
                            previewBtn.disabled = false;
                            previewBtn.innerHTML = 'Lihat Preview';
                            console.error('[Frame] Preview error:', err);
                            alert('Gagal memuat preview PDF: ' + (err.message || JSON.stringify(err)));
                        });
                    });
                }
            }

            bindFormFooterListeners();

            if (injectedForm) {
                injectedForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const submitBtn = document.getElementById('frameFormSubmitBtn');
                    if (submitBtn) { submitBtn.disabled = true; submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...'; }

                    fetch(submitUrl, {
                        method:  'POST',
                        body:    new FormData(injectedForm),
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    })
                    .then(res => {
                        if (!res.ok) return res.json().then(err => { throw err; });
                        return res.json();
                    })
                    .then(result => {
                        console.log('[Frame] Submit result:', result);

                        // ── Ambil pdf_url dari result.data atau kendaraan pertama ──
                        let pdfUrl = null;
                        if (result.data) {
                            if (result.data.pdf_url) {
                                pdfUrl = result.data.pdf_url;
                            } else {
                                const firstKey = Object.keys(result.data)[0];
                                if (firstKey && result.data[firstKey]) {
                                    pdfUrl = result.data[firstKey].pdf_url || null;
                                }
                            }
                        }

                        if (pdfUrl) {
                            // ── Transisi: modal → iframe PDF ──
                            _frameState.pdfUrl   = pdfUrl;
                            _frameState.mode     = 'iframe';
                            _frameState.rendered = true;  // preserve state baru (iframe)

                            // Perbarui title modal
                            document.getElementById('ModalTitle').textContent = 'Preview Surat Keputusan';

                            // Render iframe dengan pdf_url
                            _renderIframe(pdfUrl, {
                                'accept' : {
                                    'label' : 'Selesai',
                                    'class' : 'btn-success',
                                    'action' : function() {
                                        $('#ViewerModal').modal('hide');
                                        window.location.reload();
                                    }
                                },
                                'reject' : false,
                                'back' : false,
                            }, footerCallbacks);
                        } else {
                            // Tidak ada PDF → tutup modal, tampilkan pesan
                            $('#ViewerModal').modal('hide');
                            if (result.message) alert(result.message);
                            // Reset state agar bisa dibuka ulang
                            _frameState.rendered = false;
                            window.location.reload();
                        }
                    })
                    .catch(err => {
                        if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Kirim & Simpan'; }
                        console.error('[Frame] Submit error:', err);
                        alert('Gagal mengajukan: ' + (err.message || JSON.stringify(err)));
                    });
                });
            }

            $('#Loading').hide();
        })
        .catch(() => {
            $('#Loading').hide();
            alert('Gagal memuat formulir. Silakan coba lagi.');
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // openSecureFrame — entry point utama
    // Preserve: jika key sama dan sudah rendered, langsung tampilkan ulang
    // ─────────────────────────────────────────────────────────────────────────
    function openSecureFrame(type, category, id, footer={}) {
        const key = _frameKey(type, category, id);

        // ── Jika state sudah ada untuk key ini, cukup buka modal ulang ──
        if (_frameState.key === key && _frameState.rendered) {
            $('#ViewerModal').modal('show');

            if (_frameState.mode === 'iframe' && _frameState.pdfUrl) {
                // Langsung render ulang iframe (src sudah di-set sebelumnya)
                let iframe = document.getElementById('iframe');
                if (!iframe) {
                    iframe = document.createElement('iframe');
                    iframe.id = 'iframe';
                    iframe.frameBorder = '0';
                    iframe.style.width = '100%';
                    iframe.style.height = '100%';
                }
                const modalBody = document.querySelector('#ViewerModal .modal-body');
                // Pastikan iframe ada di dalam modal-body
                if (!modalBody.contains(iframe)) {
                    modalBody.innerHTML = '';
                    modalBody.style = '';
                    modalBody.appendChild(iframe);
                }
                $('#Loading').hide();
                $(iframe).show();
            }
            // Untuk mode modal yang masih rendered, kontennya sudah di DOM
            return;
        }

        // ── Parameter baru / belum pernah render: reset dan mulai dari awal ──
        _frameState = { key, mode: null, data: null, pdfUrl: null, rendered: false };

        const defAction = function(e) { e.preventDefault(); $('#ViewerModal').modal('hide'); };
        if (!footer.acceptButton) footer.acceptButton = { label: 'Terima',  action: defAction };
        if (!footer.rejectButton) footer.rejectButton = { label: 'Tolak',   action: defAction };
        if (!footer.backButton)   footer.backButton   = { label: 'Kembali', action: defAction };

        $('#ViewerModal').modal('show');

        // Reset modal body ke loading state
        const modalBody = document.querySelector('#ViewerModal .modal-body');
        let iframe = document.getElementById('iframe');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'iframe';
            iframe.frameBorder = '0';
            iframe.style.width = '100%';
            iframe.style.height = '100%';
        }
        modalBody.innerHTML   = '';
        modalBody.style.overflowY = '';
        modalBody.style.maxHeight = '';
        modalBody.style.padding   = '';
        const loadingEl = document.createElement('div');
        loadingEl.id        = 'Loading';
        loadingEl.className = 'text-center p-5';
        loadingEl.innerHTML = '<i class="fas fa-spinner fa-spin fa-3x"></i><br>Memverifikasi Akses...';
        modalBody.appendChild(loadingEl);
        iframe.onload       = null;
        iframe.src          = '';
        iframe.style.display = 'none';
        modalBody.appendChild(iframe);
        $('.modal-footer').html('');

        // Fetch access config dari backend
        fetch(`/api/frame-access/${type}/${category}/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.access_url) return;

            _frameState.mode = data.mode || 'iframe';
            _frameState.data = data;

            if (_frameState.mode === 'modal') {
                _frameState.rendered = true;
                _renderModal(data.access_url, data.submit_url, data.footer, footer);
            } else {
                _frameState.rendered = true;
                _renderIframe(data.access_url, data.footer, footer);
            }
        })
        .catch(() => alert('Akses ditolak atau sesi berakhir.'));
    }
</script>