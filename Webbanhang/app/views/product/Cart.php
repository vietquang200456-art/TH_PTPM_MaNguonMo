<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold">
            Giỏ hàng của bạn
        </h2>

        <?php if (!empty($cartItems)): ?>

            <a href="/webbanhang/Product/clearCart"
               class="btn btn-danger"
               onclick="return confirm('Bạn muốn xóa toàn bộ giỏ hàng?');">

                <i class="fa-solid fa-trash"></i>
                Xóa tất cả

            </a>

        <?php endif; ?>

    </div>

    <?php if (!empty($cartItems)): ?>

        <form method="POST"
              action="/webbanhang/Product/checkout">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-dark text-center">

                        <tr>

                            <th width="60">

                                <input type="checkbox"
                                       id="checkAll">

                            </th>

                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th width="180">Số lượng</th>
                            <th>Tổng</th>
                            <th>Hành động</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($cartItems as $id => $item): ?>

                            <?php
                                $subTotal =
                                    $item['price'] *
                                    $item['quantity'];
                            ?>

                            <tr>

                                <!-- CHECKBOX -->
                                <td class="text-center">

                                    <input
                                        type="checkbox"
                                        class="product-check"
                                        name="selectedProducts[]"
                                        value="<?php echo $id; ?>"
                                        data-price="<?php echo $subTotal; ?>"
                                        checked
                                    >

                                </td>

                                <!-- IMAGE -->
                                <td width="120" class="text-center">

                                    <?php if (!empty($item['image'])): ?>

                                        <img
                                            src="/webbanhang/<?php echo $item['image']; ?>"
                                            class="img-thumbnail"
                                            width="100"
                                        >

                                    <?php endif; ?>

                                </td>

                                <!-- NAME -->
                                <td>

                                    <strong>

                                        <?php
                                            echo htmlspecialchars(
                                                $item['name'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );
                                        ?>

                                    </strong>

                                </td>

                                <!-- PRICE -->
                                <td class="text-danger fw-bold">

                                    <?php
                                        echo number_format(
                                            $item['price'],
                                            0,
                                            ',',
                                            '.'
                                        );
                                    ?>

                                    đ

                                </td>

                                <!-- QUANTITY -->
                                <td>

                                    <div class="d-flex justify-content-center align-items-center gap-2">

                                        <a href="/webbanhang/Product/decrease/<?php echo $id; ?>"
                                           class="btn btn-sm btn-outline-danger">

                                            -

                                        </a>

                                        <span class="fw-bold px-2">

                                            <?php
                                                echo $item['quantity'];
                                            ?>

                                        </span>

                                        <a href="/webbanhang/Product/increase/<?php echo $id; ?>"
                                           class="btn btn-sm btn-outline-success">

                                            +

                                        </a>

                                    </div>

                                </td>

                                <!-- SUBTOTAL -->
                                <td class="fw-bold text-primary">

                                    <?php
                                        echo number_format(
                                            $subTotal,
                                            0,
                                            ',',
                                            '.'
                                        );
                                    ?>

                                    đ

                                </td>

                                <!-- REMOVE -->
                                <td class="text-center">

                                    <a href="/webbanhang/Product/remove/<?php echo $id; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Xóa sản phẩm này?');">

                                        <i class="fa-solid fa-trash"></i>

                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <!-- TOTAL -->
            <div class="card shadow-sm border-0 p-4 mt-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1">
                            Tổng thanh toán
                        </h5>

                        <small class="text-muted">
                            Chỉ tính các sản phẩm được chọn
                        </small>

                    </div>

                    <h3 class="text-danger fw-bold mb-0"
                        id="totalAmount">

                        0 đ

                    </h3>

                </div>

            </div>

            <!-- ACTION -->
            <div class="d-flex justify-content-between mt-4">

                <a href="/webbanhang/Product"
                   class="btn btn-secondary">

                    <i class="fa-solid fa-arrow-left"></i>
                    Tiếp tục mua sắm

                </a>

                <button type="submit"
                        class="btn btn-success px-4">

                    <i class="fa-solid fa-credit-card"></i>
                    Thanh toán đã chọn

                </button>

            </div>

        </form>

    <?php else: ?>

        <!-- EMPTY CART -->
        <div class="text-center py-5">

            <i class="fa-solid fa-cart-shopping
                      display-3 text-secondary mb-3"></i>

            <h4 class="fw-bold">
                Giỏ hàng đang trống
            </h4>

            <p class="text-muted">
                Hãy thêm sản phẩm vào giỏ hàng.
            </p>

            <a href="/webbanhang/Product"
               class="btn btn-primary">

                Tiếp tục mua sắm

            </a>

        </div>

    <?php endif; ?>

</div>

<!-- SCRIPT -->
<script>

    // Lấy toàn bộ checkbox sản phẩm
    const checkboxes =
        document.querySelectorAll('.product-check');

    // Tổng tiền
    const totalAmount =
        document.getElementById('totalAmount');

    // Checkbox tất cả
    const checkAll =
        document.getElementById('checkAll');

    // Hàm tính tổng
    function calculateTotal()
    {
        let total = 0;

        checkboxes.forEach(function(checkbox){

            if(checkbox.checked){

                total += parseFloat(
                    checkbox.dataset.price
                );
            }
        });

        // Format VNĐ
        totalAmount.innerText =
            total.toLocaleString('vi-VN') + ' đ';
    }

    // Event checkbox từng sản phẩm
    checkboxes.forEach(function(checkbox){

        checkbox.addEventListener(
            'change',
            calculateTotal
        );
    });

    // Check all
    if(checkAll){

        checkAll.addEventListener('change', function(){

            checkboxes.forEach(function(checkbox){

                checkbox.checked =
                    checkAll.checked;
            });

            calculateTotal();
        });
    }

    // Tính tổng ban đầu
    calculateTotal();

</script>

<?php include 'app/views/shares/footer.php'; ?>