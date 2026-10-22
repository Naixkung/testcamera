<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Standalone Barcode Scanner Test</title>

    <!-- CDN ทั้งหมด 100% ไม่พึ่งพาไฟล์หรือระบบในโปรแกรม Housekeeping -->
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Html5-Qrcode CDN -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
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
    <div class="mb-3">
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
            <i class="fa-solid fa-code"></i> Standalone Test Page
        </span>
    </div>
    
    <h3 class="fw-bold text-dark mb-2">
        <i class="fa-solid fa-barcode text-success"></i> ทดสอบสแกนบาร์โค้ด
    </h3>
    <p class="text-muted small mb-4">
        หน้าทดสอบแบบอิสระ ไม่เชื่อมต่อกับฐานข้อมูลหรือระบบอื่นในเครื่อง
    </p>

    <!-- ปุ่มเปิด Modal สแกน -->
    <div class="d-grid gap-3 mb-4">
        <button class="btn btn-success btn-lg fw-bold shadow-sm py-3" type="button" id="btnScanBarcode">
            <i class="fa-solid fa-camera"></i> เปิดกล้องสแกนบาร์โค้ด
        </button>
    </div>

    <!-- กล่องแสดงผลลัพธ์ที่สแกนได้ -->
    <div class="text-start">
        <label class="form-label fw-bold small text-secondary">
            <i class="fa-solid fa-circle-check text-success"></i> ผลการอ่านบาร์โค้ดล่าสุด:
        </label>
        <div class="input-group">
            <input type="text" id="scanned_result_input" class="form-control form-control-lg bg-light fw-bold text-center text-primary" placeholder="-- รหัสจะแสดงที่นี่ --" readonly>
            <button class="btn btn-outline-secondary" type="button" id="btnCopyResult" title="คัดลอก">
                <i class="fa-regular fa-copy"></i>
            </button>
        </div>
    </div>
</div>

<!-- ============================================================
     Scanner Modal (หน้าต่างสแกนบาร์โค้ดสินค้า + สลับกล้องหน้า/หลัง)
============================================================ -->
<div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <!-- Header สีเขียว #00796b ตามแบบสแกนบาร์โค้ดสินค้า -->
            <div class="modal-header text-white" style="background:#00796b;">
                <h5 class="modal-title fs-5 mb-0">
                    <i class="fa-solid fa-barcode"></i> สแกนบาร์โค้ดสินค้า
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-light fw-bold shadow-sm" id="btnSwitchCamera" title="สลับกล้องหน้า/หลัง">
                        <i class="fa-solid fa-arrows-rotate"></i> สลับกล้อง
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <!-- Body แสดงภาพจากกล้อง -->
            <div class="modal-body text-center p-3">
                <div id="qr-reader"></div>
                <p id="scan-status" class="mt-3 mb-0 fw-bold text-primary">
                    <i class="fa-solid fa-spinner fa-spin"></i> กำลังเตรียมกล้อง...
                </p>
            </div>

            <!-- Footer ปุ่มสลับกล้องด้านล่าง และปุ่มปิด -->
            <div class="modal-footer d-flex justify-content-between bg-light">
                <button type="button" class="btn btn-primary fw-bold" id="btnSwitchCameraFooter">
                    <i class="fa-solid fa-arrows-rotate"></i> สลับกล้องหน้า / หลัง
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<!-- CDN Scripts (อิสระ 100%) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ============================================================
// โค้ดควบคุมกล้องและการสลับกล้องหน้า/หลัง (Standalone Scanner)
// ============================================================
let html5QrcodeScanner = null;
let availableCameras = [];
let currentCameraIndex = 0;
let currentFacingMode = "environment"; // ค่าเริ่มต้นเป็นกล้องหลัง
let currentCameraConfig = null;
let isSwitchingCamera = false; // กันกดสลับกล้องซ้ำระหว่างที่กำลังสลับอยู่

