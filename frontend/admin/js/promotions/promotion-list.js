import {
    getPromotions,
    deletePromotion
} from './promotion-api.js';

const tbody = document.getElementById('promotionTableBody');
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const btnSearch = document.getElementById('btnSearch');
const pagination = document.getElementById('pagination');
const messageBox = document.getElementById('message');

let currentPage = 1;
const limit = 10;

function showMessage(message, type = 'success') {
    messageBox.innerHTML = `
        <div class="message ${type}">
            ${message}
        </div>
    `;

    setTimeout(() => {
        messageBox.innerHTML = '';
    }, 3000);
}

function formatValue(item) {
    if (item.LoaiKM === 'Phần trăm') {
        return `${item.GiaTri}%`;
    }

    return Number(item.GiaTri || 0).toLocaleString('vi-VN') + ' đ';
}

function formatDate(value) {
    if (!value) return '';

    const date = new Date(value);

    return date.toLocaleString('vi-VN');
}

function renderTable(items) {
    if (!items.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="center">
                    Không có dữ liệu
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = items.map(item => `
        <tr>
            <td>${item.MaKM}</td>

            <td>
                <strong>${item.TenKM}</strong>
                <div class="sub-text">
                    ${item.MoTa || ''}
                </div>
            </td>

            <td>${item.LoaiKM}</td>

            <td>${formatValue(item)}</td>

            <td>
                <div>${formatDate(item.NgayBatDau)}</div>
                <div class="sub-text">
                    đến ${formatDate(item.NgayKetThuc)}
                </div>
            </td>

            <td>${item.SoLuong ?? 0}</td>

            <td>
                <span class="badge">
                    ${item.TrangThai}
                </span>
            </td>

            <td>
                <button
                    class="btn btn-small btn-edit"
                    data-code="${item.MaKM}"
                >
                    Sửa
                </button>

                <button
                    class="btn btn-small btn-danger btn-delete"
                    data-code="${item.MaKM}"
                >
                    Xóa
                </button>
            </td>
        </tr>
    `).join('');

    bindTableEvents();
}

function renderPagination(data) {
    const totalPages = data.totalPages || 1;

    pagination.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');

        button.textContent = i;

        button.className =
            i === currentPage
                ? 'page-btn active'
                : 'page-btn';

        button.addEventListener('click', () => {
            currentPage = i;
            loadPromotions();
        });

        pagination.appendChild(button);
    }
}

async function loadPromotions() {
    try {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="center">
                    Đang tải...
                </td>
            </tr>
        `;

        const result = await getPromotions({
            page: currentPage,
            limit,
            search: searchInput.value.trim(),
            status: statusFilter.value
        });

        renderTable(result.data.items);
        renderPagination(result.data.pagination);

    } catch (error) {
        console.error(error);

        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="center error-text">
                    Không tải được dữ liệu
                </td>
            </tr>
        `;

        showMessage(
            error.message || 'Lỗi khi tải dữ liệu',
            'error'
        );
    }
}

function bindTableEvents() {
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', () => {
            window.dispatchEvent(
                new CustomEvent('edit-promotion', {
                    detail: button.dataset.code
                })
            );
        });
    });

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', async () => {
            const maKM = button.dataset.code;

            if (!confirm(`Xóa khuyến mãi ${maKM}?`)) {
                return;
            }

            try {
                await deletePromotion(maKM);

                showMessage('Xóa khuyến mãi thành công');

                loadPromotions();

            } catch (error) {
                showMessage(
                    error.message || 'Không thể xóa',
                    'error'
                );
            }
        });
    });
}

btnSearch.addEventListener('click', () => {
    currentPage = 1;
    loadPromotions();
});

statusFilter.addEventListener('change', () => {
    currentPage = 1;
    loadPromotions();
});

searchInput.addEventListener('keydown', event => {
    if (event.key === 'Enter') {
        currentPage = 1;
        loadPromotions();
    }
});

window.addEventListener('promotion-saved', () => {
    loadPromotions();
});

loadPromotions();