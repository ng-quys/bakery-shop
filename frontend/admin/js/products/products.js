import {
    mountShell
} from '../core/shell.js';

import {
    request
} from '../core/api.js';

import {
    esc,
    money,
    statusBadge
} from '../core/ui.js';


/* =====================================================
   SHELL
===================================================== */

mountShell({
    active: 'products',
    title: 'Quản lý sản phẩm',
    subtitle: 'Quản lý sản phẩm trong collection SanPhams.'
});


const page =
    document.getElementById('pageContent');


/* =====================================================
   PAGE
===================================================== */

page.innerHTML = `

<section class="card panel">

    <div class="panel-head">

        <div>

            <div class="panel-eyebrow">
                SẢN PHẨM
            </div>

            <div class="panel-title">
                Quản lý sản phẩm
            </div>

        </div>


        <button
            class="btn btn-primary"
            id="addProductBtn"
            type="button"
        >
            + Thêm mới
        </button>

    </div>


    <!-- FILTER -->
    <div class="toolbar">

        <input
            id="searchInput"
            type="text"
            placeholder="Tìm theo mã hoặc tên sản phẩm..."
        >

        <select id="categoryFilter">

            <option value="">
                Tất cả danh mục
            </option>

        </select>

        <select id="statusFilter">

            <option value="">
                Tất cả trạng thái
            </option>

            <option value="Đang bán">
                Đang bán
            </option>

            <option value="Ngừng bán">
                Ngừng bán
            </option>

        </select>


        <button
            class="btn"
            id="searchBtn"
            type="button"
        >
            Tìm
        </button>

    </div>


    <!-- TABLE -->
    <div class="table-wrap">

        <table>

            <thead>

                <tr>
                    <th>Mã SP</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Tồn kho</th>
                    <th>Kích thước / Giá</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>

            </thead>


            <tbody id="productTable">

                <tr>

                    <td
                        colspan="7"
                        class="empty"
                    >
                        Đang tải dữ liệu...
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</section>



<!-- =====================================================
     PRODUCT MODAL
===================================================== -->

<div
    class="modal hidden"
    id="productModal"
>

    <div class="modal-card product-modal-card">

        <div class="modal-head">

            <div>

                <div class="panel-eyebrow">
                    SẢN PHẨM BÁNH
                </div>

                <h2 id="productModalTitle">
                    Thêm sản phẩm
                </h2>

            </div>


            <button
                type="button"
                class="modal-close"
                id="closeProductModal"
            >
                ×
            </button>

        </div>


        <form id="productForm">

            <input
                type="hidden"
                id="productMode"
                value="create"
            >


            <div class="form-grid">


                <!-- MÃ SP -->
                <div class="form-group">

                    <label for="MaSP">
                        Mã sản phẩm *
                    </label>

                    <input
                        id="MaSP"
                        type="text"
                        placeholder="VD: BM05"
                        required
                    >

                </div>


                <!-- TÊN SP -->
                <div class="form-group">

                    <label for="TenSP">
                        Tên sản phẩm *
                    </label>

                    <input
                        id="TenSP"
                        type="text"
                        placeholder="VD: Bánh mousse dâu"
                        required
                    >

                </div>


                <!-- DANH MỤC -->
                <div class="form-group">

                    <label for="MaDM">
                        Danh mục *
                    </label>

                    <select
                        id="MaDM"
                        required
                    >

                        <option value="">
                            -- Chọn danh mục --
                        </option>

                    </select>

                </div>


                <!-- SỐ LƯỢNG -->
                <div class="form-group">

                    <label for="SoLuong">
                        Tồn kho
                    </label>

                    <input
                        id="SoLuong"
                        type="number"
                        min="0"
                        value="0"
                    >

                </div>


                <!-- TRẠNG THÁI -->
                <div class="form-group">

                    <label for="TrangThai">
                        Trạng thái
                    </label>

                    <select id="TrangThai">

                        <option value="Đang bán">
                            Đang bán
                        </option>

                        <option value="Ngừng bán">
                            Ngừng bán
                        </option>

                    </select>

                </div>


                <!-- HÌNH ẢNH -->
                <div class="form-group">

                    <label for="HinhAnh">
                        URL hình ảnh
                    </label>

                    <input
                        id="HinhAnh"
                        type="text"
                        placeholder="https://..."
                    >

                </div>


                <!-- MÔ TẢ -->
                <div class="form-group full">

                    <label for="MoTa">
                        Mô tả
                    </label>

                    <textarea
                        id="MoTa"
                        rows="3"
                        placeholder="Mô tả sản phẩm..."
                    ></textarea>

                </div>


                <!-- KÍCH THƯỚC / GIÁ -->
                <div class="form-group full">

                    <label>
                        Kích thước / Giá
                    </label>

                    <div
                        class="size-list"
                        id="sizeList"
                    ></div>


                    <button
                        type="button"
                        class="btn btn-small"
                        id="addSizeBtn"
                    >
                        + Thêm kích thước
                    </button>

                </div>

            </div>


            <div
                id="productError"
                class="form-error hidden"
            ></div>


            <div class="form-actions">

                <button
                    type="button"
                    class="btn"
                    id="cancelProductBtn"
                >
                    Hủy
                </button>


                <button
                    type="submit"
                    class="btn btn-primary"
                    id="saveProductBtn"
                >
                    Lưu sản phẩm
                </button>

            </div>

        </form>

    </div>

</div>



<!-- =====================================================
     DELETE MODAL
===================================================== -->

<div
    class="modal hidden"
    id="deleteProductModal"
>

    <div class="modal-card delete-modal-card">

        <div class="modal-head">

            <div>

                <div class="panel-eyebrow">
                    XÁC NHẬN
                </div>

                <h2>
                    Xóa sản phẩm?
                </h2>

            </div>

        </div>


        <p class="delete-message">

            Bạn có chắc muốn xóa sản phẩm

            <strong
                id="deleteProductName"
            ></strong>

            không?

        </p>


        <div class="form-actions">

            <button
                type="button"
                class="btn"
                id="cancelDeleteProduct"
            >
                Hủy
            </button>


            <button
                type="button"
                class="btn btn-danger"
                id="confirmDeleteProduct"
            >
                Xóa sản phẩm
            </button>

        </div>

    </div>

</div>



<div
    id="adminToast"
    class="admin-toast hidden"
></div>
`;


