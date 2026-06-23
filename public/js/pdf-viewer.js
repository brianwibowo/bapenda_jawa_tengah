/**
 * Bapenda Custom PDF Viewer Helper using PDF.js
 */
window.BapendaPdfViewer = (function () {
    // Configure worker source if pdfjsLib is loaded
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
    }

    /**
     * Renders a page of the PDF document.
     */
    function renderPage(state) {
        state.pageRendering = true;
        state.elements.loadingOverlay.style.display = 'flex';

        state.pdfDoc.getPage(state.pageNum).then(function (page) {
            const viewport = page.getViewport({ scale: state.scale });
            const canvas = state.canvas;
            const ctx = state.ctx;

            // Retina display support
            const outputScale = window.devicePixelRatio || 1;
            canvas.width = Math.floor(viewport.width * outputScale);
            canvas.height = Math.floor(viewport.height * outputScale);
            canvas.style.width = Math.floor(viewport.width) + 'px';
            canvas.style.height = Math.floor(viewport.height) + 'px';

            const transform = outputScale !== 1
                ? [outputScale, 0, 0, outputScale, 0, 0]
                : null;

            const renderContext = {
                canvasContext: ctx,
                transform: transform,
                viewport: viewport
            };

            const renderTask = page.render(renderContext);

            renderTask.promise.then(function () {
                state.pageRendering = false;
                state.elements.loadingOverlay.style.display = 'none';

                if (state.pageNumPending !== null) {
                    // New page rendering is pending
                    const pendingPage = state.pageNumPending;
                    state.pageNumPending = null;
                    renderPage(state);
                }
            });
        });

        // Update control elements
        state.elements.currentPage.textContent = state.pageNum;
        state.elements.prevBtn.disabled = state.pageNum <= 1;
        state.elements.nextBtn.disabled = state.pageNum >= state.pdfDoc.numPages;
        state.elements.zoomIndicator.textContent = Math.round(state.scale * 100) + '%';
    }

    /**
     * Queues page rendering if rendering is currently in progress.
     */
    function queueRenderPage(state, num) {
        if (state.pageRendering) {
            state.pageNumPending = num;
        } else {
            state.pageNum = num;
            renderPage(state);
        }
    }

    /**
     * Setup custom viewer DOM markup if not already present.
     */
    function ensureViewerMarkup(container) {
        let wrapper = container.querySelector('.pdf-viewer-wrapper');
        if (!wrapper) {
            container.innerHTML = `
                <div class="pdf-viewer-wrapper">
                    <div class="pdf-toolbar">
                        <div class="pdf-toolbar-group">
                            <button type="button" class="pdf-btn pdf-prev-btn" title="Halaman Sebelumnya">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span class="pdf-page-indicator">Halaman <span class="pdf-current-page">1</span> / <span class="pdf-total-pages">1</span></span>
                            <button type="button" class="pdf-btn pdf-next-btn" title="Halaman Selanjutnya">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div class="pdf-toolbar-group">
                            <button type="button" class="pdf-btn pdf-zoom-out-btn" title="Perkecil">
                                <i class="fas fa-search-minus"></i>
                            </button>
                            <span class="pdf-zoom-indicator">100%</span>
                            <button type="button" class="pdf-btn pdf-zoom-in-btn" title="Perbesar">
                                <i class="fas fa-search-plus"></i>
                            </button>
                        </div>
                        <div class="pdf-toolbar-group">
                            <button type="button" class="pdf-btn pdf-print-btn" title="Cetak Dokumen">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                            <a href="#" class="pdf-btn pdf-btn-primary pdf-download-btn" title="Unduh Dokumen" download>
                                <i class="fas fa-download"></i> Unduh
                            </a>
                        </div>
                    </div>
                    <div class="pdf-viewport">
                        <div class="pdf-loading-overlay">
                            <div class="pdf-spinner"></div>
                            <span>Memuat Dokumen...</span>
                        </div>
                        <canvas class="pdf-canvas"></canvas>
                    </div>
                </div>
            `;
            wrapper = container.querySelector('.pdf-viewer-wrapper');
        }
        return wrapper;
    }

    /**
     * Public method to render a PDF Document inside a container.
     */
    function render(containerId, blobUrl, downloadFileName) {
        const container = document.getElementById(containerId);
        if (!container) {
            console.error(`Container dengan ID '${containerId}' tidak ditemukan.`);
            return;
        }

        // Initialize Global Worker source just in case it wasn't set earlier
        if (typeof pdfjsLib !== 'undefined' && !pdfjsLib.GlobalWorkerOptions.workerSrc) {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
        }

        const wrapper = ensureViewerMarkup(container);

        // Get child elements
        const prevBtn = wrapper.querySelector('.pdf-prev-btn');
        const nextBtn = wrapper.querySelector('.pdf-next-btn');
        const currentPage = wrapper.querySelector('.pdf-current-page');
        const totalPages = wrapper.querySelector('.pdf-total-pages');
        const zoomOutBtn = wrapper.querySelector('.pdf-zoom-out-btn');
        const zoomInBtn = wrapper.querySelector('.pdf-zoom-in-btn');
        const zoomIndicator = wrapper.querySelector('.pdf-zoom-indicator');
        const printBtn = wrapper.querySelector('.pdf-print-btn');
        const downloadBtn = wrapper.querySelector('.pdf-download-btn');
        const loadingOverlay = wrapper.querySelector('.pdf-loading-overlay');
        const canvas = wrapper.querySelector('.pdf-canvas');

        // Cleanup existing listeners if any by cloning controls (or re-creating the whole state object)
        const newState = {
            pdfDoc: null,
            pageNum: 1,
            scale: 1.1, // Set default zoom scale slightly higher for readability
            pageRendering: false,
            pageNumPending: null,
            blobUrl: blobUrl,
            filename: downloadFileName || 'dokumen.pdf',
            canvas: canvas,
            ctx: canvas.getContext('2d'),
            elements: {
                prevBtn,
                nextBtn,
                currentPage,
                totalPages,
                zoomOutBtn,
                zoomInBtn,
                zoomIndicator,
                printBtn,
                downloadBtn,
                loadingOverlay
            }
        };

        // Attach state to the container so we can access it or clean it up later
        container._pdfViewerState = newState;

        // Show loading state
        loadingOverlay.style.display = 'flex';

        // Load document
        pdfjsLib.getDocument(blobUrl).promise.then(function (pdfDoc_) {
            newState.pdfDoc = pdfDoc_;
            totalPages.textContent = pdfDoc_.numPages;

            // Render first page
            renderPage(newState);
        }).catch(function (error) {
            console.error('PDF.js loading error:', error);
            loadingOverlay.innerHTML = `
                <div class="text-danger mb-2"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                <span class="text-danger fw-bold">Gagal memuat pratinjau PDF.</span>
            `;
        });

        // Set download link
        downloadBtn.href = blobUrl;
        downloadBtn.download = downloadFileName || 'dokumen.pdf';

        // Navigation listeners
        prevBtn.onclick = function () {
            if (newState.pageNum <= 1) return;
            queueRenderPage(newState, newState.pageNum - 1);
        };

        nextBtn.onclick = function () {
            if (newState.pageNum >= newState.pdfDoc.numPages) return;
            queueRenderPage(newState, newState.pageNum + 1);
        };

        // Zoom listeners
        zoomInBtn.onclick = function () {
            if (newState.scale >= 3.0) return;
            newState.scale = parseFloat((newState.scale + 0.15).toFixed(2));
            queueRenderPage(newState, newState.pageNum);
        };

        zoomOutBtn.onclick = function () {
            if (newState.scale <= 0.6) return;
            newState.scale = parseFloat((newState.scale - 0.15).toFixed(2));
            queueRenderPage(newState, newState.pageNum);
        };

        // Print listener
        printBtn.onclick = function () {
            const printIframe = document.createElement('iframe');
            printIframe.style.display = 'none';
            printIframe.src = blobUrl;
            document.body.appendChild(printIframe);
            printIframe.onload = function () {
                try {
                    printIframe.contentWindow.focus();
                    printIframe.contentWindow.print();
                } catch (e) {
                    console.error('Cetak PDF gagal:', e);
                }
                setTimeout(function () {
                    document.body.removeChild(printIframe);
                }, 1000);
            };
        };
    }

    /**
     * Cleanup resources and detach PDF document state.
     */
    function cleanup(containerId) {
        const container = document.getElementById(containerId);
        if (container && container._pdfViewerState) {
            const state = container._pdfViewerState;
            state.pdfDoc = null;
            state.ctx.clearRect(0, 0, state.canvas.width, state.canvas.height);
            // Hide wrapper content
            container.innerHTML = '';
            delete container._pdfViewerState;
        }
    }

    return {
        render: render,
        cleanup: cleanup
    };
})();
