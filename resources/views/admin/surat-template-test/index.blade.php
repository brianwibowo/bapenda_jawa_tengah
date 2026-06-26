<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0">Template Surat — Preview</h2>
    </x-slot>

    <style>
        .surat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }
        .surat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important;
        }
        .surat-card .card-icon {
            font-size: 2.2rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
    </style>

    <div class="row g-4">
        {{-- CARD 1: SP Samsat → Polda --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-primary bg-opacity-10 text-primary flex-shrink-0">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SP Samsat → Polda</h5>
                        <p class="text-muted mb-0 small">Surat Pemberitahuan standar dari Samsat ke Polda</p>
                        <span class="btn btn-sm btn-outline-primary mt-2" data-type="sp_default" data-bs-toggle="modal" data-bs-target="#modalSpDefaultTest">
                            <i class="fas fa-eye me-1"></i>Lihat Template
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: SP Polda → Bapenda & JR --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-info bg-opacity-10 text-info flex-shrink-0">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SP Polda → Bapenda & JR</h5>
                        <p class="text-muted mb-0 small">Pemberitahuan penghapusan data kendaraan dari Polda</p>
                        <span class="btn btn-sm btn-outline-info mt-2" data-type="sp_polda2bapendajr" data-bs-toggle="modal" data-bs-target="#modalSpPolda2bapendajrTest">
                            <i class="fas fa-eye me-1"></i>Lihat Template
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 3: SP Balasan Bapenda --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-success bg-opacity-10 text-success flex-shrink-0">
                        <i class="fas fa-reply"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SP Balasan Bapenda</h5>
                        <p class="text-muted mb-0 small">Balasan surat pemberitahuan dari Bapenda</p>
                        <span class="btn btn-sm btn-outline-success mt-2" data-type="sp_balasan_bapenda" data-bs-toggle="modal" data-bs-target="#modalSpBalasanBapendaTest">
                            <i class="fas fa-eye me-1"></i>Lihat Template
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 4: SP Balasan Jasa Raharja --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-warning bg-opacity-10 text-warning flex-shrink-0">
                        <i class="fas fa-reply-all"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SP Balasan Jasa Raharja</h5>
                        <p class="text-muted mb-0 small">Balasan surat pemberitahuan dari Jasa Raharja</p>
                        <span class="btn btn-sm btn-outline-warning mt-2" data-type="sp_balasan_jr" data-bs-toggle="modal" data-bs-target="#modalSpBalasanJRTest">
                            <i class="fas fa-eye me-1"></i>Lihat Template
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 5: SK Samsat Default --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-secondary bg-opacity-10 text-secondary flex-shrink-0">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SK Samsat (Default)</h5>
                        <p class="text-muted mb-0 small">Surat Keputusan standar Samsat</p>
                        <span class="btn btn-sm btn-outline-secondary mt-2" data-type="sk_default" data-bs-toggle="modal" data-bs-target="#modalSkDefaultTest">
                            <i class="fas fa-eye me-1"></i>Lihat Template
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 6: SK Instansi (Polda / Bapenda / JR) --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-danger bg-opacity-10 text-danger flex-shrink-0">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SK Instansi</h5>
                        <p class="text-muted mb-0 small">SK Polda Regident / Bapenda Pembebasan / JR Pembebasan</p>
                        <div class="mt-2 d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-danger" data-type="sk_polda" data-bs-toggle="modal" data-bs-target="#modalSkPoldaTest">
                                <i class="fas fa-eye me-1"></i>Polda
                            </button>
                            <button class="btn btn-sm btn-outline-primary" data-type="sk_bapenda" data-bs-toggle="modal" data-bs-target="#modalSkBapendaTest">
                                <i class="fas fa-eye me-1"></i>Bapenda
                            </button>
                            <button class="btn btn-sm btn-outline-warning" data-type="sk_jr" data-bs-toggle="modal" data-bs-target="#modalSkJRTest">
                                <i class="fas fa-eye me-1"></i>JR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- HTML / CSS / BLADE EDITOR WITH LIVE PREVIEW --}}
    {{-- ============================================================ --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-code me-2"></i>HTML / CSS / Blade Editor</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-light" id="btnRenderBlade">
                    <i class="fas fa-play me-1"></i>Render Blade
                </button>
                <button class="btn btn-sm btn-outline-light" id="btnResetEditor">
                    <i class="fas fa-undo me-1"></i>Reset
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-6 border-end">
                    <div class="p-2 bg-light border-bottom">
                        <small class="text-muted fw-bold"><i class="fas fa-edit me-1"></i>Editor</small>
                    </div>
                    <textarea id="codeEditor" class="form-control border-0 rounded-0" style="font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.5; min-height: 600px; resize: vertical; tab-size: 2;" placeholder="Tulis HTML / CSS / Blade di sini...">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;style&gt;
        body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
        h1 { color: #1a56db; border-bottom: 2px solid #1a56db; padding-bottom: 10px; }
        .content { margin-top: 20px; line-height: 1.6; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #888; }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;Contoh Template Surat&lt;/h1&gt;
    &lt;div class="content"&gt;
        &lt;p&gt;Yang bertanda tangan di bawah ini:&lt;/p&gt;
        &lt;table style="width:100%;"&gt;
            &lt;tr&gt;&lt;td style="width:120px;"&gt;Nama&lt;/td&gt;&lt;td&gt;: @{{ $nama ?? '-' }}&lt;/td&gt;&lt;/tr&gt;
            &lt;tr&gt;&lt;td&gt;Jabatan&lt;/td&gt;&lt;td&gt;: @{{ $jabatan ?? '-' }}&lt;/td&gt;&lt;/tr&gt;
            &lt;tr&gt;&lt;td&gt;NRKB&lt;/td&gt;&lt;td&gt;: @{{ $nrkb ?? '-' }}&lt;/td&gt;&lt;/tr&gt;
        &lt;/table&gt;
        &lt;p&gt;Dengan ini menerangkan bahwa...&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="footer"&gt;
        &lt;p&gt;Dokumen ini dicetak pada @{{ date('d F Y') }}&lt;/p&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
                </div>
                <div class="col-md-6">
                    <div class="p-2 bg-light border-bottom">
                        <small class="text-muted fw-bold"><i class="fas fa-eye me-1"></i>Preview</small>
                        <small class="text-muted ms-2" id="previewStatus"></small>
                    </div>
                    <div style="position: relative; min-height: 600px; background: #fff;">
                        <iframe id="previewFrame" style="width: 100%; height: 600px; border: none;"></iframe>
                        <div id="previewLoading" class="position-absolute top-50 start-50 translate-none" style="display:none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSpDefaultTest" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>SP Samsat → Polda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- content --}}
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 2: SP Polda → Bapenda & JR --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSpPolda2bapendajrTest" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>SP Polda → Bapenda & JR</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- content --}}
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 3: SP Balasan Bapenda --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSpBalasanBapendaTest" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>SP Balasan Bapenda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- content --}}
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 4: SP Balasan Jasa Raharja --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSpBalasanJRTest" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>SP Balasan Jasa Raharja</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- content --}}
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 5: SK Samsat Default --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSkDefaultTest" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
	    <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>SK Samsat (Default)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- content --}}
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 6a: SK Polda Regident --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSkPoldaTest" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
	    <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>SK Polda Regident</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- content --}}
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 6b: SK Bapenda Pembebasan --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSkBapendaTest" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
	    <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>SK Bapenda Pembebasan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- content --}}
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 6c: SK Jasa Raharja Pembebasan --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSkJRTest" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
	    <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>SK Jasa Raharja Pembebasan SWDKLLJ</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                {{-- content --}}
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- JAVASCRIPT: Preview logic for all modals --}}
    {{-- ============================================================ --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // ── HTML / CSS / Blade Editor ──
        const editor = document.getElementById('codeEditor');
        const frame = document.getElementById('previewFrame');
        const status = document.getElementById('previewStatus');
        const loading = document.getElementById('previewLoading');
        const btnRender = document.getElementById('btnRenderBlade');
        const btnReset = document.getElementById('btnResetEditor');

        const DEFAULT_CODE = editor.value;

        function updatePreview() {
            const code = editor.value;
            const hasBlade = /@\w+|@{{.*?}}|@{!!.*?!!}/.test(code);

            if (hasBlade) {
                status.textContent = '⏳ Gunakan "Render Blade" untuk Blade syntax';
                frame.srcdoc = '<html><body style="font-family:sans-serif;padding:40px;color:#999;text-align:center;"><h2>Blade Syntax Terdeteksi</h2><p>Klik tombol <strong>Render Blade</strong> untuk memproses.</p></body></html>';
                return;
            }

            status.textContent = '✓ Live';
            frame.srcdoc = code;
        }

        let debounceTimer;
        editor.addEventListener('input', function () {
            status.textContent = '⏳ Mengetik...';
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(updatePreview, 400);
        });

        btnRender.addEventListener('click', async function () {
            const code = editor.value;
            if (!code.trim()) return;

            btnRender.disabled = true;
            btnRender.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Merender...';
            status.textContent = '⏳ Merender Blade...';

            try {
                const response = await fetch('/surat-template-test/render-code', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'text/html',
                    },
                    body: JSON.stringify({ code: code }),
                });

                if (!response.ok) {
                    const err = await response.text();
                    throw new Error(err.substring(0, 200));
                }

                const html = await response.text();
                frame.srcdoc = html;
                status.textContent = '✓ Blade rendered';
            } catch (error) {
                console.error('Render error:', error);
                frame.srcdoc = '<html><body style="font-family:sans-serif;padding:40px;color:red;"><h2>Error</h2><pre style="white-space:pre-wrap;">' + error.message + '</pre></body></html>';
                status.textContent = '✗ Error';
            } finally {
                btnRender.disabled = false;
                btnRender.innerHTML = '<i class="fas fa-play me-1"></i>Render Blade';
            }
        });

        btnReset.addEventListener('click', function () {
            editor.value = DEFAULT_CODE;
            updatePreview();
        });

        // Initial render
        updatePreview();
    });
    </script>
    @push('scripts')
        <script>
            $(document).ready(function () {
		let currentBlobUrl = null;
                async function getModalContent(type) {
                    try {
                        const url = "{{route('surat-template-test.modal',[':type'])}}".replace(':type',type);

                        const response = await $.ajax({
                            type: "GET",
                            url: url,
                            dataType: "html",
                        });
                        return response;
                    } catch (error) {
                        console.error("Gagal mengambil data modal:", error);
                        return '<div class="alert alert-danger">Gagal memuat data. Silakan coba lagi.</div>';
                    }
                }

                const initRepeater = (repeater) => {
                    return repeater.repeater({
                        initEmpty: false,
                        defaultValues: {
                            'text-input': ''
                        },
                        show: function () {
                            $(this).slideDown({
                                duration: 200,
                                easing: 'linear'
                            });
                            const btnDelete = $(this).find('[data-repeater-delete]');
                            btnDelete.removeAttr('class').addClass('btn btn-outline-danger d-inline-flex align-items-center justify-content-center');
                            btnDelete.css({
                                'height': '38px',
                                'width': '38px'
                            });
                        },
                        hide: function (deleteElement) {
                            const $row = $(this);
                            const inputValue = $row.find('input[type="text"]').val();
                            if (inputValue && inputValue.trim() !== ''){
                                swal({
                                    title: "Apakah Anda yakin ingin menghapus rujukan ini?",
                                    text: "Anda tidak dapat memulihkan rujukan yang telah terhapus.",
                                    icon: "warning",
                                    buttons: {
                                        confim: {
                                            text:"Hapus",
                                            className: "btn btn-danger"
                                        },
                                        cancel: {
                                            visible: true,
                                            text: "Batal",
                                            className: "btn btn-success"
                                        }
                                    }
                                }).then((Delete) => {
                                    if(Delete){
                                        $row.slideUp(deleteElement);
                                    }else{
                                        swal.close();
                                    }
                                })
                            }else{
                                $row.slideUp(deleteElement);
                            }
                        },
                    })
                }

                $('.modal').on('show.bs.modal', async function(e){
                    const modal = $(e.target);
                    const type = $(e.relatedTarget).attr('data-type');
		    console.log(type);
                    if (!type) return;
                    const modal_content = modal.find('.modal-content');
                    modal_content.append(`
                        <div class="text-center py-5">
                            <div class="spinner-border text-info" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat data...</p>
                        </div>
                    `);

                    const content = await getModalContent(type);
                    modal_content.children().not(":first").remove();
                    modal_content.append(content);

                    const body = modal.find('.modal-body');
                    const form = modal.find("form");
                    const formContainer = form.children().eq(1);
                    const iFrame = modal.find('iframe');
                    const previewContainer = iFrame.parent();
                    const footerForm = modal.find('.modal-footer').eq(0);
                    const footerPreview = modal.find('.modal-footer').eq(1);
                    const btnPreview = footerForm.children().eq(1);
                    const btnEdit = footerPreview.children().eq(0);
                    const repeater = initRepeater(modal.find('.repeater'));
                    const terusanRepeater = initRepeater(modal.find('.repeater-terusan'));
		    
		    btnEdit.off('click').on('click',function(e){
			e.preventDefault();
			previewContainer.css('display', 'none');
		        footerPreview.css('display', 'none');

		        formContainer.css('display', 'block');
		        footerForm.css('display', 'flex');
		    })

                    btnPreview.click(async function (e) {
                        e.preventDefault();
                        if($(this).prop('disabled')) return;

                        const originalHtml = btnPreview.html();
                        btnPreview.prop('disabled', true);
                        btnPreview.html('<span class="spinner-border spinner-border-sm me-1"></span> Memuat...');

                        try {
                            const formData = new FormData(form[0]);
                            const response = await fetch('/surat-template-test/preview/' + type, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/pdf',
                                }
                            });
                            if (!response.ok) throw new Error('Request failed: ' + response.status);
                            const blob = await response.blob();
                            if (currentBlobUrl) URL.revokeObjectURL(currentBlobUrl);
                            currentBlobUrl = URL.createObjectURL(blob);
			    iFrame[0].src = currentBlobUrl;
                            

                            // 6. Sembunyikan form elemen, tampilkan preview container beserta footer alternatif
                            formContainer.css('display','none');
                            footerForm.css('display','none');

                            previewContainer.css('display','block');
                            footerPreview.css('display','flex');
                        } catch (error) {
                            console.error('Preview failed:', error);
                            // alert('Gagal memuat preview PDF.');
                        }finally{
                            btnPreview.prop('disabled', false);
                            btnPreview.html(originalHtml);
                        }
                    });

                });

                $('.modal').on('hidden.bs.modal', function(e){
                    const modal = $(e.target);
		    URL.revokeObjectURL(currentBlobUrl);
                    const modal_content = modal.find('.modal-content');
                    modal_content.children().not(":first").remove();
                })
            });
        </script>
    @endpush
</x-app-layout>