/* =====================================================
   ELEMENTS
===================================================== */

const tbody =
    document.getElementById('productTable');

const productModal =
    document.getElementById('productModal');

const deleteModal =
    document.getElementById('deleteProductModal');

const productForm =
    document.getElementById('productForm');

const errorBox =
    document.getElementById('productError');

const saveBtn =
    document.getElementById('saveProductBtn');

const sizeList =
    document.getElementById('sizeList');


let productToDelete = null;


/* =====================================================
   CATEGORY LOAD
===================================================== */

async function loadCategories() {

    try {

        const result =
            await request(
                '/admin/categories?limit=100'
            );


        const items =
            result?.data?.items
            ?? [];


        const filter =
            document.getElementById(
                'categoryFilter'
            );


        const formSelect =
            document.getElementById(
                'MaDM'
            );


        filter.innerHTML =
            `
            <option value="">
                Tất cả danh mục
            </option>
            `;


        formSelect.innerHTML =
            `
            <option value="">
                -- Chọn danh mục --
            </option>
            `;


        items.forEach(
            item => {

                const option =
                    `
                    <option value="${esc(item.MaDM)}">
                        ${esc(item.MaDM)} - ${esc(item.TenDM)}
                    </option>
                    `;


                filter.insertAdjacentHTML(
                    'beforeend',
                    option
                );


                formSelect.insertAdjacentHTML(
                    'beforeend',
                    option
                );

            }
        );

    }
    catch (error) {

        console.error(
            'Không tải được danh mục:',
            error
        );

    }

}


