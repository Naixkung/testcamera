<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ทดสอบ สแกนบาร์โค้ดสินค้า (สลับกล้องหน้า/หลัง)</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Html5-Qrcode CDN -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        body {
            background-color: #f0f2f5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .scanner-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            max-width: 550px;
            width: 100%;
            padding: 30px;
        }
        #qr-reader {
            width: 100% !important;
            border-radius: 8px;
            overflow: hidden;
            background: #000;
        }
        #qr-reader video {
            border-radius: 8px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<div class="scanner-card text-center">
    <h3 class="fw-bold text-dark mb-2">
        <i class="fa-solid fa-barcode text-success"></i> ทดสอบสแกนบาร์โค้ดสินค้า
    </h3>
    <p class="text-muted small mb-4">
        ทดสอบระบบสลับกล้องหน้า / กล้องหลัง (Standalone ไม่เกี่ยวกับระบบอื่น)
    </p>

    <!-- ปุ่มเปิด Modal สแกน -->
    <div class="d-grid gap-3 mb-4">
        <button class="btn btn-primary btn-lg fw-bold shadow-sm py-3" type="button" id="btnScanBarcode">
            <i class="fa-solid fa-barcode"></i> Scan สแกนบาร์โค้ดสินค้า
        </button>
    </div>

    <!-- กล่องแสดงผลลัพธ์ที่สแกนได้ -->
    <div class="text-start">
        <label class="form-label fw-bold small text-secondary">
            <i class="fa-solid fa-circle-check text-success"></i> รหัสสินค้าที่อ่านได้:
        </label>
        <div class="input-group">
            <input type="text" id="scanned_result_input" class="form-control form-control-lg bg-light fw-bold text-center text-primary" placeholder="-- ยังไม่มีข้อมูล --" readonly>
            <button class="btn btn-outline-secondary" type="button" id="btnCopyResult" title="คัดลอก">
                <i class="fa-regular fa-copy"></i>
            </button>
        </div>
    </div>
</div>

<!-- ============================================================
     Scanner Modal
============================================================ -->
<div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background:#00796b;">
                <h5 class="modal-title fs-5 mb-0">
                    <i class="fa-solid fa-barcode"></i> สแกนบาร์โค้ดสินค้า
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-light fw-bold shadow-sm" id="btnSwitchCamera" title="สลับกล้องหน้า/หลัง">
                        <i class="fa-solid fa-arrows-rotate"></i> สลับเป็นกล้องหน้า
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body text-center p-3">
                <div id="qr-reader"></div>
                <p id="scan-status" class="mt-3 mb-0 fw-bold text-primary">
                    <i class="fa-solid fa-spinner fa-spin"></i> กำลังเตรียมกล้อง...
                </p>
            </div>

            <div class="modal-footer d-flex justify-content-between bg-light">
                <button type="button" class="btn btn-primary fw-bold" id="btnSwitchCameraFooter">
                    <i class="fa-solid fa-arrows-rotate"></i> สลับเป็นกล้องหน้า
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<!-- CDN Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ============================================================
// Barcode Scanning & Front/Back Camera Switching Logic
// ============================================================
let html5QrcodeScanner = null;
let availableCameras = [];
let isFrontCamera = false; // false = กล้องหลัง, true = กล้องหน้า
let isSwitching = false;

function updateSwitchButtonLabels() {
    let nextCam = isFrontCamera ? 'กล้องหลัง' : 'กล้องหน้า';
    let btnHtml = '<i class="fa-solid fa-arrows-rotate"></i> สลับเป็น' + nextCam;
    $('#btnSwitchCamera').html(btnHtml);
    $('#btnSwitchCameraFooter').html(btnHtml);
}

function isScannerRunning() {
    if (!html5QrcodeScanner) return false;
    try {
        if (typeof html5QrcodeScanner.getState === 'function') {
            let state = html5QrcodeScanner.getState();
            // 2 = SCANNING, 3 = PAUSED
            return state === 2 || state === 3;
        }
    } catch (e) {}
    return false;
}

function stopScanner() {
    if (html5QrcodeScanner && isScannerRunning()) {
        return html5QrcodeScanner.stop().catch(err => {
            console.warn("Scanner stop warning:", err);
        });
    }
    return Promise.resolve();
}

function startScannerWithTarget(targetCamera) {
    if (!html5QrcodeScanner) {
        html5QrcodeScanner = new Html5Qrcode("qr-reader");
    }

    // สำคัญมาก: ไม่ใส่ config.videoConstraints เพื่อไม่ให้ override targetCamera
    let config = { 
        fps: 20, 
        qrbox: { width: 300, height: 120 },
        formatsToSupport: [
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.CODE_93,
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.EAN_8,
            Html5QrcodeSupportedFormats.UPC_A,
            Html5QrcodeSupportedFormats.UPC_E,
            Html5QrcodeSupportedFormats.ITF,
            Html5QrcodeSupportedFormats.QR_CODE
        ]
    };

    return html5QrcodeScanner.start(
        targetCamera,
        config,
        function (decodedText, decodedResult) {
            $('#scan-status').html('<i class="fa-solid fa-circle-check"></i> อ่านสำเร็จ!').removeClass('text-primary text-danger').addClass('text-success');
            if (navigator.vibrate) navigator.vibrate(200);

            $('#scanned_result_input').val(decodedText);

            setTimeout(() => {
                stopScanner().finally(() => {
                    $('#scannerModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'อ่านสำเร็จ!',
                        html: 'รหัสสินค้า: <b class="text-success fs-4">' + decodedText + '</b>',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#198754'
                    });
                });
            }, 500);
        },
        function (errorMessage) {
            if ($('#scan-status').hasClass('text-primary') && !$('#scan-status').html().includes('กำลังค้นหา')) {
                let camName = isFrontCamera ? 'กล้องหน้า' : 'กล้องหลัง';
                $('#scan-status').html('<i class="fa-solid fa-circle-notch fa-spin"></i> กำลังค้นหาบาร์โค้ด (' + camName + ')...').removeClass('text-success text-danger').addClass('text-primary');
            }
        }
    ).then(() => {
        let camName = isFrontCamera ? 'กล้องหน้า (Front)' : 'กล้องหลัง (Back)';
        $('#scan-status').html('<i class="fa-solid fa-camera"></i> กำลังใช้งาน: ' + camName).removeClass('text-danger text-success').addClass('text-primary');
        updateSwitchButtonLabels();
    });
}