// เลือกกล้อง "หลัง" และ "หน้า" มาอย่างละ 1 ตัวสำหรับสลับ
// (มือถือรุ่นใหม่มักมีกล้องหลังหลายตัว เช่น wide/ultra-wide ถ้าวนสลับทุกตัวที่เจอ
// จะไปเจอกล้องหลังตัวอื่นที่ใช้กับสแกนบาร์โค้ดไม่ได้ ทำให้ดูเหมือน "สลับกล้องหลังไม่ได้")
function buildCameraToggleList(devices) {
    const backCam = devices.find(d => /back|rear|environment/i.test(d.label));
    const frontCam = devices.find(d => /front|user|face/i.test(d.label));
    const list = [];
    if (backCam) list.push(backCam);
    if (frontCam && (!backCam || frontCam.id !== backCam.id)) list.push(frontCam);
    if (list.length > 0) return list;
    // หา label ไม่เจอเลย (บางเบราว์เซอร์ไม่ส่ง label มา) ใช้ 2 ตัวแรกที่เจอแทน
    return devices.slice(0, 2);
}

// ฟังก์ชันหยุดกล้องอย่างปลอดภัย
function stopScanner() {
    if (html5QrcodeScanner) {
        let isRunning = false;
        try {
            isRunning = html5QrcodeScanner.isScanning;
        } catch (e) {
            isRunning = false;
        }
        if (isRunning) {
            return html5QrcodeScanner.stop().catch(err => {
                console.warn("Scanner stop warning:", err);
            });
        }
    }
    return Promise.resolve();
}

// ฟังก์ชันสลับกล้อง (หน้า / หลัง)
function switchCamera() {
    // กันกดซ้ำระหว่างกำลังสลับ หรือกล้องยังไม่พร้อม (เช่น กดเร็วเกินไปก่อน modal เปิดกล้องเสร็จ)
    if (isSwitchingCamera || !html5QrcodeScanner) {
        return;
    }
    isSwitchingCamera = true;
    $('#btnSwitchCamera, #btnSwitchCameraFooter').prop('disabled', true);
    $('#scan-status').html('<i class="fa-solid fa-spinner fa-spin"></i> กำลังสลับกล้อง...').removeClass('text-success text-danger').addClass('text-primary');

    if (availableCameras && availableCameras.length > 1) {
        // หากมีหลายกล้อง ให้วนสลับตามกล้องในเครื่อง
        currentCameraIndex = (currentCameraIndex + 1) % availableCameras.length;
        currentCameraConfig = availableCameras[currentCameraIndex].id;
    } else {
        // หากตรวจไม่พบรายชื่อ ให้สลับโหมด facingMode (หน้า/หลัง)
        currentFacingMode = (currentFacingMode === "environment") ? "user" : "environment";
        currentCameraConfig = { facingMode: currentFacingMode };
    }

    startScannerWithId(currentCameraConfig).catch(err => {
        console.error("Switch camera failed", err);
        $('#scan-status').html('<i class="fa-solid fa-triangle-exclamation"></i> สลับกล้องไม่สำเร็จ').removeClass('text-primary text-success').addClass('text-danger');
    }).finally(() => {
        isSwitchingCamera = false;
        $('#btnSwitchCamera, #btnSwitchCameraFooter').prop('disabled', false);
    });
}

// ผูก Event ให้ปุ่มสลับกล้องทั้ง 2 จุด (Header และ Footer)
$(document).on('click', '#btnSwitchCamera, #btnSwitchCameraFooter', function () {
    switchCamera();
});

// กดปุ่มเพื่อเปิด Modal สแกน
$('#btnScanBarcode').click(function () {
    $('#scan-status').html('<i class="fa-solid fa-spinner fa-spin"></i> กำลังเตรียมกล้อง...').removeClass('text-success text-danger').addClass('text-primary');
    $('#scannerModal').modal('show');
});