/* =====================================================
   LOAD PRODUCTS
===================================================== */

async function loadProducts() {

    tbody.innerHTML = `

        <tr>

            <td
                colspan="7"
                class="empty"
            >
                Đang tải dữ liệu...
            </td>

        </tr>

    `;


    const search =
        document
            .getElementById(
                'searchInput'
            )
            .value
            .trim();


    const category =
        document
            .getElementById(
                'categoryFilter'
            )
            .value;


    const status =
        document
            .getElementById(
                'statusFilter'
            )
            .value;


    let url =
        '/admin/products?limit=100';


    if (search) {

        url +=
            `&search=${
                encodeURIComponent(search)
            }`;

    }


    if (category) {

        url +=
            `&category=${
                encodeURIComponent(category)
            }`;

    }


    if (status) {

        url +=
            `&status=${
                encodeURIComponent(status)
            }`;

    }


    try {

        const result =
            await request(
                url
            );


        const items =
            result?.data?.items
            ?? [];


        renderProducts(
            items
        );

    }
    catch (error) {

        console.error(
            error
        );


        tbody.innerHTML = `

            <tr>

                <td
                    colspan="7"
                    class="empty"
                >
                    Không tải được dữ liệu
                </td>

            </tr>

        `;

    }

}


/* =====================================================
   RENDER SIZE
===================================================== */

function renderSizePrice(
    sizes
) {

    if (
        !Array.isArray(sizes)
        ||
        !sizes.length
    ) {

        return '-';

    }


    return sizes
        .map(
            item => {

                return `
                    <div>
                        ${
                            esc(
                                item.Ten
                                ?? ''
                            )
                        }:
                        ${
                            money(
                                item.Gia
                                ?? 0
                            )
                        }
                    </div>
                `;

            }
        )
        .join('');

}


/* =====================================================
   RENDER PRODUCTS
===================================================== */

function renderProducts(
    items
) {

    if (!items.length) {

        tbody.innerHTML = `

            <tr>

                <td
                    colspan="7"
                    class="empty"
                >
                    Chưa có sản phẩm
                </td>

            </tr>

        `;

        return;

    }


    tbody.innerHTML =
        items.map(
            item => `

                <tr>

                    <td>
                        <strong>
                            ${esc(item.MaSP)}
                        </strong>
                    </td>


                    <td>
                        ${esc(item.TenSP)}
                    </td>


                    <td>
                        ${esc(item.MaDM)}
                    </td>


                    <td>
                        ${esc(item.SoLuong ?? 0)}
                    </td>


                    <td>
                        ${renderSizePrice(item.KichThuoc)}
                    </td>


                    <td>
                        ${statusBadge(item.TrangThai)}
                    </td>


                    <td>

                        <div class="table-actions">

                            <button
                                type="button"
                                class="btn btn-small edit-product-btn"
                                data-code="${esc(item.MaSP)}"
                            >
                                Sửa
                            </button>


                            <button
                                type="button"
                                class="btn btn-small btn-danger delete-product-btn"
                                data-code="${esc(item.MaSP)}"
                                data-name="${esc(item.TenSP)}"
                            >
                                Xóa
                            </button>

                        </div>

                    </td>

                </tr>

            `
        )
        .join('');


    bindRowEvents();
}


/* =====================================================
   SIZE ROW
===================================================== */

function addSizeRow(
    name = '',
    price = ''
) {

    const row =
        document.createElement(
            'div'
        );


    row.className =
        'size-row';


    row.innerHTML = `

        <input
            type="text"
            class="size-name"
            placeholder="VD: Nhỏ"
            value="${esc(name)}"
        >

        <input
            type="number"
            class="size-price"
            min="0"
            placeholder="Giá"
            value="${esc(price)}"
        >

        <button
            type="button"
            class="btn btn-small btn-danger remove-size-btn"
        >
            ×
        </button>

    `;


    row
        .querySelector(
            '.remove-size-btn'
        )
        .addEventListener(
            'click',
            () => {

                row.remove();

            }
        );


    sizeList.appendChild(
        row
    );

}


