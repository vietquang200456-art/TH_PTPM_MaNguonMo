<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <!-- Form thanh toán -->
        <div class="col-lg-7">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="bg-primary text-white p-4">

                    <h2 class="fw-bold mb-1">
                        <i class="fa-solid fa-credit-card me-2"></i>
                        Thanh toán đơn hàng
                    </h2>

                    <p class="mb-0 opacity-75">
                        Vui lòng nhập đầy đủ thông tin để hoàn tất đặt hàng.
                    </p>

                </div>

                <!-- Body -->
                <div class="card-body p-4">

                    <!-- Hiển thị lỗi -->
                    <?php if (!empty($errors)): ?>

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                <?php foreach ($errors as $error): ?>

                                    <li>
                                        <?php echo $error; ?>
                                    </li>

                                <?php endforeach; ?>

                            </ul>

                        </div>

                    <?php endif; ?>

                    <!-- Form -->
                    <form method="POST"
                          action="/webbanhang/Product/processCheckout">

                        <!-- Họ tên -->
                        <div class="mb-4">

                            <label for="name"
                                   class="form-label fw-semibold">

                                <i class="fa-solid fa-user me-1"></i>
                                Họ và tên

                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control form-control-lg"
                                placeholder="Nhập họ tên của bạn"
                                required
                            >

                        </div>

                        <!-- Số điện thoại -->
                        <div class="mb-4">

                            <label for="phone"
                                   class="form-label fw-semibold">

                                <i class="fa-solid fa-phone me-1"></i>
                                Số điện thoại

                            </label>

                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                class="form-control form-control-lg"
                                placeholder="Ví dụ: 0987654321"
                                required
                            >

                            <div class="form-text">
                                Nhân viên sẽ liên hệ xác nhận đơn hàng.
                            </div>

                        </div>

                        <!-- Địa chỉ -->
                        <div class="mb-4">

                            <label for="address"
                                   class="form-label fw-semibold">

                                <i class="fa-solid fa-location-dot me-1"></i>
                                Địa chỉ nhận hàng

                            </label>

                            <textarea
                                id="address"
                                name="address"
                                rows="4"
                                class="form-control"
                                placeholder="Nhập địa chỉ nhận hàng đầy đủ..."
                                required
                            ></textarea>

                        </div>

                        <!-- Phương thức thanh toán -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                <i class="fa-solid fa-wallet me-1"></i>
                                Phương thức thanh toán

                            </label>

                            <div class="border rounded-3 p-3 bg-light">

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="payment_method"
                                        id="cod"
                                        checked
                                    >

                                    <label class="form-check-label"
                                           for="cod">

                                        Thanh toán khi nhận hàng (COD)

                                    </label>

                                </div>

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="payment_method"
                                        id="banking"
                                        disabled
                                    >

                                    <label class="form-check-label text-muted"
                                           for="banking">

                                        Chuyển khoản ngân hàng
                                        (Sắp cập nhật)

                                    </label>

                                </div>

                            </div>

                        </div>

                        <!-- Tổng tiền -->
                        <?php
                            $total = 0;

                            if (!empty($_SESSION['selectedCart'])) {

                                foreach ($_SESSION['selectedCart'] as $item) {

                                    $total +=
                                        $item['price'] *
                                        $item['quantity'];
                                }
                            }
                        ?>

                        <div class="border rounded-3 p-4 bg-light mb-4">

                            <div class="d-flex justify-content-between">

                                <span class="fw-semibold">
                                    Tổng thanh toán:
                                </span>

                                <span class="fs-4 fw-bold text-danger">

                                    <?php
                                        echo number_format(
                                            $total,
                                            0,
                                            ',',
                                            '.'
                                        );
                                    ?>

                                    đ

                                </span>

                            </div>

                        </div>

                        <!-- Button -->
                        <div class="d-flex justify-content-between gap-3">

                            <a href="/webbanhang/Product/cart"
                               class="btn btn-outline-secondary btn-lg w-50">

                                <i class="fa-solid fa-arrow-left me-1"></i>
                                Quay lại giỏ hàng

                            </a>

                            <button type="submit"
                                    class="btn btn-success btn-lg w-50">

                                <i class="fa-solid fa-check me-1"></i>
                                Xác nhận thanh toán

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- Sidebar thông tin -->
        <div class="col-lg-4 mt-4 mt-lg-0">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">

                        <i class="fa-solid fa-cart-shopping me-2"></i>
                        Đơn hàng của bạn

                    </h5>

                    <?php if (!empty($_SESSION['selectedCart'])): ?>

                        <?php foreach ($_SESSION['selectedCart'] as $item): ?>

                            <div class="d-flex align-items-center mb-3">

                                <!-- Ảnh -->
                                <div class="me-3">

                                    <?php if (!empty($item['image'])): ?>

                                        <img
                                            src="/webbanhang/<?php echo $item['image']; ?>"
                                            width="70"
                                            height="70"
                                            class="rounded object-fit-cover border"
                                        >

                                    <?php endif; ?>

                                </div>

                                <!-- Nội dung -->
                                <div class="flex-grow-1">

                                    <h6 class="mb-1">

                                        <?php
                                            echo htmlspecialchars(
                                                $item['name'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );
                                        ?>

                                    </h6>

                                    <small class="text-muted">

                                        SL:
                                        <?php echo $item['quantity']; ?>

                                    </small>

                                </div>

                                <!-- Giá -->
                                <div class="fw-bold text-danger">

                                    <?php
                                        echo number_format(
                                            $item['price'] *
                                            $item['quantity'],
                                            0,
                                            ',',
                                            '.'
                                        );
                                    ?>

                                    đ

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="alert alert-warning mb-0">

                            Giỏ hàng đang trống.

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

    body{
        background: #f5f7fb;
    }

    .card{
        border-radius: 20px;
    }

    .form-control,
    .form-select{
        border-radius: 12px;
        padding: 12px 14px;
    }

    .form-control:focus{
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }

    .btn{
        border-radius: 12px;
        font-weight: 500;
    }

    .object-fit-cover{
        object-fit: cover;
    }

</style>

<?php include 'app/views/shares/footer.php'; ?>