// เมื่อ Modal แสดงผล ให้เปิดกล้องอัตโนมัติ
$('#scannerModal').on('shown.bs.modal', function () {
    if (!html5QrcodeScanner) {
        html5QrcodeScanner = new Html5Qrcode("qr-reader");
    }

    Html5Qrcode.getCameras().then(devices => {
        availableCameras = buildCameraToggleList(devices || []);
        if (availableCameras.length > 0) {
            // ลิสต์ถูกจัดให้กล้องหลัง (ถ้าหาเจอ) อยู่ตัวแรกแล้วจาก buildCameraToggleList
            currentCameraIndex = 0;
            currentCameraConfig = availableCameras[currentCameraIndex].id;
            currentFacingMode = "environment";
            startScannerWithId(currentCameraConfig);
        } else {
            currentFacingMode = "environment";
            currentCameraConfig = { facingMode: "environment" };
            startScannerWithId(currentCameraConfig);
        }
    }).catch(err => {
        currentFacingMode = "environment";
        currentCameraConfig = { facingMode: "environment" };
        startScannerWithId(currentCameraConfig);
    });
});

// เริ่มสตรีมกล้อง
function startScannerWithId(cameraIdOrConfig, useHighRes = true) {
    return stopScanner().then(() => {
        // หน่วงเล็กน้อยให้กล้องเดิมปล่อย track ก่อนเปิดกล้องใหม่ (จำเป็นสำหรับการสลับกล้องบนมือถือหลายรุ่น)
        return new Promise(resolve => setTimeout(resolve, 300));
    }).then(() => {
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

        if (useHighRes) {
            config.videoConstraints = {
                width: { ideal: 1920 },
                height: { ideal: 1080 }
            };
        }

        return html5QrcodeScanner.start(
            cameraIdOrConfig,
            config,
            function (decodedText, decodedResult) {
                // เมื่ออ่านบาร์โค้ดสำเร็จ
                $('#scan-status').html('<i class="fa-solid fa-circle-check"></i> อ่านสำเร็จ!').removeClass('text-primary text-danger').addClass('text-success');
                if (navigator.vibrate) navigator.vibrate(200);

                $('#scanned_result_input').val(decodedText);

                setTimeout(() => {
                    stopScanner().finally(() => {
                        $('#scannerModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'อ่านสำเร็จ!',
                            html: 'รหัสบาร์โค้ด: <b class="text-success fs-5">' + decodedText + '</b>',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#198754'
                        });
                    });
                }, 500);
            },
            function (errorMessage) {
                if ($('#scan-status').hasClass('text-primary') && !$('#scan-status').html().includes('กำลังค้นหา')) {
                    $('#scan-status').html('<i class="fa-solid fa-circle-notch fa-spin"></i> กำลังค้นหาบาร์โค้ด...').removeClass('text-success text-danger').addClass('text-primary');
                }
            }
        ).catch(err => {
            if (useHighRes) {
                console.warn("High-res camera start failed, retrying with basic config...", err);
                return startScannerWithId(cameraIdOrConfig, false);
            } else {
                console.error("Camera start completely failed", err);
                $('#scan-status').html('<i class="fa-solid fa-triangle-exclamation"></i> ไม่สามารถเปิดกล้องได้').removeClass('text-primary text-success').addClass('text-danger');
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเปิดกล้องได้ (โปรดตรวจสอบสิทธิ์การใช้งานกล้องในเบราว์เซอร์)', 'error');
                throw err; // ส่งต่อ error ให้ตัวเรียก (เช่น switchCamera) รู้ว่าล้มเหลว
            }
        });
    });
}

// ปิดกล้องเมื่อปิด Modal
$('#scannerModal').on('hidden.bs.modal', function () {
    stopScanner();
});

// ปุ่มคัดลอกรหัส
$('#btnCopyResult').click(function () {
    let val = $('#scanned_result_input').val();
    if (val && val !== '-- รหัสจะแสดงที่นี่ --') {
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