/* =====================================================
   GET SIZE DATA
===================================================== */

function getSizes() {

    return [
        ...sizeList
            .querySelectorAll(
                '.size-row'
            )
    ]
    .map(
        row => {

            const name =
                row
                    .querySelector(
                        '.size-name'
                    )
                    .value
                    .trim();


            const price =
                Number(
                    row
                        .querySelector(
                            '.size-price'
                        )
                        .value
                    || 0
                );


            return {
                Ten:
                    name,
                Gia:
                    price
            };

        }
    )
    .filter(
        item =>
            item.Ten !== ''
    );

}


/* =====================================================
   CREATE MODAL
===================================================== */

function openCreateProduct() {

    productForm.reset();


    document
        .getElementById(
            'productMode'
        )
        .value =
            'create';


    document
        .getElementById(
            'productModalTitle'
        )
        .textContent =
            'Thêm sản phẩm';


    const codeInput =
        document.getElementById(
            'MaSP'
        );


    codeInput.disabled =
        false;


    document
        .getElementById(
            'TrangThai'
        )
        .value =
            'Đang bán';


    document
        .getElementById(
            'SoLuong'
        )
        .value =
            0;


    sizeList.innerHTML =
        '';


    addSizeRow(
        'Nhỏ',
        ''
    );


    errorBox
        .classList
        .add(
            'hidden'
        );


    productModal
        .classList
        .remove(
            'hidden'
        );

}


/* =====================================================
   EDIT MODAL
===================================================== */

async function openEditProduct(
    code
) {

    try {

        const result =
            await request(
                `/admin/products/${
                    encodeURIComponent(
                        code
                    )
                }`
            );


        const item =
            result.data;


        productForm.reset();


        document
            .getElementById(
                'productMode'
            )
            .value =
                'edit';


        document
            .getElementById(
                'productModalTitle'
            )
            .textContent =
                'Sửa sản phẩm';


        const codeInput =
            document
                .getElementById(
                    'MaSP'
                );


        codeInput.value =
            item.MaSP
            ?? '';


        codeInput.disabled =
            true;


        document
            .getElementById(
                'TenSP'
            )
            .value =
                item.TenSP
                ?? '';


        document
            .getElementById(
                'MaDM'
            )
            .value =
                item.MaDM
                ?? '';


        document
            .getElementById(
                'SoLuong'
            )
            .value =
                item.SoLuong
                ?? 0;


        document
            .getElementById(
                'TrangThai'
            )
            .value =
                item.TrangThai
                ?? 'Đang bán';


        document
            .getElementById(
                'HinhAnh'
            )
            .value =
                item.HinhAnh
                ?? '';


        document
            .getElementById(
                'MoTa'
            )
            .value =
                item.MoTa
                ?? '';


        sizeList.innerHTML =
            '';


        if (
            Array.isArray(
                item.KichThuoc
            )
            &&
            item.KichThuoc.length
        ) {

            item.KichThuoc
                .forEach(
                    size => {

                        addSizeRow(
                            size.Ten
                            ?? '',
                            size.Gia
                            ?? ''
                        );

                    }
                );

        }
        else {

            addSizeRow();

        }


        errorBox
            .classList
            .add(
                'hidden'
            );


        productModal
            .classList
            .remove(
                'hidden'
            );

    }
    catch (error) {

        showToast(
            error.message
            || 'Không tải được sản phẩm.'
        );

    }

}


/* =====================================================
   CLOSE
===================================================== */

function closeProductModal() {

    productModal
        .classList
        .add(
            'hidden'
        );

}


/* =====================================================
   PAYLOAD
===================================================== */

