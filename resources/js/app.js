const scanPage = document.querySelector('[data-scan-page]');

if (scanPage) {
    const video = document.getElementById('scanVideo');
    const placeholder = document.getElementById('scanPlaceholder');
    const toggleButton = document.getElementById('cameraToggle');
    const statusText = document.getElementById('scanStatus');
    const resultText = document.getElementById('scanResult');
    const buttonLabel = document.getElementById('cameraLabel');

    let stream = null;
    let detector = null;
    let rafId = null;
    let zxingReader = null;
    let zxingControls = null;
    let lastValue = null;
    let scanning = false;

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

    const stopCamera = () => {
        scanning = false;
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

    const handleDetectedValue = (value) => {
        if (value && value !== lastValue) {
            lastValue = value;
            setResult(value);
            setStatus('Barcode terdeteksi.');
        }
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
                null,
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
            setStatus('Browser tidak mendukung akses kamera.');
            return;
        }

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
            startCamera();
        });
    }

    document.addEventListener('visibilitychange', () => {
        if (document.hidden && (stream || zxingControls)) {
            stopCamera();
        }
    });
}
