<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5 d-flex align-items-center justify-content-center" style="min-height: 60vh;">
    <div class="card border-0 shadow-sm text-center p-4 p-md-5 custom-success-card" style="max-width: 580px; border-radius: 20px;">
        
        <div class="success-animation-wrapper mb-4">
            <div class="success-icon-circle shadow-sm">
                <i class="fa-solid fa-check fs-1 text-white animate-pop"></i>
            </div>
            <span class="dot dot-1"></span>
            <span class="dot dot-2"></span>
            <span class="dot dot-3"></span>
        </div>

        <h2 class="fw-bold text-dark mb-3">Đặt hàng thành công!</h2>
        
        <p class="text-secondary mb-4 mx-auto" style="max-width: 420px; font-size: 1.05rem; line-height: 1.6;">
            Cảm ơn bạn đã tin tưởng và lựa chọn sản phẩm của chúng tôi. Đơn hàng của bạn đã được tiếp nhận và đang được hệ thống xử lý một cách nhanh chóng nhất.
        </p>

        <div class="p-3 bg-light rounded-3 mb-4 border d-inline-flex align-items-center justify-content-center gap-2 mx-auto" style="font-size: 0.9rem; font-weight: 500;">
            <span class="text-muted">Hệ thống:</span>
            <span class="text-success fw-bold text-uppercase"><i class="fa-solid fa-receipt me-1"></i> Đã kích hoạt</span>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 mt-2">
            <a href="/webbanhang/Product" class="btn btn-gradient px-4 py-2.5">
                <i class="fa-solid fa-bag-shopping me-2"></i> Tiếp tục mua sắm
            </a>
        </div>

    </div>
</div>

<style>
    /* Hiệu ứng nổi bần bật cho Card */
    .custom-success-card {
        background: #ffffff;
        transition: transform 0.3s ease;
    }
    .custom-success-card:hover {
        transform: translateY(-2px);
    }

    /* Thiết kế vòng tròn Icon Check-mark chuyển động */
    .success-animation-wrapper {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto;
    }
    .success-icon-circle {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #22c55e, #10b981); /* Gradient xanh lá cây biểu thị sự thành công */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    /* Animation cho Icon nảy nhẹ lên khi load trang */
    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .animate-pop {
        animation: popCheck 0.3s 0.4s ease forwards;
        opacity: 0;
        transform: scale(0.5);
    }
    @keyframes popCheck {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Các chấm nhỏ bay decor xung quanh icon */
    .dot {
        position: absolute;
        border-radius: 50%;
        background: #10b981;
        opacity: 0.6;
    }
    .dot-1 { width: 8px; height: 8px; top: 10%; left: -5%; animation: float1 2s infinite ease-in-out; }
    .dot-2 { width: 6px; height: 6px; bottom: 15%; right: -8%; animation: float2 2.5s infinite ease-in-out; }
    .dot-3 { width: 10px; height: 10px; bottom: -5%; left: 20%; animation: float1 3s infinite ease-in-out; }
    
    @keyframes float1 { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    @keyframes float2 { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(6px); } }

    /* Nút bấm mua sắm Gradient đồng nhất với Header hệ thống */
    .btn-gradient {
        background: linear-gradient(45deg, #4f46e5, #06b6d4);
        color: #fff !important;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        padding-left: 2rem !important;
        padding-right: 2rem !important;
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        opacity: 0.95;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>