function getProductPayload() {

    return {

        MaSP:
            document
                .getElementById(
                    'MaSP'
                )
                .value
                .trim()
                .toUpperCase(),

        TenSP:
            document
                .getElementById(
                    'TenSP'
                )
                .value
                .trim(),

        MaDM:
            document
                .getElementById(
                    'MaDM'
                )
                .value,

        SoLuong:
            Number(
                document
                    .getElementById(
                        'SoLuong'
                    )
                    .value
                || 0
            ),

        TrangThai:
            document
                .getElementById(
                    'TrangThai'
                )
                .value,

        HinhAnh:
            document
                .getElementById(
                    'HinhAnh'
                )
                .value
                .trim(),

        MoTa:
            document
                .getElementById(
                    'MoTa'
                )
                .value
                .trim(),

        KichThuoc:
            getSizes()

    };

}


/* =====================================================
   VALIDATE
===================================================== */

function validateProduct(
    payload
) {

    if (!payload.MaSP) {

        return (
            'Vui lòng nhập mã sản phẩm.'
        );

    }


    if (!payload.TenSP) {

        return (
            'Vui lòng nhập tên sản phẩm.'
        );

    }


    if (!payload.MaDM) {

        return (
            'Vui lòng chọn danh mục.'
        );

    }


    if (
        payload.SoLuong < 0
    ) {

        return (
            'Tồn kho không được âm.'
        );

    }


    if (
        !payload.KichThuoc.length
    ) {

        return (
            'Vui lòng thêm ít nhất một kích thước.'
        );

    }


    const invalidSize =
        payload.KichThuoc
            .some(
                item =>
                    item.Gia < 0
            );


    if (invalidSize) {

        return (
            'Giá sản phẩm không được âm.'
        );

    }


    return null;

}


/* =====================================================
   SUBMIT
===================================================== */

productForm
    .addEventListener(
        'submit',
        async event => {

            event.preventDefault();


            const mode =
                document
                    .getElementById(
                        'productMode'
                    )
                    .value;


            const payload =
                getProductPayload();


            const validationError =
                validateProduct(
                    payload
                );


            if (
                validationError
            ) {

                showFormError(
                    validationError
                );

                return;

            }


            try {

                saveBtn.disabled =
                    true;


                saveBtn.textContent =
                    'Đang lưu...';


                /* CREATE */

                if (
                    mode ===
                    'create'
                ) {

                    await request(
                        '/admin/products',
                        {
                            method:
                                'POST',

                            body:
                                JSON.stringify(
                                    payload
                                )
                        }
                    );


                    showToast(
                        'Thêm sản phẩm thành công.'
                    );

                }


                /* UPDATE */

                else {

                    const code =
                        document
                            .getElementById(
                                'MaSP'
                            )
                            .value;


                    delete payload.MaSP;


                    await request(
                        `/admin/products/${
                            encodeURIComponent(
                                code
                            )
                        }`,
                        {
                            method:
                                'PUT',

                            body:
                                JSON.stringify(
                                    payload
                                )
                        }
                    );


                    showToast(
                        'Cập nhật sản phẩm thành công.'
                    );

                }


                closeProductModal();


                await loadProducts();

            }
            catch (error) {

                console.error(
                    error
                );


                showFormError(
                    error.message
                    || 'Không thể lưu sản phẩm.'
                );

            }
            finally {

                saveBtn.disabled =
                    false;


                saveBtn.textContent =
                    'Lưu sản phẩm';

            }

        }
    );


/* =====================================================
   DELETE
===================================================== */

function openDeleteProduct(
    code,
    name
) {

    productToDelete =
        code;


    document
        .getElementById(
            'deleteProductName'
        )
        .textContent =
            name
            || code;


    deleteModal
        .classList
        .remove(
            'hidden'
        );

}


function closeDeleteProduct() {

    productToDelete =
        null;


    deleteModal
        .classList
        .add(
            'hidden'
        );

}


