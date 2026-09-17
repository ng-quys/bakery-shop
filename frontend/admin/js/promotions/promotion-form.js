import {
    getPromotion,
    createPromotion,
    updatePromotion
} from './promotion-api.js';

const modal = document.getElementById('promotionModal');
const form = document.getElementById('promotionForm');

const btnAdd = document.getElementById('btnAdd');
const btnCloseModal = document.getElementById('btnCloseModal');
const btnCancel = document.getElementById('btnCancel');
const modalTitle = document.getElementById('modalTitle');

const maKMInput = document.getElementById('MaKM');

let editingCode = null;

function openModal() {
    modal.classList.remove('hidden');
}

function closeModal() {
    modal.classList.add('hidden');

    form.reset();

    editingCode = null;

    maKMInput.disabled = false;

    modalTitle.textContent = 'Thêm khuyến mãi';
}

function getFormData() {
    const danhMucText = document
        .getElementById('DanhMuc')
        .value
        .trim();

    const danhMuc = danhMucText
        ? danhMucText
            .split(',')
            .map(item => item.trim())
            .filter(Boolean)
        : [];

    return {
        MaKM: maKMInput.value.trim(),
        TenKM: document.getElementById('TenKM').value.trim(),
        MoTa: document.getElementById('MoTa').value.trim(),
        LoaiKM: document.getElementById('LoaiKM').value,
        GiaTri: Number(document.getElementById('GiaTri').value),
        GiaTriToiDa:
            document.getElementById('GiaTriToiDa').value || null,
        DonToiThieu:
            Number(document.getElementById('DonToiThieu').value || 0),
        PhamViApDung:
            document.getElementById('PhamViApDung').value,
        DanhMuc: danhMuc,
        NgayBatDau:
            document.getElementById('NgayBatDau').value,
        NgayKetThuc:
            document.getElementById('NgayKetThuc').value,
        SoLuong:
            Number(document.getElementById('SoLuong').value || 0),
        TrangThai:
            document.getElementById('TrangThai').value
    };
}

function fillForm(item) {
    maKMInput.value = item.MaKM || '';
    document.getElementById('TenKM').value = item.TenKM || '';
    document.getElementById('MoTa').value = item.MoTa || '';
    document.getElementById('LoaiKM').value = item.LoaiKM || 'Phần trăm';
    document.getElementById('GiaTri').value = item.GiaTri ?? '';
    document.getElementById('GiaTriToiDa').value = item.GiaTriToiDa ?? '';
    document.getElementById('DonToiThieu').value = item.DonToiThieu ?? 0;
    document.getElementById('PhamViApDung').value =
        item.PhamViApDung || 'Toàn bộ';

    document.getElementById('DanhMuc').value =
        (item.DanhMuc || [])
            .map(dm => dm.MaDM)
            .join(',');

    document.getElementById('NgayBatDau').value =
        item.NgayBatDau || '';

    document.getElementById('NgayKetThuc').value =
        item.NgayKetThuc || '';

    document.getElementById('SoLuong').value =
        item.SoLuong ?? 0;

    document.getElementById('TrangThai').value =
        item.TrangThai || 'Đang hoạt động';
}

btnAdd.addEventListener('click', () => {
    form.reset();

    editingCode = null;

    maKMInput.disabled = false;

    modalTitle.textContent = 'Thêm khuyến mãi';

    openModal();
});

btnCloseModal.addEventListener('click', closeModal);
btnCancel.addEventListener('click', closeModal);

window.addEventListener('edit-promotion', async event => {
    try {
        const maKM = event.detail;

        const result = await getPromotion(maKM);

        editingCode = maKM;

        modalTitle.textContent = 'Sửa khuyến mãi';

        fillForm(result.data);

        maKMInput.disabled = true;

        openModal();

    } catch (error) {
        alert(
            error.message ||
            'Không lấy được thông tin khuyến mãi'
        );
    }
});

form.addEventListener('submit', async event => {
    event.preventDefault();

    const data = getFormData();

    try {
        if (editingCode) {
            await updatePromotion(editingCode, data);

            alert('Cập nhật khuyến mãi thành công');
        } else {
            await createPromotion(data);

            alert('Thêm khuyến mãi thành công');
        }

        closeModal();

        window.dispatchEvent(
            new CustomEvent('promotion-saved')
        );

    } catch (error) {
        console.error(error);

        if (error.errors) {
            alert(
                Object.values(error.errors).join('\n')
            );
            return;
        }

        alert(
            error.message ||
            'Có lỗi xảy ra'
        );
    }
});