
        @php
            $docs = collect();

            // Extract SK PDFs
            if (!empty($pengajuan->suratKeputusan)) {
                foreach ($pengajuan->suratKeputusan as $sk) {
                    if ($sk->isDraft()) {
                        continue;
                    }
                    if (!empty($sk->pdf_url)) {
                        $docs->push((object)[
                            'pdf_url' => $sk->pdf_url,
                            'display_name' => basename($sk->pdf_url),
                            'created_at' => $sk->created_at,
                        ]);
                    }
                }
            }

            // Extract SP PDFs & SP Balasan PDFs
            if (!empty($pengajuan->suratPengajuan)) {
                foreach ($pengajuan->suratPengajuan as $sp) {
                    // SP Utama (Pengajuan)
                    if (!empty($sp->pdf_url)) {
                        $docs->push((object)[
                            'pdf_url' => $sp->pdf_url,
                            'display_name' => basename($sp->pdf_url),
                            'created_at' => $sp->created_at,
                        ]);
                    }

                    // SP Balasan from persetujuan_unit_kerja array
                    if (!empty($sp->persetujuan_unit_kerja) && is_array($sp->persetujuan_unit_kerja)) {
                        foreach ($sp->persetujuan_unit_kerja as $item) {
                            if (!empty($item['pdf_url'])) {
                                $docs->push((object)[
                                    'pdf_url' => $item['pdf_url'],
                                    'display_name' => 'SP Balasan (' . ($item['instansi'] ?? 'Instansi') . ') - ' . basename($item['pdf_url']),
                                    'created_at' => !empty($item['updated_at']) ? \Carbon\Carbon::parse($item['updated_at']) : $sp->updated_at,
                                ]);
                            }
                        }
                    }
                }
            }

            $all_docs = $docs->sortByDesc('created_at');
        @endphp

        @if($all_docs->isNotEmpty())
            <div class="row">
                @foreach($all_docs as $doc)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                @php
                                    $docPdfUrl = $doc->pdf_url;
                                    // Ambil nama file dari URL
                                    $name = $doc->display_name ?? basename($docPdfUrl);
                                    $viewUrl = $docPdfUrl;
                                    $downloadUrl = $docPdfUrl;
                                    // Build safe URL: if localhost/127.0.0.1, force port 8000
                                    $parts = @parse_url($docPdfUrl);
                                    if ($parts && isset($parts['host']) && in_array($parts['host'], ['localhost', '127.0.0.1'])) {
                                        $scheme = $parts['scheme'] ?? 'http';
                                        $path = $parts['path'] ?? '';
                                        $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
                                        $fragment = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';
                                        $viewUrl = $scheme . '://' . $parts['host'] . ':8000' . $path . $query . $fragment;
                                        $downloadUrl = $viewUrl;
                                    }
                                @endphp
                                <h6 class="card-title text-truncate" title="{{ $name }}">{{ $name }}</h6>
                                <p class="card-text small text-muted">
                                    Dibuat: {{ $doc->created_at->format('d M Y H:i') }}
                                </p>
                                <a href="{{ $viewUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Lihat PDF
                                </a>
                                <a href="{{ $downloadUrl }}" download class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-file-pdf fa-3x mb-3 opacity-30"></i>
                <p class="mb-0">Belum ada dokumen keputusan atau pengajuan resmi yang diterbitkan.</p>
            </div>
        @endif