document
    .getElementById(
        'confirmDeleteProduct'
    )
    .addEventListener(
        'click',
        async () => {

            if (
                !productToDelete
            ) {

                return;

            }


            const button =
                document
                    .getElementById(
                        'confirmDeleteProduct'
                    );


            try {

                button.disabled =
                    true;


                button.textContent =
                    'Đang xóa...';


                await request(
                    `/admin/products/${
                        encodeURIComponent(
                            productToDelete
                        )
                    }`,
                    {
                        method:
                            'DELETE'
                    }
                );


                closeDeleteProduct();


                showToast(
                    'Xóa sản phẩm thành công.'
                );


                await loadProducts();

            }
            catch (error) {

                console.error(
                    error
                );


                showToast(
                    error.message
                    || 'Không thể xóa sản phẩm.'
                );

            }
            finally {

                button.disabled =
                    false;


                button.textContent =
                    'Xóa sản phẩm';

            }

        }
    );


/* =====================================================
   ERROR
===================================================== */

function showFormError(
    message
) {

    errorBox.textContent =
        message;


    errorBox
        .classList
        .remove(
            'hidden'
        );

}


/* =====================================================
   TOAST
===================================================== */

function showToast(
    message
) {

    const toast =
        document
            .getElementById(
                'adminToast'
            );


    toast.textContent =
        message;


    toast
        .classList
        .remove(
            'hidden'
        );


    setTimeout(
        () => {

            toast
                .classList
                .add(
                    'hidden'
                );

        },
        3000
    );

}


/* =====================================================
   ROW EVENTS
===================================================== */

function bindRowEvents() {

    document
        .querySelectorAll(
            '.edit-product-btn'
        )
        .forEach(
            button => {

                button
                    .addEventListener(
                        'click',
                        () => {

                            openEditProduct(
                                button.dataset.code
                            );

                        }
                    );

            }
        );


    document
        .querySelectorAll(
            '.delete-product-btn'
        )
        .forEach(
            button => {

                button
                    .addEventListener(
                        'click',
                        () => {

                            openDeleteProduct(
                                button.dataset.code,
                                button.dataset.name
                            );

                        }
                    );

            }
        );

}


/* =====================================================
   EVENTS
===================================================== */

document
    .getElementById(
        'addProductBtn'
    )
    .addEventListener(
        'click',
        openCreateProduct
    );


document
    .getElementById(
        'addSizeBtn'
    )
    .addEventListener(
        'click',
        () => addSizeRow()
    );


document
    .getElementById(
        'closeProductModal'
    )
    .addEventListener(
        'click',
        closeProductModal
    );


document
    .getElementById(
        'cancelProductBtn'
    )
    .addEventListener(
        'click',
        closeProductModal
    );


document
    .getElementById(
        'cancelDeleteProduct'
    )
    .addEventListener(
        'click',
        closeDeleteProduct
    );


document
    .getElementById(
        'searchBtn'
    )
    .addEventListener(
        'click',
        loadProducts
    );


document
    .getElementById(
        'categoryFilter'
    )
    .addEventListener(
        'change',
        loadProducts
    );


document
    .getElementById(
        'statusFilter'
    )
    .addEventListener(
        'change',
        loadProducts
    );


document
    .getElementById(
        'searchInput'
    )
    .addEventListener(
        'keydown',
        event => {

            if (
                event.key ===
                'Enter'
            ) {

                loadProducts();

            }

        }
    );


productModal
    .addEventListener(
        'click',
        event => {

            if (
                event.target ===
                productModal
            ) {

                closeProductModal();

            }

        }
    );


deleteModal
    .addEventListener(
        'click',
        event => {

            if (
                event.target ===
                deleteModal
            ) {

                closeDeleteProduct();

            }

        }
    );


/* =====================================================
   INIT
===================================================== */

await loadCategories();

await loadProducts();