function switchCamera() {
    if (isSwitching) return;
    isSwitching = true;

    // สลับสถานะ กล้องหน้า <-> กล้องหลัง
    isFrontCamera = !isFrontCamera;
    updateSwitchButtonLabels();

    let targetMode = isFrontCamera ? "user" : "environment";
    let target = null;

    if (availableCameras && availableCameras.length > 0) {
        if (isFrontCamera) {
            let fc = availableCameras.find(d => /front|user|selfie|หน้า/i.test(d.label));
            if (fc) {
                target = fc.id;
            } else if (availableCameras.length > 1) {
                let nonBack = availableCameras.filter(d => !/back|rear|environment|หลัง/i.test(d.label));
                target = (nonBack.length > 0) ? nonBack[nonBack.length - 1].id : availableCameras[availableCameras.length - 1].id;
            }
        } else {
            let bc = availableCameras.find(d => /back|rear|environment|หลัง/i.test(d.label));
            target = bc ? bc.id : availableCameras[0].id;
        }
    }

    if (!target) {
        target = { facingMode: targetMode };
    }

    let targetName = isFrontCamera ? "กล้องหน้า" : "กล้องหลัง";
    $('#scan-status').html('<i class="fa-solid fa-spinner fa-spin"></i> กำลังสลับไป ' + targetName + '...').removeClass('text-success text-danger').addClass('text-primary');

    stopScanner().then(() => {
        return startScannerWithTarget(target);
    }).catch(err => {
        console.warn("Start with device ID failed, retrying with facingMode " + targetMode, err);
        return startScannerWithTarget({ facingMode: targetMode });
    }).catch(err => {
        console.warn("FacingMode failed, retrying fallback:", err);
        if (availableCameras && availableCameras.length > 0) {
            let altIdx = isFrontCamera ? (availableCameras.length - 1) : 0;
            return startScannerWithTarget(availableCameras[altIdx].id);
        }
        throw err;
    }).catch(err => {
        console.error("Camera switch error:", err);
        $('#scan-status').html('<i class="fa-solid fa-triangle-exclamation"></i> ไม่สามารถเปิด ' + targetName + ' ได้').removeClass('text-primary text-success').addClass('text-danger');
        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเปิด ' + targetName + ' ได้ (โปรดตรวจสอบสิทธิ์กล้อง)', 'error');
    }).finally(() => {
        isSwitching = false;
    });
}

$(document).on('click', '#btnSwitchCamera, #btnSwitchCameraFooter', function () {
    switchCamera();
});

$('#btnScanBarcode').click(function () {
    $('#scan-status').html('<i class="fa-solid fa-spinner fa-spin"></i> กำลังเตรียมกล้อง...').removeClass('text-success text-danger').addClass('text-primary');
    $('#scannerModal').modal('show');
});

$('#scannerModal').on('shown.bs.modal', function () {
    isFrontCamera = false; // เริ่มต้นที่กล้องหลังเสมอ
    updateSwitchButtonLabels();
    $('#scan-status').html('<i class="fa-solid fa-spinner fa-spin"></i> กำลังเปิดกล้องหลัง...').removeClass('text-success text-danger').addClass('text-primary');

    if (!html5QrcodeScanner) {
        html5QrcodeScanner = new Html5Qrcode("qr-reader");
    }

    Html5Qrcode.getCameras().then(devices => {
        availableCameras = devices || [];
        let backCam = availableCameras.find(d => /back|rear|environment/i.test(d.label));
        let target = backCam ? backCam.id : (availableCameras.length > 0 ? availableCameras[0].id : { facingMode: "environment" });

        stopScanner().then(() => {
            return startScannerWithTarget(target);
        }).catch(err => {
            console.warn("Start with deviceId failed, fallback to facingMode:", err);
            return startScannerWithTarget({ facingMode: "environment" });
        });
    }).catch(err => {
        console.warn("getCameras failed, fallback to facingMode:", err);
        stopScanner().then(() => {
            return startScannerWithTarget({ facingMode: "environment" });
        });
    });
});

$('#scannerModal').on('hidden.bs.modal', function () {
    stopScanner();
});

$('#btnCopyResult').click(function () {
    let val = $('#scanned_result_input').val();
    if (val && val !== '-- ยังไม่มีข้อมูล --') {
        navigator.clipboard.writeText(val);
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'คัดลอกรหัสแล้ว',
            showConfirmButton: false,
            timer: 1500
        });
    }
});
</script>

</body>
</html>
