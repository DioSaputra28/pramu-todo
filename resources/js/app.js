const scanPage = document.querySelector('[data-scan-page]');

if (scanPage) {
    const video = document.getElementById('scanVideo');
    const placeholder = document.getElementById('scanPlaceholder');
    const toggleButton = document.getElementById('cameraToggle');
    const statusText = document.getElementById('scanStatus');
    const resultText = document.getElementById('scanResult');
    const buttonLabel = document.getElementById('cameraLabel');
    const itemCountText = document.getElementById('scanItemCount');
    const latestItemText = document.getElementById('scanLatestItem');
    const latestWrapper = document.getElementById('scanLatestWrapper');
    const toastContainer = document.getElementById('toastContainer');

    const restockUrl = scanPage.dataset.restockUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // Ignore the same barcode if rescanned within this window (ms).
    const SCAN_COOLDOWN_MS = 2000;

    let stream = null;
    let detector = null;
    let rafId = null;
    let zxingReader = null;
    let zxingControls = null;
    let lastValue = null;
    let lastScanAt = 0;
    let scanning = false;
    let sending = false;
    let cameraActive = false;

    const barcodeFormats = [
        'ean_13',
        'ean_8',
        'upc_a',
        'upc_e',
        'code_128',
        'code_39',
        'code_93',
        'itf',
        'qr_code',
        'data_matrix',
        'pdf417',
        'aztec',
    ];

    const setStatus = (message) => {
        if (statusText) {
            statusText.textContent = message;
        }
    };

    const setResult = (value) => {
        if (resultText) {
            resultText.textContent = value || '-';
        }
    };

    const updateButton = (active) => {
        if (buttonLabel) {
            buttonLabel.textContent = active ? 'Matikan Kamera' : 'Aktifkan Kamera';
        }
    };

    const showVideo = () => {
        if (video) {
            video.classList.remove('hidden');
        }
        if (placeholder) {
            placeholder.classList.add('hidden');
        }
    };

    const toastStyles = {
        success: 'bg-primary-container text-on-primary',
        error: 'bg-error text-on-error',
        info: 'bg-inverse-surface text-inverse-on-surface',
    };

    const toastIcons = {
        success: 'check_circle',
        error: 'error',
        info: 'info',
    };

    const showToast = (message, type = 'info', actionUrl = null, actionLabel = null) => {
        if (!toastContainer) {
            return;
        }

        const toast = document.createElement('div');
        toast.className =
            `pointer-events-auto w-full max-w-md rounded-lg px-4 py-3 shadow-lg flex items-center gap-3 text-sm font-semibold transition-all duration-300 translate-y-2 opacity-0 ${toastStyles[type] ?? toastStyles.info}`;

        const icon = document.createElement('span');
        icon.className = 'material-symbols-outlined text-[20px] shrink-0';
        icon.textContent = toastIcons[type] ?? toastIcons.info;
        toast.appendChild(icon);

        const text = document.createElement('span');
        text.className = 'flex-1';
        text.textContent = message;
        toast.appendChild(text);

        if (actionUrl) {
            const link = document.createElement('a');
            link.href = actionUrl;
            link.className = 'underline underline-offset-2 shrink-0';
            link.textContent = actionLabel ?? 'Tambah';
            toast.appendChild(link);
        }

        toastContainer.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
        });

        const dismissDelay = actionUrl ? 5000 : 2500;
        window.setTimeout(() => {
            toast.classList.add('translate-y-2', 'opacity-0');
            window.setTimeout(() => toast.remove(), 300);
        }, dismissDelay);
    };

    const updateItemCount = (count) => {
        if (itemCountText && typeof count === 'number') {
            itemCountText.textContent = count;
        }
    };

    const updateLatestItem = (name) => {
        if (latestItemText) {
            latestItemText.textContent = name;
        }
        if (latestWrapper) {
            latestWrapper.classList.remove('hidden');
        }
    };

    const stopCamera = () => {
        scanning = false;
        cameraActive = false;
        if (rafId) {
            cancelAnimationFrame(rafId);
            rafId = null;
        }
        if (zxingControls) {
            zxingControls.stop();
            zxingControls = null;
        }
        if (zxingReader?.reset) {
            zxingReader.reset();
        }
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            stream = null;
        }
        if (video) {
            video.srcObject = null;
            video.classList.add('hidden');
        }
        if (placeholder) {
            placeholder.classList.remove('hidden');
        }
        updateButton(false);
        setStatus('Kamera dimatikan.');
    };

    const sendBarcode = async (barcode) => {
        if (!restockUrl) {
            return;
        }

        sending = true;
        setStatus('Mengirim barcode...');

        try {
            const response = await fetch(restockUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ barcode }),
            });

            const data = await response.json().catch(() => ({}));

            if (response.ok) {
                const item = data.item ?? {};
                const name = item.name ?? 'Produk';
                showToast(`Ditambahkan: ${name} (x${item.quantity ?? 1})`, 'success');
                updateItemCount(data.itemCount);
                updateLatestItem(name);
                setStatus('Arahkan barcode ke kotak.');
                return;
            }

            if (response.status === 404) {
                showToast(
                    data.message ?? 'Barcode belum terdaftar.',
                    'error',
                    data.addUrl ?? null,
                    'Daftarkan',
                );
                setStatus('Barcode belum terdaftar.');
                return;
            }

            if (response.status === 422) {
                showToast('Barcode tidak valid.', 'error');
                setStatus('Barcode tidak valid.');
                return;
            }

            showToast('Gagal menambahkan item. Coba lagi.', 'error');
            setStatus('Terjadi kesalahan.');
        } catch (error) {
            console.error(error);
            showToast('Gagal terhubung ke server.', 'error');
            setStatus('Gagal terhubung ke server.');
        } finally {
            sending = false;
        }
    };

    const handleDetectedValue = (value) => {
        const barcode = (value ?? '').trim();
        if (!barcode) {
            return;
        }

        const now = Date.now();
        if (barcode === lastValue && now - lastScanAt < SCAN_COOLDOWN_MS) {
            return;
        }

        if (sending) {
            return;
        }

        lastValue = barcode;
        lastScanAt = now;
        setResult(barcode);
        setStatus('Barcode terdeteksi.');
        sendBarcode(barcode);
    };

    const scanLoop = async () => {
        if (!scanning || !detector || !video) {
            return;
        }

        try {
            const barcodes = await detector.detect(video);
            if (barcodes.length > 0) {
                const value = barcodes[0].rawValue || '';
                handleDetectedValue(value);
            }
        } catch (error) {
            console.error(error);
        }

        rafId = requestAnimationFrame(scanLoop);
    };

    const startZxing = async () => {
        try {
            const module = await import('@zxing/browser');
            if (!zxingReader) {
                zxingReader = new module.BrowserMultiFormatReader();
            }

            showVideo();
            scanning = true;
            updateButton(true);
            setStatus('Menggunakan fallback scanner.');

            zxingControls = await zxingReader.decodeFromVideoDevice(
                undefined,
                video,
                (result) => {
                    if (result) {
                        handleDetectedValue(result.getText());
                    }
                },
            );
        } catch (error) {
            console.error(error);
            setStatus('Fallback scanner gagal dijalankan.');
            stopCamera();
        }
    };

    const startCamera = async () => {
        if (!navigator.mediaDevices?.getUserMedia) {
            if (!window.isSecureContext) {
                setStatus('Kamera butuh HTTPS. Buka situs lewat https:// atau localhost.');
            } else {
                setStatus('Browser tidak mendukung akses kamera.');
            }
            return;
        }

        cameraActive = true;

        try {
            if ('BarcodeDetector' in window) {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' } },
                    audio: false,
                });

                detector = new BarcodeDetector({ formats: barcodeFormats });
                if (video) {
                    video.srcObject = stream;
                    await video.play();
                }
                showVideo();
                scanning = true;
                updateButton(true);
                setStatus('Arahkan barcode ke kotak.');
                scanLoop();
                return;
            }

            await startZxing();
        } catch (error) {
            console.error(error);
            cameraActive = false;
            setStatus('Tidak bisa mengakses kamera. Pastikan izin kamera diaktifkan.');
        }
    };

    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            if (stream || zxingControls) {
                stopCamera();
                return;
            }

            setResult('-');
            lastValue = null;
            lastScanAt = 0;
            startCamera();
        });
    }

    document.addEventListener('visibilitychange', () => {
        if (document.hidden && (stream || zxingControls)) {
            const wasActive = cameraActive;
            stopCamera();
            // Pertahankan niat untuk menyalakan ulang saat kembali ke tab.
            cameraActive = wasActive;
        } else if (!document.hidden && cameraActive && !stream && !zxingControls) {
            startCamera();
        }
    });

    // Otomatis nyalakan kamera saat halaman scan dibuka.
    // Browser tetap menampilkan dialog izin pada kunjungan pertama; setelah
    // diizinkan, kamera akan langsung aktif pada kunjungan berikutnya.
    startCamera();
}
